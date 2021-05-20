<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'util/arraykey');
App::import('Vendor', 'util/Nslog');
$this->assign('title', 'ZOZO在庫');
$arkey = new Util\arraykey();
$log = new Util\Nslog();
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.20/datatables.min.js"></script>
<div class="container-fluid">
    <h1><?php 
    echo $company_name;
    ?></h1>
    <div class="row m-3">
    <div class="col-md-5">
    <!-- <h5 class="text-left"><?php 
    echo $datetime->format("Y/m/d H:i:s");
    ?> 以降の更新在庫</h5> -->
    </div>
    <div class="col-md-6">
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-success" type="button" data-toggle="collapse" data-target="#setcollapse" aria-expanded="false" aria-controls="setcollapse">ZOZO 設定</button>
        <!-- <button type="button" class="btn btn-success" type="button" data-toggle="collapse" data-target="#setcollapse2" aria-expanded="false" aria-controls="setcollapse2">UPLOAD</button> -->
        <!-- <span>　<?php echo $fileName; ?></span> -->
    </div>
    </div>
    <div class="row collapse m-3 <?php echo ($show) ? "show" : ""; ?>" id="setcollapse">
        <div class="col-md-7">
            <div class="card card-body">
            <!-- <form method="post" enctype="multipart/form-data"> -->
            <form method="post" action='zozo/submit' enctype="multipart/form-data">
                <div class="form-group">
                    <?php if ($zozoUp_comment){
                            echo "<p style='color: red; font-weight: bold;'>".$zozoUp_comment."</p>";
                        } ?>
                        
                    <!-- コメントアウト

                        <label for="exampleInputEmail1">Email address</label> 
                     <?php echo $this->Form->text('email', ['id' => 'InputEmail',
                                                'class' => 'form-control',
                                                'aria-describedby' => 'emailHelp',
                                                'placeholder'=> 'APIキー', $disabled]); ?>
                     <small id="emailHelp" class="form-text text-muted">説明</small>
                     <input type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="APIキー">
                </div>
                <div class="form-group">
                    <label for="exampleInputAccessToken">access token</label>
                    <?php echo $this->Form->text('access_token',['id' => 'InputAccessToken',
                                                'class' => 'form-control',
                                                'placeholder'=> 'access token', $disabled]); ?>
                </div>
                <div class="form-group">
                    <label for="exampleInputRefreshToken">refresh token</label>
                    <?php echo $this->Form->text('refresh_token',['id' => 'InputRefreshToken',
                                                'class' => 'form-control',
                                                'placeholder'=> 'refresh token', $disabled]); ?> 
                    
                    コメントアウト　ここまで    -->
                </div>

                <div class="form-group">
                    <label for="exampleInputZozoFlag">ZOZO有効フラグ</label><br>
                    <?php echo $this->Form->select('zozo_flag', 
                                                ['0'=>'無効', '1'=>'有効'], 
                                                ['empty'=>false,
                                                'value'=> $zozo_flag,
                                                'class' => 'form-control', $disabled,
                                                'onChange' => 'zozoOn()']); ?>
                </div>
                <div class="form-group">
                    <label for="exampleInputShopNumber">Shop Number</label>
                    <?php echo $this->Form->text('shopNm',['id' => 'InputShopNumber',
                                                'class' => 'form-control',
                                                'placeholder'=> 'Shop Number',
                                                'value'=> $shopNm, $disabled]); ?>
                    <!-- <input type="text" class="form-control" id="InputZozoId" value="" placeholder="ZOZO ID"> -->
                </div>
                <div class="form-group">
                    <label for="exampleInputZozoId">ZOZO ID</label>
                    <?php echo $this->Form->text('zozo_id',['id' => 'InputZozoId',
                                                'class' => 'form-control',
                                                'placeholder'=> 'ZOZO ID',
                                                'value'=> $zozo_id, $disabled]);
                                                ?>
                    <!-- <input type="text" class="form-control" id="InputZozoId" value="" placeholder="ZOZO ID"> -->
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <?php echo $this->Form->password('password',['id' => 'InputPassword',
                                                'class' => 'form-control',
                                                'placeholder'=> 'Password', $disabled]); ?>
                </div>
                <div class="form-group">
                    <label for="exampleInputZozoStockRate">ZOZO在庫割当率</label><br>
                    <?php echo $this->Form->text('zozo_stockRate', ['id' => 'InputZozoStockRate',
                                                'class' => 'form-control',
                                                'placeholder'=> '整数３桁',
                                                'value'=> $zozo_stockRate, $disabled]); ?>
                </div>
                <div class="form-group">
                    <label for="exampleInputZozoRoundUp">切り捨て/切り上げ</label><br>
                    <?php echo $this->Form->select('roundup', 
                                                //['0'=>'切り捨て', '1'=>'四捨五入', '2'=>'切り上げ'], 
                                                ['0'=>'切り捨て', '1'=>'切り上げ'], 
                                                ['empty'=>false,
                                                'value'=> $roundup,
                                                'class' => 'form-control', $disabled]); ?>
                </div>
                <div class="form-group" id="calcSet">
                    <input id="calcCnt" name='calcCnt' style='visibility: hidden;' value="<?php echo count($calcSet); ?>" ></input><br>
                    <label>計算式</label><br>
                    <?php   $view_i = 0;
                            $cnt = count($calcSet);
                            if ($disabled) {
                                $calcSet = [["sign_{$view_i}" => "",
                                             "calcItem_{$view_i}" => ""]];
                            }
                            $default =['stock_free_quantity'=>'フリー在庫', 
                                    'stock_quantity'=>'在庫数',
                                    'stock_allocation_quantity'=>'引当数',
                                    'stock_defective_quantity'=>'不良在庫数',
                                    'stock_remaining_order_quantity'=>'発注残数',
                                    'stock_out_quantity'=>'欠品数',
                                    'stock_advance_order_quantity'=>'予約在庫数',
                                    'stock_advance_order_allocation_quantity'=>'予約引当数',
                                    'stock_advance_order_free_quantity'=>'予約フリー在庫数'];
                            $list = $default;
                            foreach ($calcSet as $set) {
                                echo "<div id='calcDiv_{$view_i}' class='calcRow' style='width: 100%;'>";
                                echo $this->Form->button('　+　',
                                                        ['id' => "addBtn_{$view_i}",
                                                        'type'=> 'button',
                                                        'empty'=> false,
                                                        'value'=> ' ',
                                                        'class' => 'form-control addBtn',
                                                        'style'=>'display: inline-block; width: 45px; margin-right: 30px; visibility: hidden;',
                                                        $disabled]);
                                //echo "<input type=\"button\" id=\"calcCnt\" name='calcCnt' class=\"form-control addBtn\" style='display: inline-block; width: 45px;' ></input><br>";
                                //表示ボタン横並び
                                //post、ファイル情報、同一フォーム
                                echo $this->Form->select('sign_'.$view_i,
                                                        ['0'=>'+', '1'=>'-'], 
                                                        ['empty'=> false,
                                                        'value'=> $set['sign_'.$view_i],
                                                        'class' => 'form-control sign',
                                                        'style'=>'display: inline-block; width: auto;',
                                                        $disabled]);
                                echo $this->Form->select('calcItem_'.$view_i,
                                                        $list,
                                                        ['empty'=>[''=>'指定なし'],
                                                        'value'=> $set['calcItem_'.$view_i],
                                                        'class' => 'form-control calcItem',
                                                        //'onChange' => 'changeList(arguments[0])',
                                                        'onChange' => '',
                                                        'style'=>'display: inline-block; width: auto;',
                                                        $disabled]);
                                echo "</div>";
                                //unset($list[$set["calcItem_{$view_i}"]]);
                                $view_i++;
                            } ?>
                </div>
                <br><?php if (!empty($disabled)) echo "企業名が不明なため、保存できません。"; ?><br>
                <?php echo $this->Form->submit('保存', ['class'=>'btn btn-primary', ($disabled) ? "disabled" : ""]);
                    //echo $this->Form->postButton('保存','/zozo/upload', ['class'=>'btn btn-primary', $disabled]);
                    //$this->requestAction('/zozo/upload');
                ?>
                </form>
            </div>
        </div>
    </div>
    <!-- <div class="row collapse m-3" id="setcollapse2">
        <div class="col-md-6">
            <div class="card card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <?php echo $up_comment ?>
                    <?php echo $this->Form->file('file', ['class'=>'form-control-file','accept'=>'text/csv', $disabled]); ?>
                </div>
                <?php echo $this->Form->submit('アップロード', ['class'=>'btn btn-primary', $disabled]); ?>
            </form>
            </div>
        </div>
    </div> -->
    <!-- <?php phpinfo() ?> -->

    <table id="foo-table" Class="table table-striped">
        <?php
            echo '<thead><tr>';
            echo '<th>最終更新日</th>';
            echo '<th>商品コード</th>';
            echo '<th>在庫数</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            if(isset($result)){
                foreach ($result as $value) {
                    echo '<tr>';
                    echo '<td>' . $value['stock_last_modified_date'] . '</td>';
                    echo '<td>' . $value['stock_goods_id'] . "</td>";
                    echo '<td class="text-right">' . $value['stock_quantity'] . '</td>';
                    echo '</tr>';
                }
            }
        echo '</tbody>';
        ?>
    </table>
    <!-- これ使えた<form method='show_log' style='margin: 5px 0px 5px;'>  -->
    <table id="foo-table2" Class="table table-striped">
        <?php
            echo '<thead><tr>';
            echo '<th style="width: 20%;">アップロード日</th>';
            echo '<th style="width: 30%;">ファイル名</th>';
            echo '<th style="width: 50%;" />';
            echo '</tr></thead>';
            echo '<tbody>';
            if(!empty($uploadFiles)){
                echo "<input name='fileOpen' style='visibility: hidden;' value='showFile'/>";
                $file_i= 0;
                foreach ($uploadFiles as $value) {
                    //表示ボタン横並び
                    //post、ファイル情報、同一フォーム
                    echo "<tr id='fileRow_{$file_i}'  >".
                            "<td style='vertical-align: middle;'>" . $value['date'] . "</td>".
                            "<td style='vertical-align: middle;' aria-expanded='false' data-toggle='collapse' data-target='#setFile{$file_i}' aria-controls='setFile{$file_i}'>".
                                "<input id='file_{$file_i}' name='data[open_{$file_i}]' value='{$value['file']}' readonly style='width: 100%; background-color: unset; border: none; display:inline-block;'></input>".
                            "</td>".
                            "<td>".
                                $this->Form->botton("{$file_i}", ['id' => "btn_file_{$file_i}", 'type'=>'button','class'=>'btn btn-primary', 'display'=>'inline-block', 'value' => "表示", 'onClick' => 'showFile(arguments[0])' ]).
                                "<div class='row collapse m-3' >
                                    <div class='card card-body' id='fileView_{$file_i}'></div>
                                </div>".
                            "</td>".
                        "</tr>";
                    $file_i++;
                }
            }
            echo '</tbody>';
        ?>
    </table>
    <script type="text/javascript">
        
        function zozoOn() {
            $("#setcollapse").find(".form-group").not(":eq(0),:eq(1)").toggle()
        }
        function changeList(e) {
            var id = "#" + e.target.id;
            console.log("");
            var list = JSON.parse('<?php echo json_encode($default); ?>');
            var template = "<option value=\"\">指定なし</option>" +
                            "<option value=\"stock_free_quantity\">フリー在庫</option>"+
                            "<option value=\"stock_quantity\">在庫数</option>"+
                            "<option value=\"stock_allocation_quantity\">引当数</option>"+
                            "<option value=\"stock_defective_quantity\">不良在庫数</option>"+
                            "<option value=\"stock_remaining_order_quantity\">発注残数</option>"+
                            "<option value=\"stock_out_quantity\">欠品数</option>"+
                            "<option value=\"stock_advance_order_quantity\">予約在庫数</option>"+
                            "<option value=\"stock_advance_order_allocation_quantity\">予約引当数</option>"
                            "<option value=\"stock_advance_order_free_quantity\">予約フリー在庫数</option>";

            var selectList = $("#calcSet").find(".calcItem");
            
            var selectedList = selectList.find("option:selected");
            var not_option = $.map(selectedList, function(el) {
                if ($(el).val() != "") {
                    return "[value='" + $(el).val() + "']";
                }
            });
            selectList.find("option:not(:selected)").filter(not_option.join(",")).remove();
            lastRow.after(htmlFormat);  //追加実行
            //console.log(not_option);


            for (let key in hash) {
                alert('key:' + key + ' value:' + hash[key]);
            }
        }
        /*function changeList(e) {
            var i = e.target.id.replace("calcItem_","") - 0;
            var id = "#" + e.target.id;
            var list = JSON.parse('<?php //echo json_encode($default); ?>');
            var selectedList = $("#calcSet").find(".calcItem option:selected")
            var not_option = $.map(selected, function(el) {
                if ($(el).val() != "") {
                    return "[value='" + $(el).val() + "']";
                }
            });
            $("#calcSet").find(".calcItem option:not(:selected)").filter(not_option.join(",")).remove();
            
            //console.log(not_option);
        }*/

        //計算式の行追加　リストボックスの最終行で動作
        function addRow(e) {
            var list = JSON.parse('<?php echo json_encode($default); ?>');
            
            $("#calcCnt").val(($("#calcCnt").val()-0) + 1);
            var calcSet = $("#calcSet");
            var lastRow = calcSet.find(".calcRow:last");
            var len = (lastRow.attr("id").replace("calcDiv_","")-0) + 1;
            
           // var arr = $.grep()
            var htmlFormat = lastRow.prop('outerHTML'); //最終行を取得
            //追加想定のhtmlに置換
            htmlFormat = htmlFormat.replaceAll("calcDiv_"+ (len - 1), "calcDiv_" + len)
                        .replaceAll("addBtn_"+ (len - 1), "addBtn_" + len)
                        .replaceAll("sign_"+ (len - 1), "sign_" + len)
                        .replaceAll("calcItem_"+ (len - 1), "calcItem_" + len);
            lastRow.after(htmlFormat);  //追加実行

            //新しいエレメント作成後、再取得
            var arr = $("#calcSet").find(".calcItem:not(:last)").map(function (index, el) {
                return $(this).val();
            });

            var not_option = $.map($("#calcSet").find(".calcItem option:selected"), function(el) {
                if ($(el).val() != ""){
                    return "[value='" + $(el).val() + "']";
                }
            });
            // not_option = $("#calcSet").find(".calcItem option:selected").map(function (index, el) {
            //     return "[value='" + $(this).val() + "']";
            // });
            /*var not_option = function(){
                $("#calcSet").find(".calcItem option:selected");
                var str = "";
                for (var val of arr){
                    str += "[value='" + val + "'],";
                }
                str = str.slice( 0, -1);
                return str;
            }();*/
            //console.log(".calcItem:last option:not(" + not_option.join(",") + ")");
            $("#calcSet").find(".calcItem:last").find("option"+ not_option.join(",")).remove()


            var newlast = $("#calcSet").find(".calcRow:last>select"); 
            newlast[0].value = "0";
            newlast[1].value = "";
            //newlast[0].val("");
            //newlast[0].val("0");

            $(e.target).unbind("click").css("visibility","hidden");

            addBtnSet();
            //console.log(html);
        }
        function showFile(e) {
            var id = "#" + e.target.id;
            var view_id = id.replace("btn_file_","fileView_");
            var file_id = id.replace("btn_file_","file_");

            var btnVal = $(id).val();
            var fileName = $(file_id).val();

            if (btnVal == "表示") {
                //ファイル内容展開
                $(id).val("閉じる");
                $(view_id).parent().addClass("show");

                $.ajax({type: "POST",
                    url: "https://nextengine.ec-masters.net/zozo/show_log",
                    global:false,
                    dataType: "html",
                    data: "fileName=" + fileName,
                    success: function(msg) {
                        $(view_id).html(msg);
                    }
                });

            } else {
                //ファイル内容閉塞
                $(id).val("表示");
                $(view_id).parent().removeClass("show");
                $(view_id).text("");
            }

        }
        function addBtnSet() {
            var last = $("#calcSet").find(".calcRow:last");
            var cnt = last.attr("id").replace("calcDiv_","");
            if ( cnt < 4 ) {
                $(last).find(".addBtn").bind("click", addRow).css("visibility","unset");
            } else if (cnt >= 4) {
                $(last).find(".addBtn").unbind("click").css("visibility","hidden");
                //$(e.target).unbind("click").css("border","none").text("");
            }
        }
        if ($("#zozo_flag").val() == "0"){
            zozoOn();
        }
        addBtnSet();
        //$("#calcCnt").parent().find(".addBtn:last").bind("change", addRow);
    </script>
    <!-- <h5 class="text-left">ログが表示される予定です。</h5> -->
</div>