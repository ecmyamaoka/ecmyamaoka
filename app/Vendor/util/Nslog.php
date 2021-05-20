<?php

namespace Util;

class Nslog {
    public function out($str) {
        if($_SERVER["REMOTE_ADDR"]=='109.236.1.108'){
            print_r('<pre>');
            print_r($str);            
            print_r('</pre>');
        }
    }
}

