<?php

/**
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Pages
 * @since         CakePHP(tm) v 0.10.0.1076
 */

// if (!Configure::read('debug')):
// 	throw new NotFoundException();
// endif;

// App::uses('Debugger', 'Utility');

$this->assign('title', 'セット商品販売レポート 概要');

?>
<style>
    h3 {
        margin-top: 20px
    }

    body {
        font-size: 16px;
    }

    li.no-li-indent {
        list-style: none;
        margin-left: -20px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <h1>仕入れ先コード 追加</h1>
        <div class="form-group">
            <form method="post" action="/Salesreport/upload" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-4">
                        <input type="file" name="file" ><!-- class="custom-file-input" -->
                        
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary">アップロード</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
    // File APIに対応していない場合はエリアを隠す
    if (!window.File) {
        document.getElementById('image_upload_section').style.display = "none";
    }

    // ブラウザ上でファイルを展開する挙動を抑止
    function onDragOver(event) {
        event.preventDefault();
    }

    // Drop領域にドロップした際のファイルのプロパティ情報読み取り処理
    function onDrop(event) {
        // ブラウザ上でファイルを展開する挙動を抑止
        event.preventDefault();
        // ドロップされたファイルのfilesプロパティを参照
        var files = event.dataTransfer.files;
        for (var i = 0; i < files.length; i++) {
            // 一件ずつアップロード
            imageFileUpload(files[i]);
        }
    }

    // ファイルアップロード
    function imageFileUpload(f) {

        var formData = new FormData();
        formData.append('file', f);
        $.ajax({
            type: 'POST',
            contentType: false,
            processData: false,
            url: '/Salesreport/upload',
            data: formData,
            dataType: 'csv',
            success: function(data) {
                console.log("再読み込み");
                // メッセージ出したり、DOM構築したり。
            }
        });
    }
</script>