<?php

ini_set('session.cookie_domain', '.ec-masters.net');
session_start();
//phpinfo();

ini_set("display_errors", 2);
error_reporting(E_ALL);



$memcache = new Memcached();
$memcache->addServer('localhost', 11211);


//$memcache->set('key12345', $array, 60*60*6);
// if(!empty($memcache->get('key00000'))){
//     echo '1:' . $memcache->get('key00000') . PHP_EOL;
// }

// echo "OK:";
// echo "id:" . session_id() . PHP_EOL;
echo date("Y/m/d H:i:s") . PHP_EOL;
echo "<br>";

//$memcache->delete(session_id());
echo session_id();
echo "<br>";

$dt = $memcache->get(session_id());

if(!empty($dt)){
    echo print_r($dt ) . PHP_EOL;
}

