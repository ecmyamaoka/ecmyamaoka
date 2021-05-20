<?php

    //在庫　更新された在庫csv
    //最終更新時間から 30分ごとに切り捨て 

    //トークン切れ店舗スキップ処理
    //四捨五入を含めた在庫計算

    include("/home/ecmasuser/nextengine/app/Config/database.php");  //開発サーバ
    //include("database.php");  //ローカル
    include("chilkat_9_5_0.php");

    $companyList = [];  //処理対象の店舗リスト
    $limit = 500;       //在庫取得の一度の取得件数
    //$id = isset($argv[1]) ? $argv[1] : ""; //バッチ引数

    //$id = 

    $link;  //DBコネクション
    $fileName = "zozo_inventory";  //出力ファイル名

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
            self::write_another(" TOKEN Update ID:".self::$c_id.PHP_EOL.$message, $echo);    //token Update
        }

        private static function write($message, $echo) {
            $date = (new \DateTime);
            $YMD = $date->format("Y-m-d");
            $His = $date->format("H:i:s");
            $message = "{$YMD} {$His} {$message}";
            if ($echo) echo($message);

            if (empty(self::$c_id)) return;
            $_cid = "-".self::$c_id;
            $file = fopen("/home/ecmasuser/nextengine/batch/stockUpload-{$YMD}{$_cid}.log", "a");
            fwrite($file, $message);
            fclose($file);
        }

        private static function common_write($message) {
            $date = (new \DateTime);
            $YMD = $date->format("Y-m-d");
            $His = $date->format("H:i:s");
            $message = "{$YMD} {$His} {$message}";

            $file = fopen("/home/ecmasuser/nextengine/batch/stockUpload-{$YMD}.log", "a");
            fwrite($file, $message);
            fclose($file);
        }
        private static function write_another($message, $echo) {
            $message = (new \DateTime)->format("Y-m-d H:i:s").$message;

            if ($echo) echo $message;

            $file = fopen("/home/ecmasuser/nextengine/batch/token.log", "a");
            fwrite($file, $message);
            fclose($file);
        }
    }

    
    function zozo_auth($id,$pass,$debug) {

        //初期化
        $glob = new CkGlobal();
        $success = $glob->UnlockBundle('ECMAST.CB1112021_dDfkA83p4eAL');
        if ($success != true) {
            Log::error($glob->lastErrorText(), true, true);
            exit;
        }
        $user_id = $id;
        $password = $pass;

        if($debug == true){
            #テスト
            $url = "https://dev99-apib.zozoclub.jp/cooperate/auth";
        }else{
            #本番
            $url = "https://apib.zozo.jp/cooperate/auth";
        }

        $xmlCharset = 'utf-8';
        $xml = "<?xml version='1.0' encoding='{$xmlCharset}' ?><request><user_id>{$user_id}</user_id><password>{$password}</password></request>";

        $http = new CkHttp();
        $http->put_CookieDir('memory');
        $http->put_SaveCookies(true);
        $http->put_SendCookies(true);
        
        $resp = $http->PostXml($url,$xml,$xmlCharset);
        if ($http->get_LastMethodSuccess() != true) {
            //認証エラー
            Log::error("Auth認証エラー", true, true);
            Log::error($http->lastErrorText(), true, true);
            exit;
        } else {
            Log::info("Auth認証", true, true);
            Log::info($resp->bodyStr(), true, true);
            echo $resp->bodyStr();
        }
        return $http;
    }


    function refresh_token($cplist) {

        foreach ($cplist as $cp){
            if($cp['id']==23){
                //var_dump($cp);

                $json = api_v1_login_company_info($cp['access_token'],$cp['refresh_token']);
                $json2 = api_v1_receiveorder_uploadpattern_info($cp['access_token'],$cp['refresh_token']);

                
                var_dump($json2);

                //echo $cp['company_name'] . ' ' . $json['result'];
                $http = zozo_auth($cp['zozo_id'],$cp['password'],false);
                ZozoRequestGoods($http,$cp['id']);

            }

        }

    }

    function api_v1_login_company_info($access_token,$refresh_token){

        $url = "https://api.next-engine.org/api_v1_login_company/info";
        $post_data = array(
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'wait_flag' => '1'
        );

        $ch = curl_init(); // はじめ
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
        $html =  curl_exec($ch);

        return json_decode($html, true);

    }

    function api_v1_receiveorder_uploadpattern_info($access_token,$refresh_token){

        $url = "https://api.next-engine.org/api_v1_receiveorder_uploadpattern/info";
        $post_data = array(
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'wait_flag' => '1'
        );

        $ch = curl_init(); // はじめ
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
        $html =  curl_exec($ch);

        return json_decode($html, true);

    }

    function ZozoRequestGoods($http,$shopid){

        $xmlCharset = 'utf-8';
        $goodsurl = "https://apib.zozo.jp/cooperate/RequestGoods";

        #取寄せデータの出力（ZOZO → BRAND）（最大抽出期間7 日）
        #指定期間に受けた客注のうちブランド様倉庫に要求するSKU を出力します。
        #プールプーさん　shopgroupid　1509
        $xml = '<?xml version="1.0" encoding="UTF-8"?><request><shopgroupid>1509</shopgroupid><start_date>2021/05/12 00:00:00</start_date><end_date>2021/05/12 14:00:00</end_date></request>';
        $resp = $http->PostXml($goodsurl,$xml,$xmlCharset);
        if ($http->get_LastMethodSuccess() != true) {
            //認証エラー
            print $http->lastErrorText() . "\n";
            exit;
        }else{

            $xml = simplexml_load_string($resp->bodyStr());
            $values = $xml->xpath('requestgoods');
            // foreach ( $values as $value ) {
            //     echo $value['goods_code'];
            //     echo '-';
            //     echo $value['cs_code'];
            //     echo ',';
            //     echo $value['request_quantity'];
            //     echo PHP_EOL;
            // }
            //print $resp->bodyStr();



        }

    }


    function main() {

        global $companyList,$id, $link;
        $time_start = microtime(true);
        if (empty($id)) {
            Log::info("***** 対象全件 *****", true, true);
        } else {
            Log::info("***** 対象店舗指定　id: {$id} *****", true, true);
        }

        $db_conf = (new DATABASE_CONFIG())->mysqldb;
        $link = mysqli_connect($db_conf['host'], $db_conf['login'], $db_conf['password'], $db_conf['database']);

        //処理対象の店舗情報を取得　
        // グローバル変数のcompanyListにセット
        $companyList = get_company_list($id);

        //Tokenのリフレッシュ
        refresh_token($companyList);
        
       

        if(0){

            ini_set('xdebug.var_display_max_children', -1);
            ini_set('xdebug.var_display_max_data', -1);
            ini_set('xdebug.var_display_max_depth', -1);
            
            //処理店舗の在庫情報を取得
            $stock = get_master_stock();
            $setItems = get_import_stock($stock);

            mysqli_close($link);
            //アップロード用の在庫情報を出力を取得
            csvExport($stock, $setItems);

            //アップロード実行(現在はコメントアウト)
            //upload2();
        }

        $time = microtime(true) - $time_start;
        LOG::info("全体処理秒数 ***** {$time} 秒 *****", true);
    }





    //cronの設定は不要
    /**
     * 在庫情報取得
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

        //在庫情報の一時テーブル作成
        $link->query("CREATE TEMPORARY TABLE tmp_stock ( `company_id` int, `stock_goods_id` varchar(200),`val` int )");
        //insert文のステートメント保持
        $stmt = $link->prepare("INSERT INTO tmp_stock VALUES ( ?, ?, ? )");
        $excludeList = [];

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
                    'stock_free_quantity-gte' => 1,  //フリー在庫1以上
                    'limit'=> $limit,
                    'offset'=> $offset++ * $limit,
                    'fields' => implode(',', $item)
                );

                $ch = curl_init(); // はじめ
                curl_setopt($ch, CURLOPT_URL, $url);
                //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
                $html =  curl_exec($ch);

                $add_data = json_decode($html, true);
                //Log::info(json_encode($add_data), true, true);
                if ($add_data["data"]){
                    foreach ($add_data["data"] as $rec) {
                        //比較用の一時テーブルに一行分追加
                        $stmt->bind_param("isi", $company["id"], $rec["stock_goods_id"], $rec["stock_free_quantity"]);
                        $stmt->execute();
                    }
                }
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
                    array_push($excludeList, $com_idx);
                    $retry = false;
                    $add_data['count'] = 0;
                } else if ($company["zozo_flag"] == 0) {
                    Log::error("処理対象外の店舗です。ログの件数は0件となります。", true, true);
                    array_push($excludeList, $com_idx);
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
            Log::info("在庫取得件数 ***** {$cnt}件 *****", true);
            $all_cnt += $cnt;
            $com_idx++;
        }

        $excludeList = array_unique($excludeList);
        foreach ($excludeList as $ex) {
            unset($companyList[$ex]);
        }
        $companyList = array_merge($companyList);

        Log::info("execlu2 ".json_encode($companyList), true);

        Log::info("処理対象在庫取得件数 ***** {$all_cnt}件 *****", true, true);
        $time = microtime(true) - $time_start1;
        Log::info("在庫取得処理秒数 ***** {$time} 秒 *****", true, true);
        
        return [$data, $item];
    }

    /**
     * インポートされた在庫情報を取得
     */
	function get_import_stock($stock) {
        $time_start1 = microtime(true);
        Log::info("get_import_stock  ************" , true, true);
        
        $data = [];
        $all_cnt = 0;
        $i_com =0;

        global $companyList, $link;
        $stmt = $link->prepare("SELECT i.shop_id, i.set_goods_id, i.brand_id, zozo_goods_id, cs_goods_id, t.val 
                FROM zozo_setitem_import i INNER JOIN tmp_stock t ON t.company_id = ? AND i.set_goods_id = t.stock_goods_id");

        foreach ($companyList as $company){
            $cnt = 0;

            $stmt->bind_param("s", $company['id']);
            $stmt->execute();
            $res = $stmt->get_result();
            $res = $res->fetch_all(MYSQLI_ASSOC);

            if (!$link) {
                Log::error('データベース接続失敗', true, true);
                Log::error("errno: ".mysqli_connect_errno(), true, true);
                Log::error("error: ".mysqli_connect_error(), true, true);
                break;
            } else if(count($res) == 0) {
                Log::warn("セット商品がありません。", true, true);
                array_push($data, []);
                continue;
            }

            $i = 0; $com_result = [];
            foreach ($res as $r) {  //選択行ごと 
                foreach ($r as $key => $val) {  //選択行の列ごと
                    $com_result[$i][$key] = $val;
                }
                $i++;
            }
            
            $cnt = count($com_result);
            array_push($data, $com_result);
            Log::info("セット商品取得件数 ***** {$cnt}件 *****", true);
            
            $all_cnt += $cnt;
            $i_com++;
        }
        Log::info("処理対象セット商品取得件数 ***** {$all_cnt}件 *****", true, true);
        $time = microtime(true) - $time_start1;
        Log::info("セット商品取得処理秒数 ***** {$time} 秒 *****", true, true);
        return $data;
    }

    
    /**
     * トークン取得
     */
    function get_company_list($id) {
        $AND = "";  //バッチ処理対象の全件取得時
        $WHERE ="";
        if (!empty($id)) {
            //$AND = "AND id='{$id}' "; //idで１件取得
            $WHERE = "WHERE id='{$id}' "; //idで１件取得
        }

        //zozo_flag １：有効、2：テスト
        //$Where = "WHERE zozo_flag = 2 {$AND}";
        $sql = "select * from ne_users {$WHERE}";
        
        global $link;
        $res = $link->query($sql);
        $res = $res->fetch_all(MYSQLI_ASSOC);
        
        if (!$link) {
            Log::error('データベース接続失敗', true, true);
            Log::error("errno: ".mysqli_connect_errno(), true, true);
            Log::error("error: ".mysqli_connect_error(), true, true);
            exit;
        } else if(count($res) == 0){
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

    /**
     * CSV出力
     */
    function csvExport($stock_all, $setItemList) {
        global $companyList,$fileName;
        $time_start3 = microtime(true);
        $i_com = 0;
        array_splice( $stock_all[1], 0, 0, 'shopNm');
        foreach ($companyList as $company){

            $s_header = $stock_all[1];
            $stock_rec = $stock_all[0][$i_com];
            Log::$c_id = $company["id"];

            $fn = "{$fileName}-{$company['id']}";
            //Log::info(" ***** ".json_encode($goods_rec), true);
            LOG::info(" **** {$fn}",true, true);
            $file = fopen("/home/ecmasuser/nextengine/batch/{$fn}.csv", "w");
                    array_splice($s_header, 2, 0, 'cs_stock_goods_id');
                    //array_splice($s_header, 2, 0, 'test_stock_goods_id'); //test
                    //fwrite($file, implode("\t", $s_header).PHP_EOL);
            
            $i_rec = 0;
            if ($company["zozo_stockRate"] == null
             || $company["zozo_stockRate"] == 0) {
                 Log::warn("**** {$company['id']}:{$company['company_name']} **** ZOZO在庫割合率を5%以上にしてください。割当率は100%で計算されます。", true, true);
                $company["zozo_stockRate"] = 100;
            }
            if ($stock_rec) {
                foreach($stock_rec as $s_data) {
                    if ($s_data["result"] == "success") {
                        $list = $s_data["data"];

                        //CSV仕様（タブ区切り、CRLF）
                        foreach ($list as $record) {
                            array_splice( $record, 0, 0, $company["shopNm"]);
                            $exp = explode("-", $record['stock_goods_id']);
                            //$foreign = $exp[0] == "set" ? "set" : "";
                            $str = ""; $str2 = "";
                            
                            /*if (!empty($foreign)) {
                                //セット商品　在庫取得で名の頭がsetのもの
                                $str .= $exp[1];
                                unset($exp[0]); unset($exp[1]);
                                $str2 = implode("-", $exp);
                            } else {*/
                                //通常商品
                                //1つめ"-"でセパレート
                                $str = $exp[0];
                                unset($exp[0]);
                                $str2 = implode("-", $exp);
                            //}
                            
                            if ($company["roundup"]) {
                                //切り上げ設定
                                $record['stock_free_quantity'] = 
                                    ceil($record['stock_free_quantity'] * (0.01 * $company["zozo_stockRate"]));
                            } else {
                                //切り下げ設定
                                $record['stock_free_quantity'] = 
                                    floor($record['stock_free_quantity'] * (0.01 * $company["zozo_stockRate"]));
                            }

                            $record['stock_goods_id'] = $str;
                            array_splice($record, 3);
                            array_splice($record, 2, 0, $str2);
                            fwrite($file, implode("\t", $record)."\r\n");   //（タブ区切り、CRLF）
                        }
                        $i_rec++;
                    }
                }
                //fwrite($file, "*** set");   // セット商品の区切り
                // セット商品
                foreach($setItemList[$i_com] as $setItem) {
                    if ($company["roundup"]) {
                        //切り上げ設定
                        $setItem['val'] = 
                            ceil($setItem['val'] * (0.01 * $company["zozo_stockRate"]));
                    } else {
                        //切り下げ設定
                        $setItem['val'] = 
                            floor($setItem['val'] * (0.01 * $company["zozo_stockRate"]));
                    }
                    array_splice($setItem, 0, 2, $company["shopNm"]);
                    array_splice($setItem, 2, 1);
                    fwrite($file, implode("\t", $setItem)."\r\n");   //（タブ区切り、CRLF）
                }
            }
            fclose($file);
            $i_com++;
            $time = microtime(true) - $time_start3;
            Log::info("処理秒数 ***** csvExport {$time} 秒 *****", true, true);
            //$g_idx++;
        }
        
    } //function
    
    /**
     * テスト用　アップロード
     */
    function upload() {
        global $fileName;
        $time_start = microtime(true);
        
        //初期化
        $glob = new CkGlobal();
        $success = $glob->UnlockBundle('ECMAST.CB1112021_dDfkA83p4eAL');
        if ($success != true) {
            Log::error($glob->lastErrorText(), true, true);
            exit;
        }
        
        #テスト
        $user_id = 'dexapi_pourvous';
        $password = '123456';
        
        $xmlCharset = 'utf-8';
        $xml = "<?xml version='1.0' encoding='{$xmlCharset}' ?><request><user_id>{$user_id}</user_id><password>{$password}</password></request>";
        //Log::info($xml ,true, true);
        
        #テスト
        $url = "https://dev99-apib.zozoclub.jp/cooperate/auth";
        //$goodsurl = "https://dev99-apib.zozoclub.jp/cooperate/RequestGoods";
        $stockurl ="https://dev99-apib.zozoclub.jp/cooperate/shopstock";
        
        $http = new CkHttp();
        $http->put_CookieDir('memory');
        $http->put_SaveCookies(true);
        $http->put_SendCookies(true);
        
        $resp = $http->PostXml($url,$xml,$xmlCharset);
        if ($http->get_LastMethodSuccess() != true) {
            //認証エラー
            Log::error("Auth認証エラー", true, true);
            Log::error($http->lastErrorText(), true, true);
            exit;
        } else {
            Log::info("Auth認証", true, true);
            Log::info($resp->bodyStr(), true, true);
            $req = new CkHttpRequest();
            $req->put_HttpVerb('POST');
            $pathToFileOnDisk = "{$fileName}-23.csv";

            //新 2回目：AddParamでファイルの内容を記載     成功版
            $req->AddParam('shopstock', file_get_contents($pathToFileOnDisk));

            $resp = $http->PostUrlEncoded($stockurl, $req);
            if ($http->get_LastMethodSuccess() != true) {
                Log::error($http->lastErrorText(), true, true);
                exit;
            }
            
            //元
                /*
                $req->put_Path('/cooperate/shopstock');
                $req->put_ContentType('multipart/form-data');
                $req->AddHeader('Connection','Keep-Alive');
                $req->AddHeader('Accept','text/html');

                $pathToFileOnDisk = './zozo_inventory-23.csv';
                $success = $req->AddFileForUpload('shopstock', $pathToFileOnDisk);
                if ($success != true) {
                    Log::error($req->lastErrorText(), true, true);
                    exit;
                }
                // resp is a CkHttpResponse
                $resp = $http->SynchronousRequest('dev99-apib.zozoclub.jp', 443, true, $req);
                if ($http->get_LastMethodSuccess() != true) {
                    Log::error($req->lastErrorText(), true, true);
                    exit;
                }
                */
            
            Log::info('HTTP response status: ' . $resp->get_StatusCode(), true, true);
            Log::info('Received:', true, true);
            Log::info($resp->bodyStr(), true, true);
            
            //$htmlStr = $resp->bodyStr();
            //print 'HTTP response status: ' . $resp->get_StatusCode() . "\n";
            //print 'Received:' . "\n";
            //print $htmlStr . "\n";
        }
        $time = microtime(true) - $time_start;
        //echo "処理秒数 ***** {$time} 秒 *****";
        Log::info("処理秒数 ***** {$time} 秒 *****", true, true);
    }


    /**
     * 有効フラグで対象になっているものをUpload（テスト）
     */
    function upload2() {
        $time_start = microtime(true);
        Log::info("**** upload **** ", true, true);

        //初期化
        $glob = new CkGlobal();
        $success = $glob->UnlockBundle('ECMAST.CB1112021_dDfkA83p4eAL');
        if ($success != true) {
            Log::error($glob->lastErrorText(), true, true);
            exit;
        }
        
        #テスト
        $url = "https://dev99-apib.zozoclub.jp/cooperate/auth";
        //$goodsurl = "https://dev99-apib.zozoclub.jp/cooperate/RequestGoods";
        $stockurl ="https://dev99-apib.zozoclub.jp/cooperate/shopstock";
        $xmlCharset = 'utf-8';
        

        global $companyList, $fileName;
        foreach($companyList as $company) {
            $time_start1 = microtime(true);
            Log::info("**** zozo_id: {$company['zozo_id']} **** ", true, true);
            Log::info("**** password: {$company['password']} **** ", true, true);
            upload_Backup($company['id']);
            if ($company['id'] <> 23 ){
                //プールブーさん以外、スキップ
                Log::info("**** skip id: {$company['id']} **** ", true, true);
                continue;
            }

            $xml = "<?xml version='1.0' encoding='{$xmlCharset}' ?><request><user_id>{$company['zozo_id']}</user_id><password>{$company['password']}</password></request>";
            //Log::info($xml ,true, true);
            
            $http = new CkHttp();
            $http->put_CookieDir('memory');
            $http->put_SaveCookies(true);
            $http->put_SendCookies(true);
            
            $resp = $http->PostXml($url,$xml,$xmlCharset);
            if ($http->get_LastMethodSuccess() != true) {
                //認証エラー
                Log::error("Auth認証エラー", true, true);
                Log::error($http->lastErrorText(), true, true);
                exit;
            } else {
                Log::info("Auth認証", true, true);
                Log::info($resp->bodyStr(), true, true);
                
                $req = new CkHttpRequest();
                $req->put_HttpVerb('POST');
                $req->put_Path('/cooperate/shopstock');
                $req->put_ContentType('multipart/form-data');
                $req->AddHeader('Connection','Keep-Alive');
                $req->AddHeader('Accept','text/html');
                $pathToFileOnDisk = "{$fileName}-{$company['id']}.csv";

                $req->AddParam('shopstock', file_get_contents($pathToFileOnDisk));
                $resp = $http->PostUrlEncoded($stockurl, $req);
                if ($http->get_LastMethodSuccess() != true) {
                    Log::error($http->lastErrorText(), true, true);
                    exit;
                }
                
                Log::info('HTTP response status: '. $resp->get_StatusCode(), true, true);
                Log::info('Received:'.PHP_EOL.$resp->bodyStr(), true, true);
            }
            //店舗ごとのアップロード時間出力
            $time = microtime(true) - $time_start1;
            Log::info("upload 店舗:{$company['id']}　処理秒数 ***** {$time} 秒 *****", true, true);
        }

        //全店舗アップロード時間出力
        $time = microtime(true) - $time_start;
        Log::info("upload 処理秒数 ***** {$time} 秒 *****", true, true);
    }

    function upload_Backup($id) {
        $folder = "/home/ecmasuser/nextengine/batch/";
        $copy_folder = "upload/shop-{$id}";
        $file = "zozo_inventory-{$id}";
        $dateStr_YMDHis = (new \DateTime)->format("Y-m-d H:i:s");
        if (!file_exists("{$folder}{$copy_folder}")) {
            mkdir("{$folder}{$copy_folder}", 0777);
        }
        if (copy("{$file}.csv", "{$copy_folder}/{$file}-{$dateStr_YMDHis}.csv")) {
            Log::info("copy成功", true, true);
        }
    }
    main();
?>