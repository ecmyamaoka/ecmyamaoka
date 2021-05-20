<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'util/arraykey');
App::import('Vendor', 'util/Nslog');
$this->assign('title', 'セット商品');
$arkey = new Util\arraykey();
$log = new Util\Nslog();
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/datatables.min.js"></script>
<?php $this->Html; ?>
<style>
    .calcRow * {
        display:inline-block;
    }
    .sign, .calcItem {
        width: auto;
    }
    .addBtn {
        width: 45px;
        margin-right: 30px;
    }
    #calcItem_0 option:first-of-type, option.hidden, #calcCnt {
        display: none;
    }
    div.submit, #not_company {
        text-align: end;
    }
    input.btn {
        width: 80px;
    }
    #setItem-table th {
        width: 20%;
    }
    #setItemData td {
        vertical-align: middle;
    }
    .upd {
        color: red;
        font-weight: bold;
    }

    @media screen and (max-width: 767px) {
        /* 横幅が767px以下の場合に適用するスタイル */
        form .card:first-child {
            border-right: 1px solid rgba(0,0,0,.125);
            border-bottom: none;
        }
        form .card:first-child+.card { 
            border-left: 1px solid rgba(0,0,0,.125);
            border-top: none;
            margin-top:-20px;
        }
    }
    @media screen and (min-width: 768px) {
        /* 横幅が768px以上の場合に適用するスタイル */
        form .card:first-child {
            border-right: none;
        }
        form .card:first-child+.card { 
            border-left:none;
            vertical-align:top;
            margin-left:-2px;
        }
    }
</style>
<div class="container-fluid">
    <h1><?php 
        echo $company_name;
    ?></h1>
    <div class="row m-3">
        <div class="col-md-5"></div>
        <div class="col-md-6"></div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success" type="button" onclick="location.href='<?php echo $this->html->url('https://nextengine.ec-masters.net/zozo');?>';">戻る</button>
        </div>
    </div>

    <table id="setItem-table" Class="table table-striped">
        <?php if ($zozoUp_comment){ echo "<p class='upd'>{$zozoUp_comment}</p>"; } ?>
        <?php
            echo 
            '<thead>
                <tr>'.
                    '<th>セット商品ID</th>'.
                    '<th>ブランドID</th>'.
                    '<th>ゾゾ商品ID</th>'.
                    '<th>CS商品ID</th>'.
                    '<th></th>'.
                '</tr>'.
            '</thead>'.
            '<tbody id="setItemData">';

            $i= 0;
            if (!empty($updItemData)) {
                echo "<input name='fileOpen' style='visibility: hidden;' value='showFile'/>";
                foreach ($updItemData as $value) {
                    //表示ボタン横並び
                    //post、ファイル情報、同一フォーム
                    echo "<tr id='Row_{$i}' >".
                            "<td id=\"items_{$i}\">{$value['set_goods_id']}</td>".
                            "<td id=\"brand_{$i}\">"."{$value['brand_id']}"."</td>".
                            "<td id=\"zozo_{$i}\">{$value['zozo_goods_id']}</td>".
                            "<td id=\"cs_goods_{$i}\">{$value['cs_goods_id']}</td>".
                            "<td>".$this->Form->botton("{$i}", 
                                                    ['id' => "btn_{$i}",
                                                    'type'=>'button',
                                                    'class'=>'btn btn-primary',
                                                    'value' => "削除",
                                                    'onClick' => 'itme_delete(arguments[0])' ]).
                            "</td>".
                        "</tr>";
                    $i++;
                }
            } else {
                //商品が一件もない場合に行だけ作成
                echo "<tr id='Row_{$i}' ></tr>";
            }
            echo '</tbody>';
        ?>
    </table>

    <script type="text/javascript">
        const URL = "https://nextengine.ec-masters.net/zozo";
        // 追加処理
        function item_insert(e) {
            var id = e.target.id;
            var idx = id.split("_")[1];
            var item = $("#items_" + idx).text();
            var in_set = {  "in_item": $("#in_item").val(),
                            "in_brand": $("#in_brand").val(),
                            "in_zozoid": $("#in_zozoid").val(),
                            "in_csgoods": $("#in_csgoods").val()  };
            
            $.ajax({type: "POST",
                url: URL + "/setItem_insert",
                global: false,
                cache: false,
                dataType: "html",
                data: in_set,
                success: function(msg) {
                    //javascriptからリダイレクト
                    location.href= URL + "/setItems";
                }
            });
        }
        // 削除処理
        function itme_delete(e) {
            var id = e.target.id;
            var idx = id.split("_")[1];
            var item = $("#items_" + idx).text();
            
            $.ajax({type: "POST",
                url: URL + "/setItem_delete",
                global: false,
                cache: false,
                dataType: "html",
                data: "item=" + item,
                success: function(msg) {
                    //javascriptからリダイレクト
                    location.href= URL + "/setItems";
                }
            });
        }

        // セット商品の追加行用
        const rowTemp = "<tr id=\"Row_%no%\">" + 
                            "<td id=\"items_%no%\"><input id=\"in_item\" type=\"text\"></input></td>"+
                            "<td id=\"brand_%no%\"><input id=\"in_brand\" type=\"text\"></input></td>"+
                            "<td id=\"zozo_%no%\"><input id=\"in_zozoid\" type=\"text\"></input></td>"+
                            "<td id=\"cs_goods_%no%\"><input id=\"in_csgoods\" type=\"text\"></input></td>"+
                            "<td><input id=\"addBtn\" type=\"button\" class=\"btn btn-primary\" value=\"追加\" onclick=\"item_insert(arguments[0])\"></td>"+
                        "</tr>";
        //セット商品、追加用行作成
        function addRow(e) {
            var tbody = $("#setItemData");
            var lastRow = tbody.find("tr:last");
            var len = (lastRow.attr("id").replace("Row_","")-0) + 1;
            
            //追加想定のhtmlに置換
            htmlFormat = rowTemp.replaceAll("%no%", len);
            lastRow.after(htmlFormat);  //追加実行
        }
        addRow();
    </script>
</div>