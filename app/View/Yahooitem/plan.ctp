<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'util/arraykey');
App::import('Vendor', 'util/Nslog');

$this->assign('title', '利用制限のお知らせ');

?>
<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="alert alert-primary" role="alert">こちらの機能はシルバープラン以上で利用できます。</div>
        </div>
    </div>

    <div Class="row">
        <div Class="col-md-6">
            <div id="cloud1"></div>
        </div>
        <div Class="col-md-6">
            <div id="cloud2"></div>
        </div>
    </div>

</div>