<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'util/arraykey');
App::import('Vendor', 'util/Nslog');

$this->assign('title', 'Yahoo!確認君(Y)');


$arkey = new Util\arraykey();
$log = new Util\Nslog();

//echo $akey->check();





?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/datatables.min.js"></script>


<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script> -->
<script>
    jQuery(function($){ 
        $.extend( $.fn.dataTable.defaults, { 
        language: {
            url: "https://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json"
            } 
        }); 
        $("#foo-table").DataTable({
            // 件数切替機能 無効
            lengthChange: false,
            // 検索機能 無効
            searching: false,
            // ソート機能 有効
            ordering: true,
            // 情報表示 無効
            info: false,
            // ページング機能 無効
            paging: false
        });
    }); 
</script>

<style>
.sample1 {
	width:			50px;
	height:			50px;
	overflow:		hidden;
	margin:			0px 0px 0px 0px;
	position:		relative;	/* 相対位置指定 */
    display:    inline-block;
}
.sample1 .caption {
	font-size:		16px;
	text-align: 		center;
	padding-top:		10px;
	color:			#fff;
    position:		absolute;
    top:        0px;
}
.sample1 .mask {
	width:			50px;
	height:			50px;
	position:		absolute;	/* 絶対位置指定 */
	top:			0;
	left:			0;
	opacity:		0;	/* マスクを表示しない */
	background-color:	rgba(0,0,0,0.4);	/* マスクは半透明 */
	-webkit-transition:	all 0.2s ease;
	transition:		all 0.2s ease;
}
.sample1:hover .mask {
	opacity: 1;	/* マスクを表示する */
}

.table{
    font-size:14px;
}
</style>
<div class="container-fluid">
    <?php
    echo  $this->Form->create(
        false,
        array('url' => array('action' => 'index'), 'type' => 'get')
    );
    ?>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <?php
            echo $this->Form->input('u', array(
                'label' => false,
                'div' => array(
                    'class' => 'form-group row'
                ),
                'value' => $param['u'],
                'between' => '<div class="col-sm-12">',
                'after' => '</div>',
                'class' => 'form-control',
                'placeholder' => 'ストアID'
            ));
            ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <?php
            echo $this->Form->input('q', array(
                'label' => false,
                'div' => array(
                    'class' => 'form-group row'
                ),
                'value' => $param['q'],
                'between' => '<div class="col-sm-12">',
                'after' => '</div>',
                'class' => 'form-control',
                'placeholder' => 'キーワード'
            ));
            ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <?php
            echo $this->Form->input('s', array(
                'label' => false,
                'div' => array(
                    'class' => 'form-group row'
                ),
                'type' => 'select',
                'options' => array('1'=>'画像+サブ画像取得','2'=>'カートに入っている人数取得'),
                'value' => $param['s'],
                'between' => '<div class="col-sm-12">',
                'after' => '</div>',
                'class' => 'form-control',
                'placeholder' => 'キーワード'
            ));
            ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
            <div class="form-check-inline line-height">
                <?php
                echo $this->Form->submit('取得', array(
                    'div' => false,
                    'class' => 'btn btn-primary btn-block ml-3'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        ストアIDの指定<br>
        http://store.shopping.yahoo.co.jp/○○○○/index.html の○○○○を入力してください
        </div>
    </div>


    <?php
    echo $this->Form->end();
    ?>
    <?php
        if($param['s'] == 2){
            echo '<hr><div class="text-right"><a class="btn btn-success" href="/yahooitem/download">CSVダウンロード</a></div>';
        }
    ?>

    <table id="foo-table" Class="table table-striped">
        <?php
        if($param['s'] == 1){
            echo '<thead><tr>';
            echo '<th>商品コード</th>';
            echo '<th>画像</th>';
            echo '<th>商品名</th>';
            echo '<th>サブ画像</th>';
            echo '<th width="100px">画像数</th>';
            echo '</tr></thead>';
        }
        if($param['s'] == 2){
            echo '<thead><tr>';
            echo '<th>商品コード</th>';
            echo '<th>画像</th>';
            echo '<th>商品名</th>';
            echo '<th width="100px">カートイン数</th>';
            echo '</tr></thead>';
        }
        echo '<tbody>';

        foreach ($dt as $value) {
            $ct = 0;
            echo '<tr>';
            echo '<td>';
            $code = explode ('_',$value['Code']);
            //echo  $value['detail']['Result']['Hit']['ItemCode'];
            echo  $code[1];
            echo '</td>';
            echo '<td>';
            echo '<img width="50" src="' . $value['Image']['Small'] . '">';
            echo '</td>';
            echo '<td>';
            echo '<a target="_blank" href="' . $value['Url'] . '">' . $value['Name'] . '</a>';
            echo '</td>';

            if($param['s'] == 1){
                // echo '<td>';
                // echo '<img width="50" src="' . $value['detail']['Result']['Hit']['Image']['Small'] . '">';
                // echo '</td>';
                
                echo '<td>';
                if(isset($value['detail']['Result']['Hit']['RelatedImages']['Image'])){
                    if(isset($value['detail']['Result']['Hit']['RelatedImages']['Image']['Small'])){
                        $ct = 1;
                        echo '<span class="sample1">';
                        echo '<img width="50" src="' . $value['detail']['Result']['Hit']['RelatedImages']['Image']['Small'] . '">';
                        echo '<div class="mask">';
                        echo '<div class="caption">No.1</div>';
                        echo '</div>';
                        echo '</span>';
                    }else{
                        $ct = count($value['detail']['Result']['Hit']['RelatedImages']['Image']);
                        $count = 1;
                        foreach ($value['detail']['Result']['Hit']['RelatedImages']['Image'] as $value){
                            echo '<span class="sample1">';
                            echo '<img width="50" src="' . $value['Small'] . '">';
                            echo '<div class="mask">';
                            echo '<div class="caption">No.' . $count . '</div>';
                            echo '</div>';
                            echo '</span>';
                            $count += 1;
                        }
                    }
                }
    
                echo '</td>';
                echo '<td class="text-right">';
                echo $ct;
                echo '</td>';
            }

            if($param['s'] == 2){
                echo '<td class="text-right">';
                echo $value['detail']['ResultSet']['Result'][0]['InCart'];
                echo '</td>';
                // echo '<td>';
                // echo $value['detail']['ResultSet']['Result'][0]['InFavorite'];
                // echo '</td>';
            }

            echo '</tr>';
        }
        echo '</tbody>';
        ?>
    </table>
</div>