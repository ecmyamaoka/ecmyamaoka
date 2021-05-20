<?php

$this->assign('title', 'FTPAuto君');
?>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?php if ($navi == 0) {
                                echo 'active';
                            } ?>" href="/ftp/index/0">楽天CSV</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if ($navi == 1) {
                                echo 'active';
                            } ?>" href="/ftp/index/1">楽天GOLD</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if ($navi == 5) {
                                echo 'active';
                            } ?>" href="/ftp/idregist">設定</a>
    </li>
</ul>

<div class="row">
    <div class="col-sm-4">
        日付、時間を設定後、ファイルをドラッグアンドドロップしてください。
        <div style="overflow:hidden;">
            <div class="form-group">
                <div class="row">
                    <!-- <div class="col-md-6"> -->
                    <!-- <div id="timepicker-actions" class="datepicker-here" data-time-format='hh:ii aa' data-timepicker="true"></div> -->
                    <!-- <div class="datepicker-here" data-timepicker="true" data-time-format='hh:ii aa'></div> -->
                    <!-- <input type='text' class='datepicker-here' data-language='en' /> -->
                    <!-- </div> -->
                    <div class="col-md-12">
                        <form class="form-inline">
                            <div class="form-group">
                                <label class="mr-3" for="uploadlabel">アップロード予定日時:</label>
                                <input type="text" id="timepicker-actions" class="datepicker-here form-control mb-1" readonly>
                            </div>
                        </form>
                    
                    <div id="image_upload_section">

                        <div id="drop" class="form-control" style=" height:280px; padding:10px; border:1px solid;border-color: #d7d7d7;background-color: #f8f8f8" ondragover="onDragOver(event)" ondrop="onDrop(event)">
                            <div style="vertical-align:middle;margin: 80px 5px 80px 5px;">ファイルをドラッグアンドドロップしてください。複数ファイル同時も対応しています。</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- http://t1m0n.name/air-datepicker/docs/ -->
        <script>
            reloadfilelist();

            function reloadfilelist() {
                $('#uplist tbody tr').remove();
                $.ajax({
                    type: 'GET',
                    url: '/ftp/filetree',
                    dataType: 'json',
                    success: function(json) {
                        var len = json.length;
                        for (var i = 0; i < len; i++) {
                            $('#uplist tbody').append('<tr><td><i class="far fa-file-alt mr-md-2"></i> ' + json[i].fname + '</td><td>' + json[i].fdate + '</td><td class="text-right"><button type="button" class="btn btn-danger btn-sm">取消</button></td></tr>');
                        }
                    }
                });
            }
        </script>


        <script>
            var now = new Date();
            var c = Math.floor(Math.round(now.getTime() / 1000) / 600);
            var d = (c + 1) * 600;
            var dd = unixTime2ymd(d);

            $('#timepicker-actions').val(formatDate(dd, 'yyyy/MM/dd HH:mm'));

            $('#timepicker-actions').datepicker({
                timepicker: true,
                language: 'ja',
                minutesStep: 10,
                timeFormat: 'hh:ii',
                data: dd,
                onSelect: function onSelect(fd, date) {
                    $('#timepicker-actions').val(formatDate(date, 'yyyy/MM/dd HH:mm'));
                }
            });

            $('#timepicker-actions').data('datepicker').selectDate(dd);


            function unixTime2ymd(intTime) {

                var d = new Date(intTime * 1000);
                var year = d.getFullYear();
                var month = d.getMonth() + 1;
                var day = d.getDate();
                var hour = (d.getHours() < 10) ? '0' + d.getHours() : d.getHours();
                var min = (d.getMinutes() < 10) ? '0' + d.getMinutes() : d.getMinutes();
                var sec = (d.getSeconds() < 10) ? '0' + d.getSeconds() : d.getSeconds();
                var d1 = new Date(year + '-' + month + '-' + day + ' ' + hour + ':' + min + ':' + sec);

                return d1;
            }

            function formatDate(date, format) {
                format = format.replace(/yyyy/g, date.getFullYear());
                format = format.replace(/MM/g, ('0' + (date.getMonth() + 1)).slice(-2));
                format = format.replace(/dd/g, ('0' + date.getDate()).slice(-2));
                format = format.replace(/HH/g, ('0' + date.getHours()).slice(-2));
                format = format.replace(/mm/g, ('0' + date.getMinutes()).slice(-2));
                format = format.replace(/ss/g, ('0' + date.getSeconds()).slice(-2));
                format = format.replace(/SSS/g, ('00' + date.getMilliseconds()).slice(-3));
                return format;
            };
        </script>



        <!-- <script type="text/javascript">
                $(function() {
                    $('#datetimepicker12').datetimepicker({
                        format: "YYYY/MM/DD HH:mm",
                        locale: 'ja',
                        stepping: 10,
                        inline: true,
                        sideBySide: true
                    });
                    var datePicker = $('#datetimepicker12');
                    datePicker.change(function(e){
                    console.log('log:' + datePicker.val() );
                    });
                });
            </script> -->
    </div>
</div>
<div class="col-sm-8">
    <?php
    //リスト表示
    if ($status == true) {
        echo '<table id="uplist" class="table table-striped">';
        echo '<thead><tr><th scope="col">一覧</th><th scope="col">アップロード予定時間</th>';
        echo '<th scope="col" class="text-right">取消</th></tr></thead>';
        echo '<tbody>' . PHP_EOL;
        echo '</tbody>' . PHP_EOL;
        echo '</table>' . PHP_EOL;
    } else {
        echo '<a class="btn btn-success" href="/ftp/idregist">こちらから利用設定を行ってください。</a>';
    }

    // echo 'uid:' . $user['uid'] . '<br>';
    // echo 'status:' . $user['status'] . '<br>';
    // echo 'expiredate:' . $user['expiredate'] . '<br>';
    // echo 'servicehash:' . $user['servicehash'] . '<br>';
    // echo 'loginidstr:' . $user['loginidstr'] . '<br>';
    // echo 'sname:' . $user['sname'] . '<br>';
    // echo 'cname:' . $user['cname'] . '<br>';
    // echo 'plan:' . $user['plan'] . '<br>';
    ?>
</div>

</div>

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

        //console.dir(f);

        //var datepicker = $('#timepicker-actions').val();
        //datepicker.data = new Date();

        //console.log($('#timepicker-actions').val());

        //formData.append('ftile', datepicker.date);
        formData.append('filetime', $('#timepicker-actions').val());
        formData.append('file', f);
        $.ajax({
            type: 'POST',
            contentType: false,
            processData: false,
            url: '/ftp/upload',
            data: formData,
            dataType: 'json',
            success: function(data) {
                console.log("再読み込み");

                reloadfilelist();
                // メッセージ出したり、DOM構築したり。
            }
        });
    }
</script>