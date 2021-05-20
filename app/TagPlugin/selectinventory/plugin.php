<?php

namespace selectinventory;

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

      $css ='<style>';
      foreach ($obj['images'] as $value) {
        $css .='.itemSelectPopUp dl:nth-of-type(' . $value['row'] . ') dd:nth-of-type(' . $value['col'] . '):before {';
        $css .='background-image: url(' . $value['url'] . ');';
        $css .='}';
      }

      $css .= '.itemSelectPopUp dd:before {';
      $css .= 'display:inline-block;';
      $css .= 'position:absolute;';
      $css .= 'content:"";';
      $css .= 'width:5pc;';
      $css .= 'height:5pc;';
      $css .= 'background-size: 90%;';
      $css .= 'background-repeat:no-repeat;';
      $css .= 'background-position:center';
      $css .= '}';

      $css .= '.itemSelectPopUp dl .icon {';
      $css .= 'position:relative;';
      $css .= 'left:5pc';
      $css .= '}';

      $css .= '.itemSelectPopUp dl .caption,.itemSelectPopUp dl .name {';
      $css .= 'padding-left:5pc';
      $css .= '}';

      $css .= '.itemSelectPopUp label {';
      $css .= 'padding: 0 34px 0 0;';
      $css .= '}';

      $css .= '.itemSelectPopUp dd {';
      $css .= 'min-height:5pc;';
      $css .= 'height:81px';
      $css .= '}';

      $css .= '.itemSelectPopUp .itemSelectRadio {';
      $css .= 'top:40%;';
      $css .= '}';

      $css .= '.itemSelectPopUp .itemSelectRadio+.selected {';
      $css .= 'top:44%;';
      $css .= '}';

      $css .='</style>';

      return $css;
    }else{
      return "";
    }
  }

}
