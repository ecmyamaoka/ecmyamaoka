<?php

namespace sidebanner;

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
      // スタイルシート
      $now = date("His");
      $res .= '<style>';
      $res .=
      "#sideBanner$now {
        position: fixed;
        {$obj['lrPosition']}: {$obj['lrInterval']}px;
        {$obj['tbPosition']}: {$obj['tbInterval']}px;
        z-index: 99999;
        width: {$obj['sideBannerWidth']}px;
        padding: 2px;
        box-shadow: 4px 4px 4px rgba(0,0,0,0.1);
        border: 1px solid #C0C0C0;
        background: white;
      }
      #sideBanner$now #topHtml {
        text-align: center;
      }
      #sideBanner$now #bottomHtml {
        text-align: center;
        clear:both;
      }
      #sideBanner$now #nav {
        list-style: none;
        width: 100%;
        padding-inline-start: 0;
      }
      #sideBanner$now #nav img {
        margin: {$obj{'bannerInterbal'}}px 0;
        float: {$obj['lrPosition']};
      }";
      if($obj['animation'] == "有効") {
        $res .=
        "#sideBanner$now #nav li img{
          transition: 1s;
          -webkit-transition: 1s;
        }
        #sideBanner$now #nav li img:hover{
          margin-{$obj['lrPosition']}: 50px;
          transform: scale(1.1);
        }";
      }
      $res .= "</style>";

      $res .= '<div id="sideBanner' . $now . '">';
      $res .= '<div id="topHtml">' . $obj['tophtml'] . '</div>';
      $res .= '<ul id="nav">';
      foreach($obj['images'] as $image) {
        $res .= '<li>';
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
      $res .= '<div id="bottomHtml">' . $obj['bottomhtml'] . '</div>';
      $res .= '</div>';

      // var_dump($res);
      // exit();

      $obj['html'] = $res;
      $html = tag_customhtml($obj);
      return $html;
    }else{
      return "";
    }
  }
}
