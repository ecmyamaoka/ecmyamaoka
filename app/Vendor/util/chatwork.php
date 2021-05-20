<?php

namespace Util;

class chatwork {

    public function out($str) {
        if($_SERVER["REMOTE_ADDR"]=='219.117.193.33'){
            print_r('<pre>');
            print_r($str);            
            print_r('</pre>');
        }
    }

    public function post($channel,$body){

        // API 87f8ab4f00d1a163906663f5ce5db948   yamaoka
        //API dd928ff15709759efb088d348f530032 bot
        $id="dd928ff15709759efb088d348f530032";    

       try {  
        
        $POST_DATA = array(
            'body' => $body
        );
        $headers = array(
            "HTTP/1.0",
            "Accept-Encoding:gzip ,deflate",
            "Accept-Language:ja,en-us;q=0.7,en;q=0.3",
            "Connection:keep-alive",
            "X-ChatWorkToken:" . $id,
            "User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36"
            );

        $url = 'https://api.chatwork.com/v2/rooms/' . $channel . '/messages';
   

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($POST_DATA));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        } catch ( Exception $ex ) {
            
       }

    }

    public function mb_str_pad ($input, $pad_length, $pad_string=" ", $pad_style=STR_PAD_RIGHT, $encoding="UTF-8") {
        $mb_pad_length = strlen($input) - mb_strlen($input, $encoding) + $pad_length;
        return str_pad($input, $mb_pad_length, $pad_string, $pad_style);
    }

}

