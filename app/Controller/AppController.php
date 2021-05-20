<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public function beforeFilter()
    {
        $LoginAuth = false;
        $auth = Configure::read("Auth");

        if($this->action=='index'){
            //ヘルプの場合、誰でも見れるようにする
            //indexは機能説明ページにする
            
        }elseif ($auth == "1") {
            ini_set( 'display_errors', 0 );
            ini_set('session.cookie_domain', '.ec-masters.net');

            if (!isset($_COOKIE['PHPSESSID'])) {
                //PHPSESSIDがない
                $LoginAuth = false;

            } else {
                //PHPSESSIDある
                // $memcache = new Memcached();
                // $memcache->addServer('localhost', 11211);

                // $dt = $memcache->get($_COOKIE['PHPSESSID']);
                $r = new Redis();
                $r->connect('ecclub.redis.cache.windows.net', 6379);
                $r->auth('14V0+W2bVI24P1PgKksiNYOuEPe1qiaJMKQZT+5TNfk=');
                $rd = $r->get("club-" . $_COOKIE['PHPSESSID']);
                $dt = json_decode($rd,true);


                if (!empty($dt)) {
                    if(isset($dt['uid'])){
                        $LoginAuth = true;
                    }else{
                        $LoginAuth = false;
                    }
                } else {
                    $LoginAuth = false;
                }
            }
            if($LoginAuth == false){
                $url = Router::reverse($this->request, true);

                header("Location: https://tool.ec-masters.net/login.php?redirect=" . urlencode($url));
                exit;
            }

        }
    }
}
