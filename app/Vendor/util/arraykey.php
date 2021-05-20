<?php

namespace Util;

class arraykey {
    public function check($array,$key) {
        if (!empty($array)){
            if (isset($array['item'][$key])) {
                return $array['item'][$key];
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
}

