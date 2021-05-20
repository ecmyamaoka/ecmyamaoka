<?php
use Aws\PhpHash;

date_default_timezone_set('Asia/Tokyo');
$this->assign('title', 'タグマネージャー(R) 登録');
?>
<style>
h5, .h5 {
  font-size: 12px;
  font-weight: 600;
}
h3{
  font-size: 0.9375rem;;
  font-weight: 400;
  /* display:none; */
}

input[type='checkbox'] {
  transform: scale(1.5);
  margin: 5px 10px 0 0;
}

.media-body{
  font-size: 9px;
}
.formhidden{
  display:none;
}

.inventory-table {
  margin-bottom: 0px;
}
.btn-group>.btn:not(:last-child):not(.dropdown-toggle), .btn-group>.btn-group:not(:last-child)>.btn {
  border-top-right-radius: 0.25rem !important;
  border-bottom-right-radius: 0.25rem !important;
}
.btn-group>.btn:not(:first-child), .btn-group>.btn-group:not(:first-child)>.btn {
  border-top-left-radius: 0.25rem !important;
  border-bottom-left-radius: 0.25rem !important;
}

</style>
<style>
.list-group{
  max-height: 70vh;
  margin-bottom: 10px;
  overflow:scroll;
  -webkit-overflow-scrolling: touch;
}
</style>

