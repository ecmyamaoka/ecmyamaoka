<?php

namespace freehtml;

class Plugin
{
    public function getInfo()
    {
        $json = file_get_contents(__DIR__ . "/plugin.json");
        return $json;
    }
    public function exec($json)
    {
        require(__DIR__ . "/../common.php");

        $obj = json_decode($json, true);
        if($obj['display'] == '有効'){

            //ここで加工する

            return $obj['html'];

        }else{
            return "";
        }
    }
}
