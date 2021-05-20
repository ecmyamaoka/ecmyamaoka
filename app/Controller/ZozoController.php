<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class Log {

	public static $c_id = "";
	const log_dir = "/home/ecmasuser/nextengine/app/tmp/logs/upd";
	/**
	 * ログを生成する
	 * @param string $message メッセージ
	 * @param boolean $echo　trueならecho出力
	 */
	public static function info($message, $echo = false) {
		self::write(" INFO ".$message.PHP_EOL, $echo);     //通常
	}
	public static function warn($message, $echo = false) {
		self::write(" WARN ".$message.PHP_EOL, $echo);     //警告
	}
	public static function error($message, $echo = false) {
		self::write(" ERROR ".$message.PHP_EOL, $echo);    //エラー
	}
	public static function tokenUpdate($token, $echo = false) {
		global $company;
		if (empty($token)) {
			$token = $company;
			unset($token['company_ne_id']);
		}
		$message = "";
		foreach ($token as $key => $val) {
			$message .= "{$key}: {$val}".PHP_EOL;
		}
		self::write_another(" TOKEN Update ".PHP_EOL.$message, $echo);    //token Update
	}
	private static function write($message, $echo) {
		$message = (new \DateTime)->format("Y-m-d H:i:s").$message;
		$id = self::$c_id;
		$dir = self::log_dir;
		if ($echo) echo $message;
		$file = fopen("{$dir}/mine-{$id}.log", "a");
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

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class ZozoController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
	private $company_index = "";
	private $folder = "/home/ecmasuser/nextengine/batch/upload";

	public function get_login_company()
	{
		App::import('Model','ConnectionManager');
		$db = ConnectionManager::getDataSource('mysqldb');

		$url = "https://api.next-engine.org/api_v1_login_company/info";
		
		$item = array();
		array_push($item,"company_ne_id");
		array_push($item,"company_name");
		
		//プールブーさんトークン
		$pourvous_token = [
			"42381220d4274e29649ffd0d749cb7eadf74755f0d199ffebb853fb3bf641fe7ece9f75bda31bc2d743480b66c3469dc653eb66968e758099fd1033300b6577f",
			"5a45254ad3771d36819f89c99f6145c75ca4372d3fa26eb89404e570ee5a51b33aeee38204a6e7a8c27c28e179c2b66ae7945328145cd25146e04bc19613edc5"
		];
		
		$post_data = array(
			'access_token' => $this->Session->read('access_token'),
			'refresh_token' => $this->Session->read('refresh_token'),
			//'access_token' => $pourvous_token[0],
			//'refresh_token' => $pourvous_token[1],
			'wait_flag' => '1',
			'fields' => implode(',', $item)
		);

		$ch = curl_init(); // はじめ
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html =  curl_exec($ch);
		$json = json_decode($html, true);
		$result = "";

		if(isset($json['data'])) {
			$company_ne_id = $json['data'][0]['company_ne_id'];

			//echo $company_ne_id;
			$sql = "select * from ne_users where company_ne_id='{$company_ne_id}'";
			$result = $db->query($sql);

			if(empty($result)){

				$sql='INSERT INTO ne_users (company_ne_id, company_name, access_token,refresh_token,Registed_At)  VALUES (:company_ne_id, :company_name, :access_token,:refresh_token,now())';
				$param = array('company_ne_id' => $company_ne_id,'company_name'=>$json['data'][0]['company_name'] ,'access_token' => $json['access_token'],'refresh_token' => $json['refresh_token']);
				$db->query($sql,$param);
				//this->company_index = $result[0]["ne_users"]["id"];
				$this->company_index = $db->lastInsertId();

			}else{

				$sql = "UPDATE ne_users set access_token='" . $json['access_token'] . "',refresh_token='" . $json['refresh_token'] . "',Registed_At='" . date("Y/m/d H:i:s") . "' where company_ne_id='" . $company_ne_id . "'";
				$db->query($sql);
				$this->company_index = $result[0]["ne_users"]["id"];

			}

			$this->Session->write("company_ne_id", $company_ne_id);
			$this->Session->write("company_index", $this->company_index);

			// $this->Session->write("company_ne_id", $company_ne_id);
			// $this->Session->write("company_index", $this->company_index);
			// Log::$c_id = mb_substr($company_ne_id, 0, 5)."-".$this->company_index;
			// Log::info("get login company");


			// $this->company_index = $result[0]["ne_users"]["id"];
			// if(count($result)==0){
			// 	$sql='INSERT INTO ne_users (company_ne_id, company_name, access_token,refresh_token,Registed_At)  VALUES (:company_ne_id, :company_name, :access_token,:refresh_token,now())';
			// 	$param = array('company_ne_id' => $company_ne_id,'company_name'=>$json['data'][0]['company_name'] ,'access_token' => $json['access_token'],'refresh_token' => $json['refresh_token']);
			// 	$db->query($sql,$param);
			// }else{
			// 	$sql = "UPDATE ne_users set access_token='" . $json['access_token'] . "',refresh_token='" . $json['refresh_token'] . "',Registed_At='" . date("Y/m/d H:i:s") . "' where company_ne_id='" . $company_ne_id . "'";
			// 	$db->query($sql);
			// }
			// $this->Session->write("company_ne_id", $company_ne_id);
			// $this->Session->write("company_index", $this->company_index);
			// Log::$c_id = mb_substr($company_ne_id, 0, 5)."-".$this->company_index;
			// Log::info("get login company");
		}
		return [$json, $result];
	}


	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public function get_zozoSetItem($AND = "")
	{
		App::import('Model','ConnectionManager');
		$db = ConnectionManager::getDataSource('mysqldb');

		if(isset($this->company_index)) {
			if (!empty($AND)) $AND = "and set_goods_id='{$AND}'";
			$sql = "select * from zozo_setitem_import where shop_id='{$this->company_index}' {$AND}";
			$result = $db->query($sql);
		}
		return array_column($result, "zozo_setitem_import");
	}

	/**
	 * 在庫情報取得
	 */
	public function get_master_stock($datetime)
	{
		$url = "https://api.next-engine.org/api_v1_master_stock/search";
		
		$item = array();
		array_push($item,"stock_goods_id");
		array_push($item,"stock_quantity");
		array_push($item,"stock_last_modified_date");
		
		$post_data = array(
			'access_token' => $this->Session->read('access_token'),
			'refresh_token' => $this->Session->read('refresh_token'),
			'wait_flag' => '1',
			'stock_last_modified_date-gte' => $datetime->format("Y-m-d H:i:s"),
			'limit' => 1000,
			'fields' => implode(',', $item)
		);
		//echo $this->console_log($post_data);

		$ch = curl_init(); // はじめ
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		//「項目１〜」というところは変更で「goods_foreign_name」
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html =  curl_exec($ch);
		$json = json_decode($html, true);

		return $json;
	}

 	/**
     * JavaScriptコンソールログ出力
     */
	function console_log( $data ) {
		echo "<script> console.log(".json_encode($data).");</script>";
	}
	
	/**
	 * ZOZO設定の更新実行
	 */
	public function setZozoData($setData) {
		$company_ne_id = $this->Session->read('company_ne_id');

		$db = ConnectionManager::getDataSource('mysqldb');
		$sql = "select * from ne_users where company_ne_id='{$company_ne_id}'";
		$result = $db->query($sql);
		$this->company_index = $result[0]["ne_users"]["id"];
		
		if(count($result) == 0){
			$this->set('zozoUp_comment', "データが登録されていません");
			
		} else {
			$upd = array();
			$setData["password"] = base64_encode($setData["password"]);
			$calcCnt = $setData["calcCnt"] - 0;
			unset($setData["calcCnt"]);

			$calcSet = "";
			$sign =["+","-","*","/"];
			$len = $calcCnt;
			for($i = 0; $i < $len; $i++) {
				if (empty($setData["calcItem_{$i}"])) {
					//計算項目の"指定なし"分の件数を減算
					$calcCnt--;
				} else {
					//$calcSet .=  $sign[$setData["sign_".$i]].":".$setData["calcItem_".$i].",";
					$calcSet .= "{$sign[$setData['sign_'.$i]]}:{$setData['calcItem_'.$i]},";
				}
				unset($setData["sign_{$i}"]);
				unset($setData["calcItem_{$i}"]);
			}

			if ($calcCnt > 0) {
				array_push($upd, "calcSet='".substr($calcSet, 0, -1)."'");
			}
			foreach ($setData as $key => $val) {
				if ($val <> "") array_push($upd, "{$key}='{$val}'");
			}
			$setData["calcCnt"] = $calcCnt;
			$setData["calcSet"] = $calcSet;

			$update = implode(",", $upd);
			
			$sql = "UPDATE ne_users set {$update}
			 where company_ne_id='{$company_ne_id}'";
			$db->query($sql);
			
			return $setData;
		}
	}

	/**
    *　ZOZO更新時の入力チェック
    */
    public function zozoSetValidate($data){
		
		$message = "";
		$zozoStockRate_notEmpty = !empty($data["zozo_stockRate"]);
		$zozoid_notEmpty = !empty($data["zozo_id"]);
		$zozoFlag_on = !empty($data["zozo_flag"]);
		$shopNm_notEmpty = !empty($data["shopNm"]);
		
		if ($zozoFlag_on) {
			if (!$zozoid_notEmpty || !$shopNm_notEmpty || !$zozoStockRate_notEmpty) {
				$message .= "・ZOZO有効フラグを有効にする場合はShop Number、ZOZO ID、ZOZO在庫割当率を入力してください。<br>";
			}
			if ($zozoStockRate_notEmpty) {
				if (!preg_match('/^([5-9]|[1-9]\d|100)$/', $data["zozo_stockRate"]))
					$message .= "・ZOZO在庫割当率は5〜100の範囲で入力してください。<br>";
			}

			//計算式に関するチェック
			$calcCnt = $data["calcCnt"] - 0;
			$len = $calcCnt;
			$vals = [];
			for($i = 0; $i < $len; $i++) {
				if (empty($data["calcItem_{$i}"])) {
					//計算項目の"指定なし"分の件数を減算
					$calcCnt--;
					unset($data["sign_{$i}"]);
					unset($data["calcItem_{$i}"]);
				} else {
					array_push($vals, $data["calcItem_{$i}"]);
				}
			}
			if ($calcCnt == 0) {
				$message .= "・計算式が指定されていません。１つは指定してください。<br>";
			} else {
				$count = array_count_values($vals);
				if (max($count) > 1) $message .= "・計算式に同じ項目は指定できません。<br>";
			}
		}
		
        return $message;
	}
	/**
    *　ZOZOセット商品追加時の入力チェック
    */
    public function zozoSetItemValidate($input, $data){
		
		$message = "";
		//$zozoItem_notEmpty = !empty($input["item"]); //削除時のセット商品コード
		$in_zozoItem_notEmpty = !empty($input["in_item"]);

		if ($in_zozoItem_notEmpty) {
			if ($data[0]["set_goods_id"] == $input["in_item"]){
				$message .= "・すでに同じセット商品IDが存在しています。<br>";
			}
		} else {
			$message .= "・セット商品コードが入力されていません。<br>";
		}
		
        return $message;
	}

	/**
	* Displays a view
	* セット商品一覧表示
	*
	* @return CakeResponse|null
	* @throws ForbiddenException When a directory traversal attempt.
	* @throws NotFoundException When the view file could not be found
	*   or MissingViewException in debug mode
	*/
	public function setItems() {
		App::import('Model','ConnectionManager');
		$result = $this->get_login_company();
		$user = $result[0];
		//DBから値を読み込めない際の初期値
		$this->set("zozoUp_comment", "");
		
		$this->console_log($this->request);
		$this->console_log($user);
		//$this->console_log($this->Zozo);
		//$setItems = [];
		if (isset($user['data'])) {
			$setItems = $this->get_zozoSetItem();
			$this->setItems = $setItems;
			$this->set('company_name', $user['data'][0]['company_name']);
			
			if(!empty($setItems)) {
				$this->set("updItemData", $setItems);
			}
			//更新後の表示
			if ($this->Session->check("updItemData")) {
				$setData = $this->Session->read("updItemData");
				$this->Session->delete("updItemData"); //更新済みのデータ削除
	
				//$this->console_log("set ".json_encode($setData));
				$this->set("zozoUp_comment", $setData['message']);
			}

		} else {
			$view_error = array(
				"company_name" => "企業名不明",
				"up_comment" => "ファイルがアップロードできません。",
				"disabled" => "disabled"
			);
			$this->set($view_error);
		}

	}

	/**
	* Displays a view
	*
	* @return CakeResponse|null
	* @throws ForbiddenException When a directory traversal attempt.
	* @throws NotFoundException When the view file could not be found
	*   or MissingViewException in debug mode
	*/
	public function show_log() {
		$this->layout = "";
		$this->autoRender = false;
		
		$id = $this->Session->read("company_index");
		$fileName = $_POST['fileName'];

		$openFile = new File("{$this->folder}/shop-{$id}/{$fileName}");
		$openFile = preg_replace("/\r\n/", "<br>", $openFile->read());
		echo $openFile;
	}
	
	/**
	* Displays a view
	*
	*/
	public function submit() {

		App::import('Model','ConnectionManager');
		$this->layout = "";
		$this->autoRender = false;

		$setData = $this->data;
		//$this->console_log($setData);
		$message = $this->zozoSetValidate($setData);
		if (!empty($message)) {
			//チェックエラー時は更新しない
			$setData["message"] = $message;
		} else {
			//zozo設定の更新
			$setData = $this->setZozoData($setData);
			$setData["message"] = "更新しました。";
		}
		$this->Session->write("updData", $setData);
		$this->redirect('https://nextengine.ec-masters.net/zozo');
	}

	/**
	* セット商品の登録処理
	*/
	public function setItem_insert() {
		App::import('Model','ConnectionManager');
		$this->layout = "";
		$this->autoRender = false;
		$this->company_index = $this->Session->read("company_index");
		
		//zozoセット商品の追加
		$setData = $this->data;
		$result = $this->get_zozoSetItem($setData['in_item']);
		$message = $this->zozoSetItemValidate($setData, $result);
		if (!empty($message)) {
			//チェックエラー時は実行しない
			$setData["message"] = $message;
		} else {

			$db = ConnectionManager::getDataSource('mysqldb');
			if(count($result) == 0){
				$sql = "INSERT INTO zozo_setitem_import(shop_id, set_goods_id, brand_id, zozo_goods_id, cs_goods_id)
				VALUES ('{$this->company_index}', '{$setData['in_item']}', '{$setData['in_brand']}', '{$setData['in_zozoid']}', '{$setData['in_csgoods']}')";
				$db->query($sql);
			}
			$setData["message"] = "追加しました。";
			$db->close();
		}
		$this->Session->write("updItemData", $setData);
		//$this->redirect('https://nextengine.ec-masters.net/zozo/setItems');
	}

	/**
	* セット商品の削除処理
	*/
	public function setItem_delete() {

		App::import('Model','ConnectionManager');
		$this->layout = "";
		$this->autoRender = false;
		$this->company_index = $this->Session->read("company_index");
		
		//zozoセット商品の削除
		$setData = $this->data;
		$result = $this->get_zozoSetItem($setData['item']);
		$message = $this->zozoSetItemValidate($setData, $result);
		if (!empty($message)) {
			//チェックエラー時は実行しない　削除時は現在チェックなし
			$setData["message"] = $message;
		} else {

			$db = ConnectionManager::getDataSource('mysqldb');
			if(count($result) > 0){
				$sql = "DELETE FROM zozo_setitem_import WHERE shop_id='{$this->company_index}' AND set_goods_id='{$setData['item']}'";
				//実行はコメントアウト
				//$db->query($sql);
			}
			$setData["message"] = "削除しました。";
			$db->close();
		}
		$this->Session->write("updItemData", $setData);
		//$this->redirect('https://nextengine.ec-masters.net/zozo/setItems');
	}
	
	/**
	 * Displays a view
	 *
	 * @return CakeResponse|null
	 * @throws ForbiddenException When a directory traversal attempt.
	 * @throws NotFoundException When the view file could not be found
	 *   or MissingViewException in debug mode.
	 */
	public function index() {
		App::import('Model','ConnectionManager');

		$result = $this->get_login_company();
		$user = $result[0];
		//DBから値を読み込めない際の初期値
		$view_default = array(
				"up_comment"=>"",
				"zozoUp_comment"=>"",
				"disabled"=>"",
				"shopNm"=>"",
				"zozo_id"=>"",
				"zozo_flag"=>"0",
				"zozo_stockRate"=>"",
				"roundup"=>"1",
				"show"=> false,
				"asuraku_clear_flag"=>"0",
				"calcSet"=> ["sign_0"=> "0", "calcItem_0" => "stock_free_quantity"]
			);
		
		$this->set($view_default);
		$this->console_log($this->request);
		$this->console_log($user);
		//$this->console_log($this->Zozo);
		
		if (isset($user['data'])) {
			$this->set('company_name', $user['data'][0]['company_name']);
			
			if(!empty($result[1])) {
				$_user = $result[1][0]["ne_users"];
				//DBの内容を表示
				foreach (["shopNm", "zozo_id", "zozo_flag", "zozo_stockRate","roundup", "asuraku_clear_flag"] as $key) {
					$view_default[$key] = $_user[$key];
				}

				$_calcSet = explode(",",$_user['calcSet']);
				$_set_i = 0;
				$view_calcSet = [];
				if (!empty($_calcSet[0])) {
					$sign =["+"=>"0", "-"=>"1", "*"=>"2", "/"=>"3"];	// 「*」&「/」 は現在不要
					foreach ($_calcSet as $set) {
						$exp = explode(":",$set);
						array_push($view_calcSet, ["sign_".$_set_i => $sign[$exp[0]], "calcItem_".$_set_i => $exp[1]]);
						$_set_i++;
					}
				}
				$this->console_log($view_calcSet);
				$view_default['calcSet'] = $view_calcSet;
				$this->set($view_default);

				//ファイルリスト
				$dir = new Folder("{$this->folder}/shop-{$_user['id']}");
    			$filelist = $dir->find('.*\.csv', true);
				krsort($filelist); //新しいものから降順

				$_i = 0;
				$uploadList = [];
				foreach($filelist as $line) {
					$str = explode("-{$_user['id']}-", $line);
					$dateStr = pathinfo($str[1])["filename"];
					array_push($uploadList,["date" => $dateStr, "file" => $line]);
					$_i++;
				};
				$this->set('uploadFiles', $uploadList);
			}
			
			//更新後の表示
			if ($this->Session->check("updData")) {
				$setData = $this->Session->read("updData");
				$this->Session->delete("updData"); //更新済みのデータ削除

				//$this->console_log("set ".json_encode($setData));
				$view_default['calcSet'] = $view_calcSet;
				$view_default["show"] = true;
				$view_default["zozoUp_comment"] = $setData["message"];
				$this->set($view_default);
			}
			
		} else {
			$view_error = array(
				"company_name" => "企業名不明",
				"up_comment" => "ファイルがアップロードできません。",
				"disabled" => "disabled"
			);
			$this->set($view_error);
		}

		$date = new DateTime('now');
		$datetime = $date->modify("-10 minute");

		$this->set('datetime',$datetime);
		$res = $this->get_master_stock($datetime);
		
		if (isset($res['data']) &&
			count($res['data']) > 0) {
			foreach ((array) $res['data'] as $key => $value) {
				$sort[$key] = $value['stock_last_modified_date'];
			}
			array_multisort($sort, SORT_DESC, $res['data']);
			$this->set('result', $res['data']);
		}else{
			$this->set('result', null);
		}

		//コメント
			//echo json_encode($this->get_master_stock());

			// $memcache = new Memcached();
			// $memcache->addServer('localhost', 11211);
			// $dt = $memcache->get($_COOKIE['PHPSESSID']);

			//var_dump($dt);

			// Dim url As String = "https://tool.ec-masters.net/lidp/rakuten.new.php"
			// url &= "?hash=" & "671a2f141c9052aa0c232d4425c5aa0eb02972ba"
			// url &= DateDiff("s", "1970/01/01 00:00:00", DateTime.Now) - 32400

			// $base_url = 'https://tool.ec-masters.net/lidp/rakuten.new.php';
			// $query = ['hash'=>$dt['servicehash'] . strtotime('now')];

			// $response = file_get_contents(
			// 	$base_url . '?' .
			// 	http_build_query($query)
			// );
			// $xml = simplexml_load_string($response);
			// $json = json_encode($xml);
			// $array = json_decode($json,TRUE);
			//var_dump($array);

			// $db = ConnectionManager::getDataSource('tagmng');
			// $sql2 ="select * from tagdata;";
			// $result = $db->query($sql2);//,$params
			//var_dump($result);




			//var_dump($dt);

			// //ftpアカウント作成
			// $this->set('user', $dt);

			// $dir = " /home/" . $dt['loginidstr'];
			// if (is_dir($dir)) {
			// 	if ($dh = opendir($dir)) {
			// 		while (($file = readdir($dh)) !== false) {
			// 			echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
			// 		}
			// 		closedir($dh);
			// 	}
			// }else{
			// 	echo 'アカウントがありません<br>';

			// 	$pass = shell_exec('pwgen 10 1');
			// 	echo $pass . '<br>';
			// 	echo shell_exec('sudo adduser --disabled-password --gecos "" ' . $dt['loginidstr']);
			// 	echo shell_exec('sudo echo "' . $dt['loginidstr'] . ':' . $pass . '" | sudo chpasswd');

			// 	exec('sudo mkdir -m 777 /home/' . $dt['loginidstr'] . '/rakuten/');
			// 	exec('sudo mkdir -m 777 /home/' . $dt['loginidstr'] . '/rakutengold/');
			// 	exec('sudo mkdir -m 777 /home/' . $dt['loginidstr'] . '/rakuten/ritem/');
			// 	exec('sudo mkdir -m 777 /home/' . $dt['loginidstr'] . '/rakuten/ritem/batch/');

			// }


			// $path = func_get_args();

			// $count = count($path);
			// if (!$count) {
			// 	return $this->redirect('/');
			// }
			// if (in_array('..', $path, true) || in_array('.', $path, true)) {
			// 	throw new ForbiddenException();
			// }
			// $page = $subpage = $title_for_layout = null;

			// if (!empty($path[0])) {
			// 	$page = $path[0];
			// }
			// if (!empty($path[1])) {
			// 	$subpage = $path[1];
			// }
			// if (!empty($path[$count - 1])) {
			// 	$title_for_layout = Inflector::humanize($path[$count - 1]);
			// }
			// $this->set(compact('page', 'subpage', 'title_for_layout'));

			// try {
			// 	$this->render(implode('/', $path));
			// } catch (MissingViewException $e) {
			// 	if (Configure::read('debug')) {
			// 		throw $e;
			// 	}
			// 	throw new NotFoundException();
			// }

		
	}
}