<div class="row margin20">
  <!-- 左 -->
  <div class="col-lg-3 col-md-3 col-sm-3">
    <div class="taglist">
      <h4>タグタイプを選択</h4>
      <div class="list-group">
        <?php foreach ($plugin as $value) { ?>
          <a href="#" data-id='<?php echo $value['id'] ?>' class="list-group-item list-group-item-action">
            <div class="media">
              <img class="bd-placeholder-img mr-3" width="48" height="48" src="<?php echo $value['icon'] ?>">
              <div class="media-body">
                <h5 class="mt-0"><?php echo $value['PluginName'] ?></h5>
                <?php echo $value['description'] ?>
              </div>
            </div>
          </a>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="formhidden">
      <?php
      echo  $this->Form->create(
        false,
        array('url' => array('action' => 'regist'), 'type' => 'post')
      );
      ?>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <label for="colFormLabel" class="col-sm-12 col-form-label">タグ名</label>

          <?php
          echo $this->Form->input('tagname', array(
            'label' => false,
            'div' => array(
              'class' => 'form-group row'
            ),
            'value' => $eventdt['tagname'],
            'between' => '<div class="col-sm-12">',
            'after' => '</div>',
            'class' => 'form-control',
            'placeholder' => 'タグ名(英数のみ)',
            'required' => true
          ));
          ?>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <label for="colFormLabel" class="col-sm-12 col-form-label">タグラベル(備考)</label>

          <?php
          echo $this->Form->input('etc', array(
            'label' => false,
            'div' => array(
              'class' => 'form-group row'
            ),
            'value' => $eventdt['etc'],
            'between' => '<div class="col-sm-12">',
            'after' => '</div>',
            'class' => 'form-control ',
            'placeholder' => '掲載場所などをメモしてください。'
          ));
          ?>
        </div>
      </div>

      <!-- カスタムタグ -->
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div id="editor_holder"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <?php
          if (isset($message)) {
            echo '<div class="alert alert-dismissible alert-success margin05">';
            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo $message;
            echo '</div>';
          }
          ?>
        </div>
      </div>
      <!-- エラーメッセージ -->
      <div id="errors"><ul id="error-messages" style="color: red;"></ul></div>

      <div class="row margintop20">
        <!-- offset-lg-4  offset-md-4 -->
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="bs-component text-center">
            <input type="hidden" name="plugvalue" value="">
            <input type="hidden" name="plugintype" value="">
            <!-- <a href="#" id="jsoninput" class="btn btn-outline-secondary">入力</a> -->
            <?php
            // echo $this->Form->submit('登録', array(
            //     'div' => false,
            //     'class' => 'btn btn-primary col-lg-3 col-md-3 col-sm-12'
            // ));
            ?>
            <a href="#" id="senddata" class="btn btn-primary col-lg-3 col-md-3 col-sm-12">登録</a>
            <a href="#" id="preview" class="btn btn-outline-secondary col-lg-3  col-md-3 col-sm-12">プレビュー</a>
            <?php
            //echo $this->Html->link('キャンセル', 'index', array('class' => 'btn btn-outline-secondary col-lg-3 col-md-3 col-sm-12'));
            ?>
            <?php
            // echo $this->Html->link('削除', 'del?id=' . $eventdt['id'],
            // array('class' => 'btn btn-outline-secondary col-lg-3 col-md-3 col-sm-12',
            // 'title'=>'削除'),
            // 'タグを削除します。よろしいですか？'
            // );
            ?>

          </div>
        </div>
      </div>
      <?php
      echo $this->Form->end();
      ?>
    </div>
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3">
    <div class="formhidden">
      <h4>プレビュー</h4>
      <div id="editor_preview"></div>
      <div id="shopName"></div>
      <div id="itemImageSlider"></div>
    </div>
  </div>
  <script>
  // var now = new Date();
  // var c = Math.floor(Math.round(now.getTime() / 1000) / 600);
  // var d = (c + 1) * 600;
  // var dd = unixTime2ymd(d);

  // $('#timepicker-st').val(formatDate(dd, 'yyyy/MM/dd HH:mm'));
  // $('#timepicker-st').datepicker({
  //     timepicker: true,
  //     language: 'ja',
  //     minutesStep: 10,
  //     timeFormat: 'hh:ii',
  //     data: dd,
  //     onSelect: function onSelect(fd, date) {
  //         $('#timepicker-st').val(formatDate(date, 'yyyy/MM/dd HH:mm'));
  //     }
  // });
  // $('#timepicker-st').data('datepicker').selectDate(dd);

  // $('#timepicker-ed').val(formatDate(dd, 'yyyy/MM/dd HH:mm'));
  // $('#timepicker-ed').datepicker({
  //     timepicker: true,
  //     language: 'ja',
  //     minutesStep: 10,
  //     timeFormat: 'hh:ii',
  //     data: dd,
  //     onSelect: function onSelect(fd, date) {
  //         $('#timepicker-ed').val(formatDate(date, 'yyyy/MM/dd HH:mm'));
  //     }
  // });
  // $('#timepicker-ed').data('datepicker').selectDate(dd);



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


<script type="text/javascript">
$(function(){
  var editor;

  $('#backnavi').show();

  $(document).keyup(function(e) {
    if (e.keyCode == 27) { // Esc
      $("#editor_preview").empty();
    }
  });

  JSONEditor.defaults.custom_validators.push(function(schema, value, path) {
    var errors = [];
    if(schema.format==="number") {
      if(!/^[0-9]+$/.test(value) && value.length != 0) {
        // Errors must be an object with `path`, `property`, and `message`
        errors.push({
          path: path,
          property: 'format',
          message: '半角整数字のみを入力してください'
        });
      }
    }
    return errors;
  });

  $('#senddata').on('click', function() {
    var value = editor.getValue();
    var json =JSON.stringify(value,null,2);
    var encstr = window.btoa(unescape(encodeURIComponent(json)));
    console.log(encstr);
    $('input:hidden[name="plugvalue"]').val(encstr);

    $('#error-messages').empty();

    var errors = editor.validate();
    if(errors.length) {
      $.each(errors, function(index, value) {
        $('#error-messages').append(`<li>${value['message']}</li>`);
      });
      return false;
    }

    var dateflg = $('input:checkbox[name="root[dateflg]"]').prop("checked");
    if(dateflg) {
      var startime = $('input[name="root[starttime]"]').val();
      var endtime = $('input[name="root[endtime]"]').val();

      if(startime === '') {
        $('#error-messages').append(`<li>開始日時の値が入力されていません。</li>`);
        return false;
      }
      if(endtime === '') {
        $('#error-messages').append(`<li>停止日時の値が入力されていません。</li>`);
        return false;
      }
    }

    $('#registForm').submit();
  });

  $('#preview').on('click', function() {
    var date = new Date() ;
    var value = editor.getValue();

    $("#editor_preview").empty();
    $("#shopName").empty();
    $("#itemImageSlider").empty();
    const tag = $('input:hidden[name="plugintype"]').val();
    var encstr = window.btoa(unescape(encodeURIComponent(JSON.stringify(value,null,2))));
    const url = "/tag/makehtml";
    $.post("/tag/makehtml",{"t":tag, "j":encstr, "s":date.getTime()},
    function(dt){
      $('#editor_preview').html(dt);
    }
  );
  return false;
});

$('.list-group a').on('click', function() {
  var date = new Date() ;
  var click =  $(this).data('id');
  const url = "/tag/template?t=" + $(this).data('id') + '&s=' + date.getTime();
  $.getJSON(url)
  .done(function(data1,textStatus,jqXHR) {
    const data2 = JSON.stringify(data1);
    json = JSON.parse(data2);
    $('.formhidden').fadeIn();
    $('input:hidden[name="plugintype"]').val(json.id);
    if(json.preview=='on'){
      $('#preview').show();
    }else{
      $('#preview').hide();
    }

    $("#editor_holder").empty();
    JSONEditor.defaults.options.theme = 'bootstrap3';
    editor = new JSONEditor(document.getElementById('editor_holder'),{
      disable_collapse: true,
      disable_edit_json: true,
      disable_properties: true,
      schema: json.form,
      iconlib: "fontawesome4"
    });
    //$('.formhidden').fadeIn();
  })
  return false;

});
});
</script>
