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
class TSalesreportController extends AppController
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

	public function token() {
		
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

	public function index()
	{
	
		echo $this->Session->read('access_token');

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

	public function totalization($json){

		$items = array();

		foreach ($json['data'] as &$value) {
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
			foreach ($json['data'] as &$value) {

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
					$first_cost += $qu * $value['receive_order_row_received_time_first_cost'];
					
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
		
		$url = "https://api.next-engine.org/api_v1_receiveorder_row/search";
		$post_data = array(
			'access_token' => $this->Session->read('access_token'),
			'refresh_token' => $this->Session->read('refresh_token'),
			// 'receive_order_row_unit_price-gt' => '0',
			'receive_order_date-gte' =>$req['stdate'] . ' 00:00:00',//オーダー時間
			'receive_order_date-lte' =>$req['eddate'] . ' 23:59:59',//オーダー時間
			'receive_order_row_cancel_flag-eq' =>'0',//キャンセル除外
			'wait_flag' => '1',
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

		$this->setSession($json);
		return $json;
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
			$dt = $this->get_receiveorder_row_search($req);
			

			$dt2 = $this->totalization($dt);
			$this->set('result', $dt);
			$this->set('result2', $dt2);


		}else{
			$this->set('stdate', date("Y-m-d"));
			$this->set('eddate', date("Y-m-d"));
			$this->set('shopcode', '0');

		}

		// $this->layout = "";
		// $this->autoRender = false;

		//echo $this->Session->read('access_token');



		

		//echo $html;

		//curl -X POST -H 'content-type: application/x-www-form-urlencoded' \  -d 'access_token=xxx&refresh_token=xxx&wait_flag=1&fields=xxx&receive_order_row_receive_order_id-eq=1' \  https://api.next-engine.org/api_v1_receiveorder_row/search 


	}


}
