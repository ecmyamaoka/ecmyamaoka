<?php
session_start();

ini_set("display_errors", 2);
error_reporting(E_ALL);



require("./RNCryptor/Cryptor.php");
require("./RNCryptor/Decryptor.php");
require("./RNCryptor/Encryptor.php");
require("./RNCryptor/functions.php");
/*----------------------------------------------------------------------------*
    *  【00】初期設定                                                            *
    *----------------------------------------------------------------------------*/

define("PASSWORD", "xzNCIm4yZihjH7E0");

if (isset($_POST['u'])) {
    $str = $_POST['u'];

    $cryptor = new \RNCryptor\Decryptor();

    try{

        $sessiondata = $cryptor->decrypt($str, PASSWORD);

        $jsonstr =  json_decode($sessiondata, true);
        $uid = $jsonstr['PHPSESSID'];
        if(!empty($uid)){

            $memcache = new Memcached();
            $memcache->addServer('localhost', 11211);
            $memcache->set($uid, $jsonstr, time() + 21600);
    
        }
    
    }catch(Exception $ex){

    }

}
