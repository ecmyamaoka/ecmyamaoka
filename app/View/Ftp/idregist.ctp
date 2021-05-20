<?php

$this->assign('title', 'FTPAuto君 設定');
?>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="/ftp/index/0">楽天CSV</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/ftp/index/1">楽天GOLD</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="/ftp/idregist">設定</a>
    </li>
</ul>

<div class="row">
    <div class="col-lg-8 col-md-7 col-sm-6">
        <?php
        if ($status == false) {
            echo $this->Form->create(
                false,
                array('url' => array('action' => 'idregist'), 'type' => 'post')
            );
            echo $this->Form->submit('初期設定を行う', array(
                'div' => false,
                'class' => 'btn btn-primary col-lg-3  col-md-3 col-sm-12'
            ));
            echo $this->Form->end();
        } else {
            echo '<h5>ご利用できます</h5>';
            echo $donemessage;
        }
        ?>
    </div>
</div>
<div class="row margintop20">
    <div class="col-lg-8 col-md-7 col-sm-6">
    FTPAuto君の設定方法 ffftpなど
    </div>
</div>
