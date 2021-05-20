<?php

    ini_set('display_errors', "On");
    include("chilkat_9_5_0.php");

    $time_start = microtime(true);
    //初期化
    $glob = new CkGlobal();
    $success = $glob->UnlockBundle('ECMAST.CB1112021_dDfkA83p4eAL');
    if ($success != true) {
        print $glob->lastErrorText() . "\n";
        exit;
    }

    #テスト
    $user_id = 'dexapi_pourvous';
    $password = '123456';

    #本番
    //$user_id = 'dexapi_pourvous';
    //$password = 'N7q8Sr3t';


    $xml = '<?xml version="1.0" encoding="UTF-8"?><request><user_id>' . $user_id . '</user_id><password>' . $password . '</password></request>';
    $xmlCharset = 'utf-8';

    #テスト
    $url = "https://dev99-apib.zozoclub.jp/cooperate/auth";
    $goodsurl = "https://dev99-apib.zozoclub.jp/cooperate/RequestGoods";
    $stockurl ="https://dev99-apib.zozoclub.jp/cooperate/shopstock";

    #本番
    //$url = "https://api.zozo.jp/cooperate/auth";
    //$goodsurl = "https://api.zozo.jp/cooperate/RequestGoods";
    //$stockurl = "https://dev99-apib.zozoclub.jp/cooperate/shopstock";
    

    // $data = "001245" . "\t" . "123456790" . "\t" . "2055" . "\t" . "3" . "\r\n";
    // $data .= "001245" . "\t" . "123456790" . "\t" . "6055" . "\t" . "8" . "\r\n";
    // $data .= "001245" . "\t" . "123456790" . "\t" . "7055" . "\t" . "1" . "\r\n";

    $http = new CkHttp();

    $http->put_CookieDir('memory');
    $http->put_SaveCookies(true);
    $http->put_SendCookies(true);

    $resp = $http->PostXml($url,$xml,$xmlCharset);
    if ($http->get_LastMethodSuccess() != true) {
        //認証エラー
        print $http->lastErrorText() . "\n";
        exit;
    }else{
        print $resp->bodyStr() . "\n";
        //exit;
        if(0){
            #取寄せデータの出力（ZOZO → BRAND）（最大抽出期間7 日）
            #指定期間に受けた客注のうちブランド様倉庫に要求するSKU を出力します。
            $xml = '<?xml version="1.0" encoding="UTF-8"?><request><shopgroupid>0</shopgroupid><start_date>2021/03/25 00:00:00</start_date><end_date>2021/03/25 14:00:00</end_date></request>';
            $resp = $http->PostXml($goodsurl,$xml,$xmlCharset);
            if ($http->get_LastMethodSuccess() != true) {
                //認証エラー
                print $http->lastErrorText() . "\n";
                exit;
            }else{
                print $resp->bodyStr();
            }
            exit();
        }

        // $req = new CkHttpRequest();

        // $req->put_HttpVerb('POST');
        // $req->put_Path('/cooperate/shopstock');
        // $req->AddParam('shopstock',$data);
        
        // // Send the HTTP POST and get the response.
        // // resp is a CkHttpResponse
        // $resp = $http->SynchronousRequest('dev99-apib.zozoclub.jp',443,false,$req);
        // if ($http->get_LastMethodSuccess() == false) {
        //     print $http->lastErrorText() . "\n";
        //     exit;
        // }

        // print $resp->bodyStr() . "\n";

    if(0){

            $req = new CkHttpRequest();

            $req->put_HttpVerb('POST');
            $req->put_Path('/cooperate/shopstock');
            $req->put_ContentType('multipart/form-data');
            $req->AddHeader('Connection','Keep-Alive');
            $req->AddHeader('Accept','text/html');
            $pathToFileOnDisk = './stock.txt';
            //$pathToFileOnDisk = './zozo_inventory-4.csv';
            $success = $req->AddFileForUpload('shopstock',$pathToFileOnDisk);
            if ($success != true) {
                print $req->lastErrorText() . "\n";
                exit;
            }
            // resp is a CkHttpResponse
            $resp = $http->SynchronousRequest('dev99-apib.zozoclub.jp',443,true,$req);
            if ($http->get_LastMethodSuccess() != true) {
                print $http->lastErrorText() . "\n";
                exit;
            }

            print 'HTTP response status: ' . $resp->get_StatusCode() . "\n";
            $htmlStr = $resp->bodyStr();
            print 'Received:' . "\n";
            print $htmlStr . "\n";

     }

    }

    $time = microtime(true) - $time_start;
    echo "処理秒数 ***** {$time} 秒 *****";

?>