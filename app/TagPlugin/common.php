<?php

    function tag_customhtml($param)
    {
        $flg = $param['dateflg'];
        $st = $param['starttime'];
        $ed = $param['endtime'];

        if(!isset($param['bntype'])){
            $param['bntype']='通常';
        }

        $obj = tag_position($param['bntype'],$param['html']);

        if($flg == true && isset($st) && isset($ed)){
            //時間指定あり
            return add_js() . tag_datetime($flg,$st,$ed,$obj);
        }else{
            //時間指定なし
            if($obj['type']=='html'){
                return add_js() . $obj['body'];
            }else{
                return add_js() . add_jQuery($obj['body']);
            }
        }
    }

    function add_jQuery($obj)
    {
        $res = '<script>';
        $res .= 'jQuery(function($) {';
        $res .= $obj;
        $res .= '});';
        $res .= '</script>';
        return $res;
    }

    function add_js()
    {
        //追加jsがあればここに
        $res = '<script>';
        $res .= 'function tagrep(c) {';
        $res .= 'return c.replace(/(&lt;)/g, "<").replace(/(&gt;)/g, ">").replace(';
        $res .= '/(&quot;)/g, \'"\').replace(/(&#39;)/g, "\'").replace(/(&amp;)/g, "&")';
        $res .= '};';
        $res .= '</script>';

        return $res;
    }

    function tag_datetime($flg,$st,$ed,$obj)
    {
        if($flg == true && isset($st) && isset($ed)){
        //
            $date_st = new DateTime($st);
            $date_ed = new DateTime($ed);

            $res ='<div id="tag' . $date_st->format('U') . '"></div>';
            $res .= '<script>';
            $res .= 'jQuery(function($) {';
            $res .= "var sttime = " . $date_st->format('U') . ';';
            $res .= "var edtime = " . $date_ed->format('U') . ';';
            $res .= "var date = new Date();";
            $res .= "var today =  Math.floor(date.getTime()/1000);";

            $res .= 'if(sttime <= today && today <= edtime){';

            // $res .= 'console.log("log now:" + today);';
            // $res .= 'console.log("log sttime:" + sttime);';
            // $res .= 'console.log("log sttime:" + edtime);';

            if($obj['type']=='html'){
                $res .= '$(\'#tag' . $date_st->format('U') . '\').append(tagrep("' . htmlspecialchars($obj['body']) . '"));';
            }else{
                $res .= $obj['body'];
            }
            $res .='}';
            $res .= '});';
            $res .= '</script>';
            return $res;

        }else{
            return $html;
        }

    }

    function tag_position($tagname,$html)
    {
        $timestamp = time();

        if(strcmp($tagname,'通常')==0){
            return array('type'=>'html', 'body'=>$html);;
        }
        if(strcmp($tagname,'PCフローティング下部')==0){
            $res = '<div style="position:fixed; z-index:10000; bottom:0; left:0; padding:10 0 5px; text-align:center; width:100%; line-height:0;">';
            $res .= $html;
            $res .= '</div>';
            return array('type'=>'html', 'body'=>$res);
        }
        if(strcmp($tagname,'SPフローティング下部')==0){
            $res = '<style>';
            $res .= ' .tag' . $timestamp . '{position:fixed;z-index:1;bottom:71px;left:0;right:0;padding:10 0 25px;text-align:center;width:100%;line-height:0;margin-left:auto;margin-right:auto;width:calc(100% - 20px);}';
            $res .='</style>';
            $res .= '<div class="tag' . $timestamp . '">' . $html;
            $res .= '</div>';
            return array('type'=>'html', 'body'=>$res);
        }
        if(strcmp($tagname,'SP商品画像上部')==0){

            $text = str_replace(PHP_EOL, '', $html);
            //$res = '<script>';
            //$res = 'jQuery(function($) {';
            $res = '$(\'#shopName\').append(tagrep("' . htmlspecialchars($text) . '"));';
            //$res .= '});';
            //$res .= '</script>';
            return array('type'=>'js', 'body'=>$res);
        }
        if(strcmp($tagname,'SP商品画像下部')==0){
            $text = str_replace(PHP_EOL, '', $html);
            //$res = '<script>';
            //$res = 'jQuery(function($) {';
            $res = '$(\'#itemImageSlider\').append(tagrep("' . htmlspecialchars($text) . '"));';
            $res .= '$(\'#itemImageSlider\').css(\'margin\',\'11px 0 0 0\');';
            //$res .= '});';
            //$res = '</script>';
            return array('type'=>'js', 'body'=>$res);
        }
        if(strcmp($tagname,'SPタイトル下部')==0){
            $text = str_replace(PHP_EOL, '', $html);
            //$res = '<script>';
            //$res = 'jQuery(function($) {';
            $res = '$(\'.ctgTtl\').after(tagrep("' . htmlspecialchars($text) . '"));';
            $res .= '$(\'#shopName\').append(tagrep("' . htmlspecialchars($text) . '"));';
            //$res .= '});';
            //$res = '</script>';
            return array('type'=>'js', 'body'=>$res);
        }

    }
