<?php
use Aws\PhpHash;

date_default_timezone_set('Asia/Tokyo');
$this->assign('title', 'タグマネージャー(R) 登録');
?>
<style>
h5, .h5 {
  font-size: 16px;
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

.previewhidden{
  display:none
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

.media-body{
  font-size: 12px;
}
.formhidden{
  display:none;
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

<div class="row margin20 justify-content-md-center">
  <div class="col-lg-7 col-md-7 col-sm-7">

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
          <input type="hidden" name="id" value="<?php echo $eventdt['id']; ?>" />
          <!-- <a href="#" id="jsoninput" class="btn btn-outline-secondary">入力</a> -->
          <!-- <a href="#" id="jsonoutput" class="btn btn-outline-secondary">出力</a> -->
          <?php
          // echo $this->Form->submit('登録', array(
          //     'div' => false,
          //     'class' => 'btn btn-primary col-lg-3  col-md-3 col-sm-12'
          // ));
          ?>
          <a href="#" id="senddata" class="btn btn-primary col-lg-3 col-md-3 col-sm-12">登録</a>
          <a href="#" id="preview" class="btn btn-outline-secondary col-lg-3  col-md-3 col-sm-12">プレビュー</a>
          <?php
          echo $this->Html->link('削除', 'del?id=' . $eventdt['id'],
          array('class' => 'btn btn-outline-secondary col-lg-3 col-md-3 col-sm-12',
          'title'=>'削除'),
          'タグを削除します。よろしいですか？'
        );
        ?>

      </div>
    </div>
  </div>
  <?php
  echo $this->Form->end();
  ?>
</div>
<div class="col-lg-4 col-md-4 col-sm-4">
  <div class="previewhidden">
    <h4>プレビュー</h4>
    <p>[ESC]キーでプレビューをキャンセルできます</p>
    <div id="editor_preview"></div>
    <div id="shopName"></div>
    <div id="itemImageSlider"></div>
  </div>
</div>

<script type="text/javascript">
$(function(){
  var editor;

  $('#backnavi').show();

  $(document).keyup(function(e) {
    if (e.keyCode == 27) { // Esc
      $("#editor_preview").empty();
    }
  });


  $('#preview').on('click', function() {
    var date = new Date() ;
    var value = editor.getValue();
    console.log(value);
    $("#editor_preview").empty();
    $("#shopName").empty();
    $("#itemImageSlider").empty();
    const tag = $('input:hidden[name="plugintype"]').val();
    var encstr = window.btoa(unescape(encodeURIComponent(JSON.stringify(value,null,2))));
    console.log(encstr);
    const url = "/tag/makehtml";
    $.post("/tag/makehtml",{"t":tag, "j":encstr, "s":date.getTime()},
    function(dt){
      console.log(dt);
      $('#editor_preview').html(dt);
    }
  );
  return false;
});


$(document).ready(function(){
  $('input:hidden[name="plugintype"]').val('<?php echo $plugintype; ?>');
  $("#editor_holder").empty();
  JSONEditor.defaults.options.theme = 'bootstrap3';
  editor = new JSONEditor(document.getElementById('editor_holder'),{
    disable_collapse: true,
    disable_edit_json: true,
    disable_properties: true,
    schema: <?php echo htmlspecialchars_decode($formjson); ?>,
    iconlib: "fontawesome4"
  });
  $('.formhidden').fadeIn();

  var url = "/tag/template?t=" + "<?php echo $plugintype; ?>";
  $.getJSON(url)
  .done(function(data1,textStatus,jqXHR) {
    const data2 = JSON.stringify(data1);
    json = JSON.parse(data2);
    if(json.preview=='on'){
      $('#preview').show();
      $('.previewhidden').show();
    }else{
      $('#preview').hide();
      $('.previewhidden').hide();
    }
  });

  var dt =<?php echo $formdata; ?>;
  editor.setValue(dt);

  //不足分読み込み
  // var obj = <?php //echo htmlspecialchars_decode($formdata); ?>;
  // Object.keys(obj).forEach(function (key) {
  //     var name = editor.getEditor('root.' + key);
  //     if(name) {
  //         name.setValue(obj[key]);
  //         console.log(name.getValue());
  //     }
  // });

  //var errors = editor.validate();
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

$('.list-group a').on('click', function() {

  var click =  $(this).data('id');
  const url = "/tag/template?t=" + $(this).data('id');
  $.getJSON(url)
  .done(function(data1,textStatus,jqXHR) {
    const data2 = JSON.stringify(data1);
    json = JSON.parse(data2);
    $('.formhidden').fadeIn();
    $('input:hidden[name="plugintype"]').val(json.id);



    $("#editor_holder").empty();
    JSONEditor.defaults.options.theme = 'bootstrap3';
    editor = new JSONEditor(document.getElementById('editor_holder'),{
      disable_collapse: true,
      disable_edit_json: true,
      disable_properties: true,
      schema: json.form,
      iconlib: "fontawesome4"
    });
    $('.formhidden').fadeIn();
  })
  return false;

});
});
</script>
