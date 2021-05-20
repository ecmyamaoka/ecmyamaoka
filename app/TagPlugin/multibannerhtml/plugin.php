<?php

namespace multibannerhtml;

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
      $res = '';
      if(isset($obj['images'])) {
        // スタイルシート
        $now = date("His");
        $res .= '<style>';
        $res .= '.nav' . $now . ' {';
        $res .= 'list-style: none;';
        $res .= 'text-align: center;';
        $res .= '}';
        $res .= '.item' . $now . ' {';
        $res .= 'display: inline;';
        $res .= 'margin-left: ' . $obj['space'] . 'px;';
        $res .= '}';
        $res .= '#itemDescription img {';
        $res .= 'display: initial !important;';
        $res .= '}';
        $res .= '</style>';

        // HTML生成
        $res .= '<ul class="nav' . $now . '">';
        $i = 0;
        foreach($obj['images'] as $image) {
          if ($obj['inline'] == "縦並び") {
            $res .= '<li style="margin-bottom: ' . $obj['space'] . 'px;">';
          } else {
            $res .= '<li class="item' . $now . '"';
            if($i == 0) {
              $res .= 'style="margin-left: 0;"';
            }
            $res .= '>';
            $i += 1;
          }
          if(isset($image['linkurl']) && isset($image['imgpath'])) {

            $res .= '<a href="' . $image['linkurl']. '">';
            $res .= '<img src="' . $image['imgpath'] . '"';
            if (isset($image['width'])){
              $res .= ' width="' . $image['width'] . '"';
            }
            if (isset($image['height'])){
              $res .= ' height="' . $image['height'] . '"';
            }
            $res .= '></a>';
          }
          $res .= '</li>';
        }
        $res .= '</ul>';
      }

      $obj['html'] = $res;
      $html = tag_customhtml($obj);
      return $html;
    }else{
      return "";
    }
  }
}
