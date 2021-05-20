<?php

namespace universaltag;

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
    $res = '';
    if($obj['display'] == '有効'){

      $res .= '<script type="text/javascript">
      (function () {
        var tagjs = document.createElement("script");
        var s = document.getElementsByTagName("script")[0];
        tagjs.async = true;
        tagjs.src = "//s.yjtag.jp/tag.js#site=' . $obj['id'] . '&referrer=" + encodeURIComponent(document.location.href) + "";
        s.parentNode.insertBefore(tagjs, s);
      }());
      </script>
      <noscript>
      <iframe src="//b.yjtag.jp/iframe?c=' . $obj['id'] . ' width="1" height="1" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
      </noscript>';

      $obj['html'] = $res;
      $html = tag_customhtml($obj);
      return $html;
    }else{
      return "";
    }
  }
}
