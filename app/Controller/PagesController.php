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

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @return CakeResponse|null
 * @throws ForbiddenException When a directory traversal attempt.
 * @throws NotFoundException When the view file could not be found
 *   or MissingViewException in debug mode.
 */
	public function display() {

		App::import('Model','ConnectionManager');

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
