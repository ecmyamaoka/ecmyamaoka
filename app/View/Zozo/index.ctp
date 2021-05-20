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
    #foo-table2 td:nth-of-type(2) input {
        width: 100%;
        background-color: unset;
        border: none;
    }
    .upd {
        color: red;
        font-weight: bold;
    }
    #setItemsBtn {
        margin-top:10px;
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
        <div class="col-md-5">
        <!-- <h5 class="text-left"><?php //echo $datetime->format("Y/m/d H:i:s"); ?> 以降の更新在庫</h5> -->
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-1">
            <button type="button" class="btn btn-success" type="button" data-toggle="collapse" data-target="#setcollapse" aria-expanded="false" aria-controls="setcollapse">ZOZO 設定</button>
            <button id="setItemsBtn" type="button" class="btn btn-success" type="button" onclick="location.href='<?php echo $this->html->url('./setItems');?>';">セット商品情報</button>
        </div>
    </div>
    
    <div class="row collapse m-3 <?php echo ($show) ? "show" : ""; ?>" id="setcollapse">
        <div class="col-md-10">
            <?php if ($zozoUp_comment){ echo "<p class='upd'>{$zozoUp_comment}</p>"; } ?>
            <form method="post" action='zozo/submit' enctype="multipart/form-data" class="row">
                <div class="card card-body col-md-4" style="min-width: 260px;">
                    <div class="form-group">
                        <!-- コメントアウト
                            <label for="exampleInputEmail1">Email address</label>
                            <?php echo $this->Form->text('email', ['id' => 'InputEmail',
                                                        'class' => 'form-control',
                                                        'aria-describedby' => 'emailHelp',
                                                        'placeholder'=> 'APIキー', $disabled]); ?>
                            <?php echo $this->Form->text('access_token',['id' => 'InputAccessToken',
                                                        'class' => 'form-control',
                                                        'placeholder'=> 'access token', $disabled]); ?>
                            <?php echo $this->Form->text('refresh_token',['id' => 'InputRefreshToken',
                                                        'class' => 'form-control',
                                                        'placeholder'=> 'refresh token', $disabled]); ?> 
                        コメントアウト　ここまで    -->
                    </div>

                    <div class="form-group">
                        <label for="exampleInputZozoFlag">ZOZO有効フラグ</label><br>
                        <?php echo $this->Form->select('zozo_flag', 
                                                    ['0'=>'無効', '1'=>'有効', '2'=>'テスト'], 
                                                    ['empty'=>false,
                                                    'value'=> $zozo_flag,
                                                    'class' => 'form-control', $disabled,
                                                    'onChange' => 'zozoOn(arguments[0])']); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputShopNumber">Shop Number</label>
                        <?php echo $this->Form->text('shopNm',['id' => 'InputShopNumber',
                                                    'class' => 'form-control',
                                                    'placeholder'=> 'Shop Number',
                                                    'value'=> $shopNm, $disabled]); ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputZozoId">ZOZO ID</label>
                        <?php echo $this->Form->text('zozo_id',['id' => 'InputZozoId',
                                                    'class' => 'form-control',
                                                    'placeholder'=> 'ZOZO ID',
                                                    'value'=> $zozo_id, $disabled]);
                                                    ?>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <?php echo $this->Form->password('password',['id' => 'InputPassword',
                                                    'class' => 'form-control',
                                                    'placeholder'=> 'Password', $disabled]); ?>
                    </div>
                </div>

                <div class="card card-body col-md-4" style="min-width: 260px;">
                    <div class="form-group"></div>
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
                                                    ['0'=>'切り捨て', '1'=>'切り上げ'], 
                                                    ['empty'=>false,
                                                    'value'=> $roundup,
                                                    'class' => 'form-control', $disabled]); ?>
                    </div>
                    <!-- コメントアウト外すと使用可能
                        <div class="form-group">
                        <label for="exampleInputAsurakuClearFlag">あす楽解除フラグ(在庫0商品)</label><br>
                        <?php echo $this->Form->select('asuraku_clear_flag', 
                                                    ['0'=>'無効', '1'=>'有効'], 
                                                    ['empty'=>false,
                                                    'value'=> $asuraku_clear_flag,
                                                    'class' => 'form-control', $disabled]); ?>
                    </div> -->
                    <div class="form-group" id="calcSet">
                        <input id="calcCnt" name='calcCnt' value="<?php echo count($calcSet); ?>" ></input><br>
                        <label>計算式</label><br>
                        <?php   $view_i = 0;
                                $cnt = count($calcSet);
                                if ($disabled || empty($calcSet))  {
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
                                    echo "<div id='calcDiv_{$view_i}' class='calcRow'>";
                                    echo $this->Form->button('　+　',
                                                            ['id' => "addBtn_{$view_i}",
                                                            'type'=> 'button',
                                                            'empty'=> false,
                                                            'value'=> ' ',
                                                            'class' => 'form-control addBtn',
                                                            'style'=>'visibility: hidden;',
                                                            $disabled]);
                                    //表示ボタン横並び
                                    //post、ファイル情報、同一フォーム
                                    echo $this->Form->select('sign_'.$view_i,
                                                            ['0'=>'+', '1'=>'-'], 
                                                            ['empty'=> false,
                                                            'value'=> $set['sign_'.$view_i],
                                                            'class' => 'form-control sign',
                                                            $disabled]);
                                    echo $this->Form->select('calcItem_'.$view_i,
                                                            $list,
                                                            ['empty'=>[''=>'指定なし'],
                                                            'value'=> $set['calcItem_'.$view_i],
                                                            'class' => 'form-control calcItem',
                                                            'onChange' => 'changeList()',
                                                            //'onChange' => '',
                                                            $disabled]);
                                    echo "</div>";
                                    $view_i++;
                            } ?>
                            <br>
                        </div>
                        <?php if (!empty($disabled)) echo "<span id='not_company'>企業名が不明なため、保存できません。</span>"; ?><br>
                            <?php
                                echo $this->Form->submit('保存', ['class'=>'btn btn-primary','style'=>'width: 100px;', ($disabled) ? "disabled" : ""]);
                            ?>
                    </div>
            </form>
        </div>
    </div>
    
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

    <table id="foo-table2" Class="table table-striped">
        <?php
            echo '<thead><tr>';
            echo '<th style="width: 20%;">アップロード日</th>';
            echo '<th style="width: 40%;">ファイル名</th>';
            echo '<th style="width: 40%;" />';
            echo '</tr></thead>';
            echo '<tbody>';
            if(!empty($uploadFiles)){
                echo "<input name='fileOpen' style='visibility: hidden;' value='showFile'/>";
                $file_i= 0;
                foreach ($uploadFiles as $value) {
                    //表示ボタン横並び
                    //post、ファイル情報、同一フォーム
                    echo "<tr id='fileRow_{$file_i}' >".
                            "<td style='vertical-align: middle;'>{$value['date']}</td>".
                            "<td id='file_{$file_i}' style='vertical-align: middle;' aria-expanded='false' data-toggle='collapse' data-target='#setFile{$file_i}' aria-controls='setFile{$file_i}'>".
                                //"<input id='file_{$file_i}' name='data[open_{$file_i}]' value='{$value['file']}' readonly></input>".
                                "{$value['file']}".
                            "</td>".
                            "<td>".
                                $this->Form->botton("{$file_i}", 
                                                    ['id' => "btn_file_{$file_i}",
                                                     'type'=>'button',
                                                     'class'=>'btn btn-primary',
                                                     'value' => "表示",
                                                     'onClick' => 'showFile(arguments[0])' ]).
                                "<div class='row collapse m-3 card card-body' id='fileView_{$file_i}'></div>".
                                // "<div class='row collapse m-3' >
                                //     <div class='card card-body' id='fileView_{$file_i}'></div>
                                // </div>".
                            "</td>".
                        "</tr>";
                    $file_i++;
                }
            }
            echo '</tbody>';
        ?>
    </table>
    <template id="calcOption">
        <option value="">指定なし</option>
        <option value="stock_free_quantity">フリー在庫</option>
        <option value="stock_quantity">在庫数</option>
        <option value="stock_allocation_quantity">引当数</option>
        <option value="stock_defective_quantity">不良在庫数</option>
        <option value="stock_remaining_order_quantity">発注残数</option>
        <option value="stock_out_quantity">欠品数</option>
        <option value="stock_advance_order_quantity">予約在庫数</option>
        <option value="stock_advance_order_allocation_quantity">予約引当数</option>
        <option value="stock_advance_order_free_quantity">予約フリー在庫数</option>
    </template>
        
    <script type="text/javascript">
        //Zozoの無効、有効（有効 or テスト）切替時の表示設定
        function zozoOn(e) {
            var toggleEle  = $("#setcollapse").find(".form-group");
            var isHidden = (toggleEle.is(":hidden")) ? 0 : 1;
            var val = e.target.value - 0;

            if (isHidden * val == 0 ) {
                toggleEle.not(":eq(0),:eq(1)").toggle();
            }
        }

        //計算項目変更時のイベント
        function changeList(val) {
            var selectList = $("#calcSet").find(".calcItem :selected").toArray();
            var temp = $("#calcOption").html().trim();
            var _val = ""; _val2 = "";
            if (val == null) {
                //計算項目のリスト全体を更新
                for (var selected of selectList) {　//選択を設定する処理
                    var select = $(selected).parent();
                    _val = selected.value;
                    select.html(temp).val(_val); //リスト作り直して選択

                    for (var selected2 of selectList) { //不要なリストを除外する処理
                        _val2 = selected2.value;
                        if (selected2.value == ""　|| _val == selected2.value ) {
                            //"指定しない"と自分が選択している項目は除外しない
                            continue;
                        }
                        select.find("[value='" + _val2 + "']").addClass("hidden"); //他で選択している項目を削除
                    }
                }
            } else if (val == "") {
                //add用　新たに作成されるボックスのリストだけ作成
                var newSelect = $("#calcSet").find(".calcItem:last");
                for (var selected of selectList) {
                    _val = selected.value;
                    if (_val == "") { continue; }
                    newSelect.find("[value='" + _val + "']").addClass("hidden"); //他で選択している項目を削除
                }
            }
        }
        
        //計算式の行追加　リストボックスの最終行で動作
        function addRow(e) {
            var list = JSON.parse('<?php echo json_encode($default); ?>');
            
            $("#calcCnt").val(($("#calcCnt").val()-0) + 1);
            var calcSet = $("#calcSet");
            var lastRow = calcSet.find(".calcRow:last");
            var len = (lastRow.attr("id").replace("calcDiv_","")-0) + 1;
            
            var htmlFormat = lastRow.prop('outerHTML'); //最終行を取得
            //追加想定のhtmlに置換
            htmlFormat = htmlFormat.replaceAll("calcDiv_"+ (len - 1), "calcDiv_" + len)
                        .replaceAll("addBtn_"+ (len - 1), "addBtn_" + len)
                        .replaceAll("sign_"+ (len - 1), "sign_" + len)
                        .replaceAll("calcItem_"+ (len - 1), "calcItem_" + len);
            lastRow.after(htmlFormat);  //追加実行

            //計算項目のリストの構築
            changeList("");

            var newlast = $("#calcSet").find(".calcRow:last>select"); 
            newlast[0].value = "0";
            newlast[1].value = "";
            
            //新規行をスペースに変更
            $(e.target).unbind("click").css("visibility","hidden");
            //追加ボタンの設定
            addBtnSet();
        }

        // ファイル内容を読み込み
        function showFile(e) {
            var btn = $(e.target);
            var view = btn.next();
            var fileTd = btn.parent().prev();
            
            var btnVal = btn.val();
            var fileName = fileTd.text();
            if (btnVal == "表示") {
                //ファイル内容展開
                btn.val("閉じる");
                view.addClass("show");
                
                $.ajax({type: "POST",
                    url: "https://nextengine.ec-masters.net/zozo/show_log",
                    global: false,
                    dataType: "html",
                    data: "fileName=" + fileName,
                    success: function(msg) {
                        view.html(msg);
                    }
                });

            } else {
                //ファイル内容閉塞
                btn.val("表示");
                view.removeClass("show").text("");;
            }
        }

        // 追加ボタン作成
        function addBtnSet() {
            var last = $("#calcSet").find(".calcRow:last");
            var cnt = last.attr("id").replace("calcDiv_","");
            if ( cnt < 4 ) {
                //通常の追加ボタン
                $(last).find(".addBtn").bind("click", addRow).css("visibility","unset");
            } else if ( cnt >= 4 ) {
                //最終行はスペースだけ作成
                $(last).find(".addBtn").unbind("click").css("visibility","hidden");
            }
        }

        //初期表示の際の処理
        (function() {
            if ($("#zozo_flag").val() == "0") {
                zozoOn({ "target" : $("#zozo_flag").get(0) });
            }
            changeList();
            addBtnSet();
        }());
        
        
        //$("#calcCnt").parent().find(".addBtn:last").bind("change", addRow);
    </script>
    <!-- <h5 class="text-left">ログが表示される予定です。</h5> -->
</div>