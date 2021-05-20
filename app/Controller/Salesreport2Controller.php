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
App::import('Vendor', 'util/Nslog');


/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class Salesreport2Controller extends AppController
{


	public $uses = array();

	public function setSession($json){

		if(isset($json['access_token'])){
			$this->Session->write('access_token',$json['access_token']);
			$this->Session->write('refresh_token', $json['refresh_token']);
		}

	}

	public function error() {

	}

	public function index()
	{
		$this->layout = "";
		$this->autoRender = false;


		//echo phpinfo();

	}

	public function devtoken() {

		$this->layout = "";
		$this->autoRender = false;
		$state = $_GET["state"];
		$uid = $_GET["uid"];

		$url = "https://api.next-engine.org/api_neauth";
		$post_data = array(
			'uid' => $uid,
			'state' => $state,
			'client_id' => 'fWoEbQxHe4RPND',
			'client_secret' => '35wnpQHkcIAPoLhOlRM9WmdVZCN6xtBX7TDb18Jj'
		);

		$ch = curl_init(); // はじめ
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html =  curl_exec($ch);

		if(isset($html)){

			$json = json_decode($html, true);
			$this->Session->write('access_token',$json['access_token']);
			$this->Session->write('company_app_header',$json['company_app_header']);
			$this->Session->write('company_ne_id',$json['company_ne_id']);
			$this->Session->write('company_name', $json['company_name']);
			$this->Session->write('company_kana', $json['company_kana']);
			$this->Session->write('uid', $json['uid']);
			$this->Session->write('pic_ne_id', $json['pic_ne_id']);
			$this->Session->write('pic_name', $json['pic_name']);
			$this->Session->write('pic_kana', $json['pic_kana']);
			$this->Session->write('pic_mail_address', $json['pic_mail_address']);
			$this->Session->write('refresh_token', $json['refresh_token']);
			$this->Session->write('result', $json['result']);

			echo $this->Session->read('access_token');
			$this->redirect('report');
		}
	}

	public function token() {
		
		$this->layout = "";
		$this->autoRender = false;
		$state = $_GET["state"];
		$uid = $_GET["uid"];

		$url = "https://api.next-engine.org/api_neauth";
		$post_data = array(
			'uid' => $uid,
			'state' => $state,
			'client_id' => 'PvS8QFWHTcIMeL',
			'client_secret' => '4dmXbeBqyQ81KgiMhwDY5LpAFjSfnHTctUIkaN9J'
		);

		$ch = curl_init(); // はじめ
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html =  curl_exec($ch);

		if(isset($html)){

			$json = json_decode($html, true);
			$this->Session->write('access_token',$json['access_token']);
			$this->Session->write('company_app_header',$json['company_app_header']);
			$this->Session->write('company_ne_id',$json['company_ne_id']);
			$this->Session->write('company_name', $json['company_name']);
			$this->Session->write('company_kana', $json['company_kana']);
			$this->Session->write('uid', $json['uid']);
			$this->Session->write('pic_ne_id', $json['pic_ne_id']);
			$this->Session->write('pic_name', $json['pic_name']);
			$this->Session->write('pic_kana', $json['pic_kana']);
			$this->Session->write('pic_mail_address', $json['pic_mail_address']);
			$this->Session->write('refresh_token', $json['refresh_token']);
			$this->Session->write('result', $json['result']);

			echo $this->Session->read('access_token');
			$this->redirect('report');
		}
	}



	public function get_master_shop_search()
	{

		$url = "https://api.next-engine.org/api_v1_master_shop/search";
		
		$item = array();
		array_push($item,"shop_id");//店舗ID
		array_push($item,"shop_name");//店舗名

		$post_data = array(
			'access_token' => $this->Session->read('access_token'),
			'refresh_token' => $this->Session->read('refresh_token'),
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

		if($json['result']!='error'){
			$this->setSession($json);
		}

		return $json;

	}

	public function csvwrite($ddata){

		$rand_str = chr(mt_rand(65,90)) . chr(mt_rand(65,90)) . chr(mt_rand(65,90)) .
            chr(mt_rand(65,90)) . chr(mt_rand(65,90)) . chr(mt_rand(65,90));
		$timestamp = time();
		$fname = strtolower($rand_str) . $timestamp . ".csv";

		$f = fopen("/home/ecmasuser/nextengine/csvdownload/" . $fname , "w");
		$csv = "商品コード,商品コード,セット,売価,原価,受注数量,受注金額" . PHP_EOL;
		fputs($f, $csv);
		if(isset($ddata)){
			foreach($ddata as $dt) {
				fputcsv($f, $dt);
			}
		}
		fclose($f);
		return $fname;
	}

	public function totalization2($json){

		$items = array();
		//['data']
		foreach ($json as &$value) {
			//echo $value['goods_id'] . ' ';
			if($value['receive_order_row_goods_name'] == $value['goods_name']){
				//同一商品名は単品
				$result = in_array($value['goods_id'], $items);
				if($result == false){
					$items[] = $value['goods_id'];
				}
				//echo '1' . PHP_EOL . '<br>';
			}else{
				//セット商品かな？
				$itemcode = $value['receive_order_row_goods_name'];
				$pieces = explode(" ", $itemcode);

                if (preg_match("/^[a-zA-Z0-9_-]+$/", $pieces[0])) {
					//英数字ハイフン
					$result = in_array($pieces[0], $items);
					$value['receive_order_row_goods_id'] = $pieces[0];
					if($result == false){
						$items[] = $pieces[0];
					}
					//echo '2' . PHP_EOL . '<br>';
				}else{

					$result = in_array($value['goods_id'], $items);
					if($result == false){
						$items[] = $value['goods_id'];
					}
					//echo '3' . PHP_EOL . '<br>';
				}
			}
		}

		$fname = '/home/ecmasuser/nextengine/orderdownload/v3_' . time() . '.json';

		file_put_contents($fname,json_encode($items));

		// $file = fopen($fname , json_encode($items));
		// if( $file ){
		// 	// foreach($json as $dt) {
		// 	// 	fputcsv($file, $dt);
		// 	// }
		// 	//fputcsv($file, $items);
		// }
		// fclose($file);



		$res = array();
	
		foreach ($items as $item) {
			$goods_id = $item;
			$goods_name ='';
			$unit_price = 0;
			$first_cost = 0;
			$quantity = 0;
			$total = 0;
			//['data']
			foreach ($json as &$value) {

				if($item == $value['receive_order_row_goods_id']){

					if($value['receive_order_row_unit_price']>0){
						$unit_price = $value['receive_order_row_unit_price'];
					}

					$qu = $value['receive_order_row_quantity'];

					if($value['receive_order_row_goods_name'] == $value['goods_name']){
						if($value['receive_order_row_unit_price']>0){
							$goods_name = $value['goods_name'];
						}
					}else{
						if($value['receive_order_row_unit_price']>=0){
							$goods_name = $value['receive_order_row_goods_name'];
						}
					}
					//$first_cost += $qu * $value['receive_order_row_received_time_first_cost'];
					$first_cost = $value['receive_order_row_received_time_first_cost'];
					
					if($value['receive_order_row_unit_price']>0){
						$quantity += $qu;
						$total +=  $unit_price * $qu;
					}
				}
			}
			$calc = array();
			$calc = array_merge($calc,array('goods_id'=>$goods_id));
			$calc = array_merge($calc,array('goods_name'=>$goods_name));
			$calc = array_merge($calc,array('unit_price'=>$unit_price));
			$calc = array_merge($calc,array('first_cost'=>$first_cost));
			$calc = array_merge($calc,array('quantity'=>$quantity));
			$calc = array_merge($calc,array('total'=>$total));

			array_push($res,$calc);

		}



		
		return $res;
	}

	public function is_in_array($array, $key, $key_value){
		$within_array = false;
		foreach( $array as $k=>$v ){
		  if( is_array($v) ){
			  $within_array = $this->is_in_array($v, $key, $key_value);
			  if( $within_array == true ){
				  break;
			  }
		  } else {
				  if( $v == $key_value && $k == $key ){
						  $within_array = true;
						  break;
				  }
		  }
		}
		return $within_array;
	}




	public function totalization3($json){

		//商品マスター
		//商品マスター
		$itemMaster = array();
		$items = array();

		$orderno = array();

		set_time_limit(120);

		//受注番号
        // foreach ($json as $value) {
		// 	$result = in_array($value['receive_order_id'], $orderno);
		// 	if($result == false){
		// 		$orderno[] = $value['receive_order_id'];
		// 	}
		// }


	
		//セット商品のコード代入
		foreach ($json as &$value) {

			// if($value['receive_order_row_goods_id']=='usb-002-brown'){
			// 	echo json_encode($value);
			// 	exit();
			// }

			if($value['receive_order_row_goods_name'] == $value['goods_name']){
				//同一商品名は単品は何もしない
				//$itemMaster[] = $value['receive_order_row_goods_id'];
				$value = array_merge($value,array('set_good_id' => $value['receive_order_row_goods_id']));
				
				$result = $this->is_in_array($itemMaster,'goods_id',$value['receive_order_row_goods_id']);
				if($result == false){
					$itemMaster[] = array("goods_id" => $value['receive_order_row_goods_id'],
					"setflg" => false,
					"order_id" => $value['receive_order_id'],
					"price" => $value['goods_selling_price'],
					"unitprice" => $value['receive_order_row_unit_price'],
					"unit" => $value['receive_order_row_quantity'],
					"cost" => $value['goods_cost_price']);
				}

			}else{
				//セット商品かな？
				$itemcode = $value['receive_order_row_goods_name'];
				$pieces = explode(" ", $itemcode);
                if (preg_match("/^[a-zA-Z0-9_-]+$/", $pieces[0])) {

					$value = array_merge($value,array('set_good_id' => $pieces[0]));
					$result = $this->is_in_array($itemMaster,'goods_id',$pieces[0]);
					if($result == false){
						$itemMaster[] = array("goods_id" => $pieces[0],
						"setflg" => true,
						"order_id" => $value['receive_order_id'],
						"price" => $value['goods_selling_price'],
						"unitprice" => $value['receive_order_row_unit_price'],
						"unit" => $value['receive_order_row_quantity'],
						"cost" => $value['goods_cost_price']);
					}
				}else{
					$value = array_merge($value,array('set_good_id' => $value['goods_id']));

					$result = $this->is_in_array($itemMaster,'goods_id',$value['goods_id']);
					if($result == false){
						$itemMaster[] = array("goods_id" => $value['goods_id'],
						"setflg" => false,
						"order_id" => $value['receive_order_id'],
						"price" => $value['goods_selling_price'],
						"unitprice" => $value['receive_order_row_unit_price'],
						"unit" => $value['receive_order_row_quantity'],
						"cost" => $value['goods_cost_price']);
					}
				}
			}
		}


		//セット商品コスト加算
		foreach ($itemMaster as &$gid) {
			if($gid['setflg'] == true){
				$cost = 0;
				$price = 0;
				$unitprice = 0;

				$setcheck = array();
				foreach ($json as &$value) {
					if($value['receive_order_id'] == $gid['order_id']){
						if($value['set_good_id'] == $gid['goods_id']){
							$result = in_array($value['goods_id'], $setcheck);
							if($result == false){
								$cost += ($value['goods_cost_price'] * $value['receive_order_row_quantity']);
								$price += $value['goods_selling_price'];
								$unitprice += $value['receive_order_row_unit_price'];
								$setcheck[] = $value['goods_id'];
								$value['receive_order_row_quantity'] = $value['receive_order_row_quantity']/$gid['unit'];
							}
						}
					}
				}
				$gid['cost'] = $cost;
				$gid['price'] = $price;
				$gid['unitprice'] = $unitprice;
			}
		}

		// echo json_encode($itemMaster);
		// exit();


		// foreach ($json as &$value) {
		// 	echo json_encode($value);
		// 	exit();
		// }






		// foreach ($json as &$value) {

		// 	if($value['receive_order_row_goods_name'] == $value['goods_name']){
		// 		//同一商品名は単品は何もしない
		// 		$value['set_good_id']='noset';
		// 	}else{
		// 		//セット商品かな？
		// 		$itemcode = $value['receive_order_row_goods_name'];
		// 		$pieces = explode(" ", $itemcode);
        //         if (preg_match("/^[a-zA-Z0-9_-]+$/", $pieces[0])) {
		// 			$result = in_array($pieces[0], $items);
		// 			$value['set_good_id']=$pieces[0];
		// 		}
		// 	}
		// }

		



		// exit();


 
		


		// echo json_encode($itemMaster);
		// exit();




		// //販売情報から商品一覧を作成
		// $tmp = array();
		// $itemMaster = array();
		// foreach ($json as $station){
		// 	if (!in_array($station['goods_id'], $tmp)) {
				
		// 		//if(strpos($station['receive_order_row_goods_name'],'set-parker-imct-navy')!== false){
		// 			$tmp[] = $station['goods_id'];
		// 			$uniqueStations[] = $station;
		// 		//}
		// 	}
		// }









		

		// foreach ($uniqueStations as &$value) {
		// 	//echo $value['goods_id'] . ' ';
		// 	if($value['receive_order_row_goods_name'] == $value['goods_name']){
		// 		//同一商品名は単品
		// 		$result = $this->is_in_array($items,'goods_id',$value['goods_id']);
		// 		if($result == false){
		// 			$array = array('goods_id'=>$value['goods_id'], 'cost'=>$value['receive_order_row_received_time_first_cost']);
		// 			$items[] = $array;
		// 		}
		// 		//echo '1' . PHP_EOL . '<br>';
		// 	}else{
		// 		//セット商品かな？
		// 		$itemcode = $value['receive_order_row_goods_name'];
		// 		$pieces = explode(" ", $itemcode);

		// 		if (preg_match("/^[a-zA-Z0-9_-]+$/", $pieces[0])) {
		// 			//英数字ハイフン
		// 			//$result = in_array($pieces[0], $items);
					
		// 			$result = $this->is_in_array($items,'goods_id',$pieces[0]);

		// 			$value['receive_order_row_goods_id'] = $pieces[0];
		// 			if($result == false){
		// 				//$items[] = $pieces[0];
		// 				$array = array('goods_id'=>$pieces[0], 'cost'=>0);
		// 				$items[] = $array;
		// 			}
		// 			echo $pieces[0] . ':' . $value['receive_order_row_received_time_first_cost'];
		// 			echo '<br>';

		// 			//コストを加算
		// 			foreach ($items as &$value2) {
		// 				if($value2['goods_id'] == $pieces[0]){
		// 					$value2['cost'] += $value['receive_order_row_received_time_first_cost'];
		// 				}
		// 			}
					
		// 			//echo '2' . PHP_EOL . '<br>';
		// 		}else{
		// 			$result = $this->is_in_array($items,'goods_id',$value['goods_id']);
		// 			//$result = in_array($value['goods_id'], $items);
		// 			if($result == false){
		// 				//$items[] = $value['goods_id'];
		// 				$array = array('goods_id'=>$value['goods_id'], 'cost'=>$value['receive_order_row_received_time_first_cost']);
		// 				$items[] = $array;
		// 			}
		// 			//echo '3' . PHP_EOL . '<br>';
		// 		}
		// 	}
		// }

		// exit();

		


		// $fname = '/home/ecmasuser/nextengine/orderdownload/v3_' . time() . '.json';

		// file_put_contents($fname,json_encode($itemMaster));

		// echo json_encode($itemMaster);

		// exit();

		//echo json_encode($items);
		
		$res = array();
	
		foreach ($itemMaster as $item) {
			$goods_id = $item['goods_id'];
			$goods_name ='';
			$unit_price = 0;
			$price = 0;
			$first_cost = 0;
			$quantity = 0;
			$total = 0;
			$totalprice=0;
			$setflg = $item['setflg'];
			//['data']
			foreach ($json as &$value) {

				if($item['goods_id'] == $value['set_good_id']){
					$qu = $value['receive_order_row_quantity'];
					$subtotal = $value['receive_order_row_sub_total_price'];


					if($value['receive_order_row_unit_price']>0){
						//$unit_price = $value['receive_order_row_unit_price']/$qu;
						$unit_price = $item['unitprice'];
						$price = $item['price'];

					}

					if($value['receive_order_row_goods_name'] == $value['goods_name']){
						if($value['receive_order_row_unit_price']>0){
							$goods_name = $value['goods_name'];
						}
					}else{
						if($value['receive_order_row_unit_price']>=0){
							$goods_name = $value['receive_order_row_goods_name'];
						}
					}
					//$first_cost += $qu * $value['receive_order_row_received_time_first_cost'];
					//$first_cost = $value['receive_order_row_received_time_first_cost'];
					$first_cost = $item['cost'];
					if($value['receive_order_row_unit_price']>0){

						$quantity += $qu;
						$total += $subtotal;
					}
				}
			}
			$calc = array();
			$calc = array_merge($calc,array('goods_id'=>$goods_id));
			$calc = array_merge($calc,array('goods_name'=>$goods_name));
			$calc = array_merge($calc,array('setflg'=>$item['setflg'] ));
			if($item['setflg'] == true){
				$calc = array_merge($calc,array('unit_price'=>$unit_price));
			}else{
				$calc = array_merge($calc,array('unit_price'=>$price));
			}
			$calc = array_merge($calc,array('first_cost'=>$first_cost));
			$calc = array_merge($calc,array('quantity'=>$quantity));
			$calc = array_merge($calc,array('total'=>$total));

			array_push($res,$calc);

		}

		return $res;
	}


	public function totalization($json){

		$items = array();
		//['data']
		foreach ($json as &$value) {
			if($value['receive_order_row_goods_name'] == $value['goods_name']){
				//同一商品名は単品
				$result = in_array($value['goods_id'], $items);
				if($result == false){
					$items[] = $value['goods_id'];
				}
			}else{
				//セット商品
				$itemcode = $value['receive_order_row_goods_name'];
				$pieces = explode(" ", $itemcode);
				$result = in_array($pieces[0], $items);
				$value['receive_order_row_goods_id'] = $pieces[0];
				if($result == false){
					$items[] = $pieces[0];
				}
			}
		}

		$res = array();
	
		foreach ($items as $item) {
			$goods_id = $item;
			$goods_name ='';
			$unit_price = 0;
			$first_cost = 0;
			$quantity = 0;
			$total = 0;
			//['data']
			foreach ($json as &$value) {

				if($item == $value['receive_order_row_goods_id']){

					if($value['receive_order_row_unit_price']>0){
						$unit_price = $value['receive_order_row_unit_price'];
					}

					$qu = $value['receive_order_row_quantity'];

					if($value['receive_order_row_goods_name'] == $value['goods_name']){
						if($value['receive_order_row_unit_price']>0){
							$goods_name = $value['goods_name'];
						}
					}else{
						if($value['receive_order_row_unit_price']>0){
							$goods_name = $value['receive_order_row_goods_name'];
						}
					}
					//$first_cost += $qu * $value['receive_order_row_received_time_first_cost'];
					$first_cost = $value['receive_order_row_received_time_first_cost'];
					
					if($value['receive_order_row_unit_price']>0){
						$quantity += $qu;
						$total +=  $unit_price * $qu;
					}
				}
			}
			$calc = array();
			$calc = array_merge($calc,array('goods_id'=>$goods_id));
			$calc = array_merge($calc,array('goods_name'=>$goods_name));
			$calc = array_merge($calc,array('unit_price'=>$unit_price));
			$calc = array_merge($calc,array('first_cost'=>$first_cost));
			$calc = array_merge($calc,array('quantity'=>$quantity));
			$calc = array_merge($calc,array('total'=>$total));

			array_push($res,$calc);

		}

		return $res;
	}


	public function get_receiveorder_row_search($req)
	{

		$item = array();
		
		array_push($item,"receive_order_id");//伝票番号
		array_push($item,"receive_order_row_goods_id");//商品コード
		array_push($item,"receive_order_row_goods_name");//商品名
		array_push($item,"receive_order_row_quantity");//受注数
		array_push($item,"receive_order_row_unit_price");//単価
		array_push($item,"receive_order_row_received_time_first_cost");//受注時原価
		array_push($item,"goods_id");//商品コード
		array_push($item,"goods_representation_id");//代表商品コード
		array_push($item,"goods_name");//商品名
		array_push($item,"receive_order_row_cancel_flag");//キャンセルフラグ
		array_push($item,"receive_order_shop_id");//店舗コード
		array_push($item,"receive_order_row_sub_total_price");//小計金額
		array_push($item,"goods_cost_price");//原価
		array_push($item,"goods_selling_price");//売価単価

		
		$res = array();

		// $day = new DateTime($req['stdate']);
		// $day2 = new DateTime($req['eddate']);
		// $diff = $day->diff($day2);

		// $dd = intval($diff->format('%a'));
		//var_dump($dd);

		$orderno = 0;
		while(true){

			// if($i==0){
			// 	$dt = $req['stdate'];
			// }else{
			// 	$dt = $day->modify('+1 day')->format('Y-m-d');
			// }
			//var_dump($dt);

			$url = "https://api.next-engine.org/api_v1_receiveorder_row/search";
			$post_data = array(
				'access_token' => $this->Session->read('access_token'),
				'refresh_token' => $this->Session->read('refresh_token'),
				'receive_order_id-gt' => $orderno,
				'receive_order_date-gte' =>$req['stdate'] . ' 00:00:00',//オーダー時間
				'receive_order_date-lte' =>$req['eddate'] . ' 23:59:59',//オーダー時間
				'receive_order_row_cancel_flag-eq' =>'0',//キャンセル除外
				// 'receive_order_id-gte' => '591435',
				// 'receive_order_id-lte' => '591435',
				'wait_flag' => '1',
				'limit' => 10000,
				'fields' => implode(',', $item)
			);
			if($req['shopcode']>0){
				$post_data = $post_data + array('receive_order_shop_id-eq' => $req['shopcode']);
			}
			

			$ch = curl_init(); // はじめ
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$html =  curl_exec($ch);
			$json = json_decode($html, true);
			$ct = count($json['data']);

			$res = array_merge($res,$json['data']);
			
			$last = end($json['data']);
			$orderno = $last['receive_order_id'];

			if($ct<10000){
				break;
			}
		}

		//scvのダウンロード
		$fname = '/home/ecmasuser/nextengine/orderdownload/m_' . time() . '.csv';
		$file = fopen($fname , "a");
		if( $file ){
			foreach($res as $dt) {
				fputcsv($file, $dt);
			}
		}
		fclose($file);

		$this->setSession($res);
		return $res;
	}


	public function report()
	{
		$shopcode = $this->get_master_shop_search();
		if($shopcode['result']=='error'){
			$this->redirect('error');
		}
		//var_dump($shopcode);

		$scode = array();
		$scode = $scode + array('0' => '全て');
		foreach ($shopcode['data'] as $value) {
			$scode = $scode + array($value['shop_id'] => $value['shop_name']);
		}
		$this->set('scode', $scode);

		if ($this->request->isPost()) {
			$req = $this->request->data;
			$this->set('stdate', $req['stdate']);
			$this->set('eddate', $req['eddate']);
			$this->set('shopcode', $req['shopcode']);
			//受注データ取得
			$dt = $this->get_receiveorder_row_search($req);
			
			$dt2 = $this->totalization3($dt);

			//$dt2 = $this->totalization2($dt);

			$csvname = $this->csvwrite($dt2);

			$this->set('result', $dt);
			$this->set('result2', $dt2);
			$this->set('ct', count($dt));
			$this->set('csvname', $csvname);

			if(isset($req['submit'])){
				if($req['submit']=='ダウンロード'){
				
					$csvFileName = '/home/ecmasuser/nextengine/csvdownload/' . $csvname;

					$content = file_get_contents($csvFileName);
					$str = mb_convert_encoding($content, 'SJIS-win', 'UTF-8');
					
					//file_put_contents($csvFileName, mb_convert_encoding($input, 'SJIS-win', 'utf-8'));
					header('Content-Type: application/octet-stream');
					// ここで渡されるファイルがダウンロード時のファイル名になる
					header('Content-Disposition: attachment; filename=salesdata.csv'); 
					header('Content-Transfer-Encoding: binary');
					header('Content-Length: ' . strlen($str));
					echo $str;
					//readfile($csvFileName);
					exit;
				}
			}

			//var_dump($req);

		}else{
			$this->set('stdate', date("Y-m-d"));
			$this->set('eddate', date("Y-m-d"));
			$this->set('shopcode', '0');
			$this->set('csvname', '');

		}

		// $this->layout = "";
		// $this->autoRender = false;

		//echo $this->Session->read('access_token');



		

		//echo $html;

		//curl -X POST -H 'content-type: application/x-www-form-urlencoded' \  -d 'access_token=xxx&refresh_token=xxx&wait_flag=1&fields=xxx&receive_order_row_receive_order_id-eq=1' \  https://api.next-engine.org/api_v1_receiveorder_row/search 


	}


}
