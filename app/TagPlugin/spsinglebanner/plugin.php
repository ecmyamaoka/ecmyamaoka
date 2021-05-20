<?php

namespace spsinglebanner;

class Plugin
{
    public function getInfo()
    {
        $json = file_get_contents(__DIR__ . "/plugin.json");
        return $json;
    }
    
    public function exec($json)
    {
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

            $css = "<style>";
            if (isset($obj['bottom1'])){
                $css .=".chat_badge_base{bottom: " . $obj['bottom1'] . " !important;}";
            }
            if (isset($obj['bottom2'])){
                $css .=".floating-up-button{bottom: " . $obj['bottom2'] . " !important;}";
            }
            if (isset($obj['bottom3'])){
                $css .=".togglePopup{bottom: " . $obj['bottom3'] . " !important;}";
            }

            if($obj['bntype']=='SP フローティングバナー(下)'){
                $css .= " .ftbanner{position:fixed;z-index:1;bottom:71px;left:0;right:0;padding:10 0 25px;text-align:center;width:100%;line-height:0;margin-left:auto;margin-right:auto;width:calc(100% - 20px);}";
                $res = '<div class="ftbanner">' . $res;
                $res .= '</div>';
            }else{
                $res = '<div>' . $res .  '</div>';
            }
            $css .="</style>";

            return $css . $res;
        }else{
            return "";
        }
    }
}
