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
class NextengineController extends AppController
{


	public $uses = array();

	public function token() {
		
		//$this->autoRender = false;
		$this->layout = "";
		$this->autoRender = false;

		$client_id = $_GET["client_id"];
		$redirect_uri = $_GET["redirect_uri"];
		$state = $_GET["state"];
		$uid = $_GET["uid"];

		$url = "https://api.next-engine.org/api_neauth";
		$post_data = array(
			'uid' => $_GET["uid"],
			'state' => $_GET["state"],
			'client_id' => '81pgNEP9wKfncb',
			'client_secret' => 'vGtCAs9PNHmMuyQJVxernz26dhYlKqi8IfF3aTpD'
		);

		$ch = curl_init(); // はじめ
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html =  curl_exec($ch);

		if(isset($html)){

			$json = json_decode($html, true);
			//https://developer.next-engine.com/api/api_v1_neauth
			echo $json['access_token'];
			echo $json['company_app_header'];
			echo $json['company_ne_id'];
			echo $json['company_name'];
			echo $json['company_kana'];
			echo $json['uid'];
			echo $json['pic_ne_id'];
			echo $json['pic_name'];
			echo $json['pic_kana'];
			echo $json['pic_mail_address'];
			echo $json['refresh_token'];
			echo $json['result'];
	
		}




		// $this->set('access_token', $json['access_token']);
		// $this->set('token_type', $json['token_type']);
		// $this->set('expires_in', $json['expires_in']);
		// $this->set('refresh_token', $json['refresh_token']);
		// $this->set('id_token', '');


	}




	public function index()
	{

	}

}
