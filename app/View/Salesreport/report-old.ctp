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
$this->assign('title', 'セット商品販売レポート ver 1.0.1');

?>
<?php
    echo $this->Form->create(
        false,
        array('url' => array('action' => 'report'), 'type' => 'post')
    );
?>
<div class="row">
    <div class="col-sm-2">
        <input type="text"
            value="<?php echo $stdate; ?>"
            name="stdate"
            autocomplete="off"
            class="datepicker-here form-control form-control-sm"
            data-language='ja'
            data-min-view="days"
            data-view="days"
            data-date-format="yyyy-mm-dd" />
    </div>
    <div class="col-sm-2">
        <input type="text"
            value="<?php echo $eddate; ?>"
            name="eddate"
            autocomplete="off"
            class="datepicker-here form-control form-control-sm"
            data-language='ja'
            data-min-view="days"
            data-view="days"
            data-date-format="yyyy-mm-dd" />
    </div>
    <div class="col-sm-2">
        <?php
        
            echo $this->Form->input('shopcode', array(
                'label' => false,
                'type' => 'select',
                'options' => $scode,
                'div' => array(
                    'class' => 'form-group row'
                ),
                'value'=> $shopcode,
                'between' => '<div class="col-sm-12">',
                'after' => '</div>',
                'class' => 'form-control form-control-sm'
            ));


            $this->Form->select('shopcode', $shopcode, array('escape' => false));
        ?>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
        <button class="btn btn-primary btn-sm"  name="submit" type="submit" value="表示">表示</button>
        <button class="btn btn-primary btn-sm" type="submit" name="submit" value="ダウンロード">CSVダウンロード</button>
        </div>
    </div>
    <!-- <div class="col-sm-1">
        <div class="form-group">
        </div>
    </div> -->
    </div>
    
      <?php 
    echo $this->Form->end();
?>
<div class="row">
<div class="col-sm-12">
<?php //echo $ct;?>
<table class="table table table-striped">
<thead>
<tr>
<td width="220">商品コード</td>
<td width="20">Set</td>
<td>商品名</td>
<td width="80" class="text-center">売価</td>
<td width="80" class="text-center">原価</td>
<td width="80" class="text-center">受注数量</td>
<td width="80" class="text-center">受注金額</td>
</tr>
</thead>
<?php
    if(isset($result2)){
        foreach($result2 as $dt) {
            echo '<tr>';
            echo '<td>' . $dt['goods_id'] . '</td>';
            if($dt['setflg']==true){
                echo '<td><font color="red">●</font></td>';
            }else{
                echo '<td></td>';
            }
            echo '<td>' . $dt['goods_name'] . '</td>';
            echo '<td align="right">' . number_format($dt['unit_price']) . '</td>';
            echo '<td align="right">' . number_format($dt['first_cost']) . '</td>';
            echo '<td align="right">' . $dt['quantity'] . '</td>';
            echo '<td align="right">' . number_format($dt['total']) . '</td>';
            echo '</tr>';
        }
    }



?>
</table>
</div>
</div>

<script>
    $('#stdate').datetimepicker();
    $('#eddate').datetimepicker();

</script>








