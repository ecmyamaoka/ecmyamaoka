<?php

namespace singlebanner;

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
            $res = '<a href="' . $obj['linkurl'] . '">';
            $res .= '<img src="' . $obj['imgpath'] . '"';
            if (isset($obj['width'])){
                $res .= ' width="' . $obj['width'] . '"';
            }
            if (isset($obj['height'])){
                $res .= ' height="' . $obj['height'] . '"';
            }
            $res .= '></a>';
            $obj['html'] = $res;

            $html = tag_customhtml($obj);

        }else{
            return "";
        }
    }
}
