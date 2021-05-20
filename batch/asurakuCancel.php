<?php

    //在庫　更新された在庫csv
    //最終更新時間から 30分ごとに切り捨て 

    //トークン切れ店舗スキップ処理
    //四捨五入を含めた在庫計算

    include("/home/ecmasuser/nextengine/app/Config/database.php");  //開発サーバ
    //include("database.php");  //ローカル
    //include("chilkat_9_5_0.php");

    $companyList = [];  //処理対象の店舗リスト
    $limit = 500;       //在庫取得の一度の取得件数
    $id = isset($argv[1]) ? $argv[1] : ""; //バッチ引数
    $link;  //DBコネクション
    $fileName = "aruraku";  //出力ファイル名
    class Log {
        public static $c_id = "";
	    const log_dir = "home/ecmasuser/nextengine/app/tmp/logs/upd";
        /**
         * ログを生成する
         * @param string $message メッセージ
         * @param boolean $echo　trueならecho出力
         */
        public static function info($message, $echo = false, $common = false) {
            self::write(" INFO ".$message.PHP_EOL, $echo);     //通常
            if ($common) self::common_write(" INFO ".$message.PHP_EOL); //概要ログ
        }
        public static function warn($message, $echo = false, $common = false) {
            self::write(" WARN ".$message.PHP_EOL, $echo);     //警告
            if ($common) self::common_write(" WARN ".$message.PHP_EOL); //概要ログ
        }
        public static function error($message, $echo = false, $common = false) {
            self::write(" ERROR ".$message.PHP_EOL, $echo);    //エラー
            if ($common) self::common_write(" ERROR ".$message.PHP_EOL); //概要ログ
        }
        public static function tokenUpdate($company, $echo = false) {
            $message = "";
            $token = [  'access_token' => $company['access_token'],
                        'refresh_token' => $company['refresh_token']  ];
            foreach ($token as $key => $val) {
                $message .= "{$key}: {$val}".PHP_EOL;
            }
            self::write_another("(Asuraku) TOKEN Update ID:".self::$c_id.PHP_EOL.$message, $echo);    //token Update
        }

        private static function write($message, $echo) {
            $date = (new \DateTime);
            $YMD = $date->format("Y-m-d");
            $His = $date->format("H:i:s");
            $message = "{$YMD} {$His} {$message}";
            if ($echo) echo($message);

            if (empty(self::$c_id)) return;
            $_cid = "-".self::$c_id;
            //在庫に関する店舗ごとのログをzozo_csvUploadをまとめる。
            $file = fopen("asurakuCancel-{$YMD}{$_cid}.log", "a");
            fwrite($file, $message);
            fclose($file);
        }

        private static function common_write($message) {
            $date = (new \DateTime);
            $YMD = $date->format("Y-m-d");
            $His = $date->format("H:i:s");
            $message = "{$YMD} {$His} {$message}";
            //処理全体のログは専用のファイル
            $file = fopen("asurakuCancel-{$YMD}.log", "a");
            fwrite($file, $message);
            fclose($file);
        }
        private static function write_another($message, $echo) {
            $message = (new \DateTime)->format("Y-m-d H:i:s").$message;

            if ($echo) echo $message;

            $file = fopen("token.log", "a");
            fwrite($file, $message);
            fclose($file);
        }
    }

    function main() {
        global $companyList, $id, $link;
        $time_start = microtime(true);
        if (empty($id)) {
            Log::info("***** 対象全件 *****", true, true);
        } else {
            Log::$c_id = $id;
            Log::info("***** 対象店舗指定　id: {$id} *****", true, true);
        }
        Rakuten::initKey();
        Log::info(Rakuten::$authKey, true, true);
        $db_conf = (new DATABASE_CONFIG())->mysqldb;
        $link = mysqli_connect($db_conf['host'], $db_conf['login'], $db_conf['password'], $db_conf['database']);

        //処理対象の店舗情報を取得　
        // グローバル変数のcompanyListにセット
        $companyList = get_company_list($id);

        ini_set('xdebug.var_display_max_children', -1);
        ini_set('xdebug.var_display_max_data', -1);
        ini_set('xdebug.a', -1);
        
        //処理店舗の在庫情報を取得
        $stock = get_master_stock();
        //$setItems = get_import_stock($stock);

        mysqli_close($link);
        //アップロード用の在庫情報を出力を取得
        //csvExport($stock,[]);
        update_asuraku($stock);
        //アップロード実行
        //upload2();

        $time = microtime(true) - $time_start;
        Log::info("全体処理秒数 ***** {$time} 秒 *****", true);
    }


    //cronの設定は不要
    /**
     * 在庫0の商品情報取得
     */
	function get_master_stock() {
        $time_start1 = microtime(true);
        Log::info("get_master_stock  ************" , true, true);

        $url = "https://api.next-engine.org/api_v1_master_stock/search";
        $item = array("stock_goods_id",
                    "stock_free_quantity",                      //フリー在庫数 *
                    "stock_quantity",                           //在庫数 *　以下は不使用（念のため取得）	
                    "stock_allocation_quantity",                //引当数
                    "stock_defective_quantity",                 //不良在庫数
                    "stock_remaining_order_quantity",           //発注残数
                    "stock_out_quantity",                       //欠品数
                    "stock_advance_order_quantity",	            //予約在庫数
                    "stock_advance_order_allocation_quantity",	//予約引当数
                    "stock_advance_order_free_quantity"         //予約フリー在庫数
                    );
                    
        global $companyList, $limit, $link;
        $data = [];
        $all_cnt = 0; $com_idx = 0;

        foreach ($companyList as $company){
            $cnt = 0; $offset = 0;
            
            $com_result = [];
            Log::$c_id = $company["id"];
            $retry = true;
            while ($retry) {
                
                $post_data = array(
                    'access_token' => $company['access_token'],
                    'refresh_token' => $company['refresh_token'],
                    'wait_flag' => '1',
                    'stock_free_quantity-eq' => 0,  //フリー在庫0のもの
                    'limit'=> $limit,
                    'offset'=> $offset++ * $limit,
                    'fields' => implode(',', $item)
                );

                $ch = curl_init($url); // はじめ
                //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
                $html =  curl_exec($ch);
                $add_data = json_decode($html, true);

                array_push($com_result, $add_data);

                //新トークン登録
                if (updToken($add_data, $company)) {
                    //初回取得した変数を更新
                    $companyList[$com_idx]["access_token"] = $add_data["access_token"];
                    $companyList[$com_idx]["refresh_token"] = $add_data["refresh_token"];
                    //現在使用中トークンを更新
                    $company["access_token"] = $add_data["access_token"];
                    $company["refresh_token"] = $add_data["refresh_token"];
                }
                
                if (empty($add_data["access_token"])) {
                    Log::error("トークンの有効期限が切れています。", true, true);
                    $retry = false;
                    $add_data['count'] = 0;
                }

                if (isset($add_data['count']) && $add_data['count'] < $limit){
                    $retry = false;
                    //Log::info(json_encode($add_data), true, true);
                }
                $cnt += $add_data['count'];
                //$i++;
            }
            array_push($data, $com_result);
            Log::info("在庫0の商品取得件数 ***** {$cnt}件 *****", true);
            $all_cnt += $cnt;
            $com_idx++;
        }

        Log::info("処理対象在庫0の商品取得件数 ***** {$all_cnt}件 *****", true, true);
        $time = microtime(true) - $time_start1;
        Log::info("在庫0の商品取得処理秒数 ***** {$time} 秒 *****", true, true);
        
        return [$data, $item];
    }

    /**
     * あす楽更新
     */
    function update_asuraku($stock_all) {

        //$items = $data["itemUrlList"];
        //echo "今回取得分";
        //var_dump($items);
        //$newInventoryItems = $items;
        global $companyList;
        $i_com = 0;
        
        //在庫取得の配列分解中　商品名だけの列に
        foreach ($companyList as $company) {

            //$s_header = $stock_all[1];
            $stock_rec = $stock_all[0][$i_com];
            Log::$c_id = $company["id"];

            $fn = "asuraku-inventory-{$company['id']}";
            Log::info(" **** {$fn}", true, true);
            $updList = [];
            if ($stock_rec) {
                foreach($stock_rec as $s_data) {
                    $list = $s_data["data"];
                    if ($s_data["result"] == "success") {
                        $list = array_column($list, "stock_goods_id");
                        $updList = array_merge($updList, $list);
                    }
                }
            }
            Log::info(json_encode($updList), true, true);

            //$updList = ["test-test2","0002"];
            $file = fopen("{$fn}.txt", "w");
            fwrite($file, implode("\r\n", $updList));
            fclose($file);
    
            foreach ($updList as $upd) {
                //Log::info($upd, true);

                $checkRes = Rakuten::checkAsuraku($upd);
                if ($checkRes["asurakuDeliveryId"] == 1) {
                    //あす楽解除更新
                    $ret_data = Rakuten::update($upd);
                }
            }

        }
    }


    
    /**
     * cURL実行処理
     */
    class Rakuten {
        public static $c_id = "";
        public static $authKey = "";
        public static $header = "";

        /**
         * 楽天APIへの接続情報の設定
         * @param string $message メッセージ
         * @param boolean $echo　trueならecho出力
         */
        public static function init($url, $template = "") {
            self::$header = array("Content-Type: text/xml;charset=UTF-8");
            if (self::$authKey) {
                array_push(self::$header, "Authorization:".self::$authKey);
            } else {
                Log::warn(" no auth ", true, true);
            }
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, self::$header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            if ($template !== "" ) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $template);
            }
            $xml = curl_exec($ch);
            $info = curl_getinfo($ch);
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            
            /*
                //HTTPステータスあり
                console_log( "info".PHP_EOL );
                console_log( $info );
        
                //HTTPエラーステータス
                console_log( "errno".PHP_EOL );
                console_log( $errno );
                
                //HTTPエラー内容
                console_log( "error".PHP_EOL );
                console_log( $error );
            */
            
            if (CURLE_OK !== $errno) {
                throw new RuntimeException($error, $errno);
            }
            curl_close($ch);
            
            return $xml;
        }

        /**
         * 認証キーの設定
         */
        public static function initKey() {

            $hash = "c71e766e865876a98c802f392f3a0ebe14ab7c68";
            $unixtime = time();
            $url = "https://tool.ec-masters.net/lidp/rakuten.new.php?hash={$hash}{$unixtime}";
            
            try {
                $data = self::init($url);
                $data = self::_setResponseXml($data);
                return self::_getDataSet_key($data);
            } catch(Exception $e) {
                Log::error("Key取得エラー（initKey）", true, true);
                throw new Exception("Key取得エラー（initKey）");
            }
        }

        /**
         * あす楽解除更新 
         */
        public static function update($item) {
            
            $url = "https://api.rms.rakuten.co.jp/es/1.0/item/update";
            $params = [ "itemUrl" => $item ];
            $template = "<?xml version='1.0' encoding='UTF-8'?>
                        <request>
                            <itemUpdateRequest>
                                <item>
                                    <itemUrl>{$params['itemUrl']}</itemUrl>
                                    <asurakuDeliveryId />
                                </item>
                            </itemUpdateRequest>
                        </request>";
            //更新実行
            try {
                    $data = self::init($url, $template);
                    $data = self::_setResponseXml($data);
                    $ret_data = self::_updateDataSet_item($data);
                    
                    //Internal Server Error
                    if (http_response_code() == 500) {
                        throw new Exception("Internal Server Error", 500);
                    }
            } catch(Exception $e) {
                //HTTPエラー用レスポンスをセット
                $ret_data = [
                    "rescode" => http_response_code(),
                    "itemUrl" => $item,
                    "asurakuDeliveryId" => "",
                    "error" => ["reSendFlg" => false]
                ];
                if (http_response_code() == 500) {
                    $ret_data['error']['reSendFlg'] = true;
                }
                Log::error("あす楽解除更新（update） エラー");
            } finally {
                return $ret_data;
            }
            
        }

        /**
         * あす楽情報取得（１件）
         */
        public static function checkAsuraku($item) {
            $url = "https://api.rms.rakuten.co.jp/es/1.0/item/get?itemUrl={$item}";
            
            try {
                $data = self::init($url);
                $data = self::_setResponseXml($data);
                return self::_getDataSet_item($data);

            } catch(Exception $e){
                Log::error("商品情報（あす楽チェック）取得エラー（checkAsuraku）");
                throw new Exception("商品情報（あす楽チェック）取得エラー（checkAsuraku）");
            }
        }

        /**
         * レスポンスXMLを整形
         */
        private static function _setResponseXml( $xml ) {
            $notTag = ['S:', 'ns2:','env:','n1:','n2:','m:','xsi:'];
            $res_xml = str_ireplace($notTag, '', $xml);
            $data = simplexml_load_string($res_xml);
            return $data;
        }

        /**
         * キー情報取得レスポンスの配列整形
         * APIキー、ShopUrl
         */
        private static function _getDataSet_key($data) {
            if (empty($data)) {
                Log::error('認証情報にアクセスできませんでした', true, true);
                throw new Exception("_getDataSet_key Not Response");
            }
            // エラーログ出力
            $status = (string) $data->Header->Status;
            $_result = $data->Contents;
            
            //global $authKey, $shopUrl;
            self::$authKey = "ESA ".base64_encode( (string) $_result->serviceSecret.':'
                                                  .(string) $_result->licenseKey );
            $shopUrl = (string) $_result->RakutenUrl;
            if ("Error" == $status){
                Log::error($data->Header->Message, true, true);
            }
            return $status;
        }
        
        /**
         * あす楽商品取得レスポンスの配列整形
         */
        private static function _getDataSet_item($data) {

            $_result = $data->itemGetResult->item;
            $val = $_result->asurakuDeliveryId;
            
            $ret_data = [
                "rescode" => (string) $data->itemGetResult->code,
                "itemUrl" => (string) $_result->itemUrl,
                "asurakuDeliveryId" => (string) $val
            ];
            //console_log($ret_data);
            $error = self::_item_errors($ret_data["rescode"], "取得");
            $ret_data["error"] = $error;
            return $ret_data;
        }

        /**
         * 商品更新レスポンスの配列整形
         */
        private static function _updateDataSet_item($data){
            
            $_result = $data->itemUpdateResult->item;
            Log::info(json_encode($data), true, true);
            
            $ret_data = [
                "rescode" => (string) $data->itemUpdateResult->code,
                "itemUrl" => (string) $_result->itemUrl
                //,"asurakuDeliveryId" => (string) $_result->asurakuDeliveryId
            ];

            //console_log($ret_data);
            //商品取得時とエラーコードは同じ
            //$error = item_errors($ret_data["rescode"], "更新 商品管理番号:{$ret_data['itemUrl']}");
            //$ret_data["error"] = $error;
            return $ret_data;
        }

         /**
         * 商品系のエラー
         */
        private static function _item_errors($errCode, $actionStr) {
            
            $error =[
                "errMessage" => '',
                "reSendFlg" => false
            ];
            $info = preg_match("/N000/", $errCode);
            if ($info) {
                $error["errMessage"] = "正常終了";

            } elseif (preg_match("/S001|S002/", $errCode)) {
                $error["errMessage"] = "サーバーエラー：サービス停止";
                $error["reSendFlg"] = true;

            } elseif (preg_match("/S011|S021|S031|S999/", $errCode)) {
                $error["errMessage"] = "システムエラー：楽天側エラー";
                $error["reSendFlg"] = true;

            } elseif (preg_match("/C001/", $errCode)) {
                $error["errMessage"] = "クライアント要因エラー：商品IDが存在しません";

            } elseif (preg_match("/C011/", $errCode)) {
                $error["errMessage"] = "クライアント要因エラー：店舗IDが存在しません";

            } elseif (preg_match("/C113/", $errCode)) {
                $error["errMessage"] = "必須チェック：パラメータのフォーマットエラー";
                $error["reSendFlg"] = true;

            } elseif (preg_match("/C114/", $errCode)) {
                $error["errMessage"] = "必須チェック：商品一括登録サービスおよびカテゴリページ設定により商品情報の更新がロックされているため操作できません";
                $error["reSendFlg"] = true;

            } elseif (preg_match("/C201/", $errCode)) {
                $error["errMessage"] = "不正データ（共通）：指定されたイベントIDは存在しません";

            } elseif (preg_match("/C204/", $errCode)) {
                $error["errMessage"] = "不正データ（共通）：商品IDのフォーマットが正しくありません";
                $error["reSendFlg"] = true;

            } elseif (preg_match("/C219/", $errCode)) {
                $error["errMessage"] = "不正データ（共通）：不要なデータが入っています";
                $error["reSendFlg"] = true;

            } elseif (preg_match("/C301/", $errCode)) {
                $error["errMessage"] = "ID重複：登録・更新・削除時 :渡されたtransactionIDでの更新が既にされています";
                $error["reSendFlg"] = true;

            }

            //ログ処理
            if ($info) {
                Log::info("商品情報{$actionStr}　{$errCode} {$error['errMessage']}");
            } else {
                Log::error("商品情報{$actionStr}　{$errCode} {$error['errMessage']}");
            }
            
            echo $error['errMessage'].PHP_EOL;
            return $error;
        }

    }

    /**
     * トークン取得
     */
    function get_company_list($id) {
        $AND = "";  //バッチ処理対象の全件取得時
        if (!empty($id)) {
            $AND = "AND id='{$id}' "; //idで１件取得
        }

        //asuraku_clear_flag 　０：無効、１：有効
        $WHERE = "WHERE asuraku_clear_flag = 1 {$AND}";
        $sql = "select * from ne_users {$WHERE}";
        
        global $link;
        $res = $link->query($sql);
        $res = $res->fetch_all(MYSQLI_ASSOC);
        
        if (!$link) {
            Log::error('データベース接続失敗', true, true);
            Log::error("errno: ".mysqli_connect_errno(), true, true);
            Log::error("error: ".mysqli_connect_error(), true, true);
            exit;
        } else if(count($res) == 0) {
            Log::error("有効なcompanyが登録されていません", true, true);
            exit;
        }

        $i = 0; $_companyList = [];
        foreach ($res as $r) {  //選択行ごと 
            foreach ($r as $key => $val) {  //選択行の列ごと
                if ($key == "password") {
                    $_companyList[$i][$key] = base64_decode($val);
                } else {
                    $_companyList[$i][$key] = $val;
                }
            }
            $i++;
        }
        //Log::info(" ***** ".json_encode($companyList), true);
        return $_companyList;
    }
    
    /**
     * 新規トークン出力
     */
    function updToken($data, $company) {

        if (isset($data["access_token"])) {
            $newToken = $data["access_token"];
            
            global $link;
            $stmt = $link->prepare("UPDATE ne_users set access_token = ? ,refresh_token = ? where id = ?");

            if ($company["access_token"] <> $newToken) {
                $company["access_token"] = $newToken;
                $company["refresh_token"] = $data["refresh_token"];
                
                Log::$c_id = $company["id"];
                Log::tokenUpdate($company, true);

                //**** DB更新
                $stmt->bind_param("sss", $newToken, $data["refresh_token"], $company['id']);
                $stmt->execute();

                Log::info("トークンが更新されました。", true, true);
                return true;
            }
            return false;
        } else {
            Log::warn("トークンが更新できませんでした。", true, true);
            return false;
        }
    }
    main();
?>