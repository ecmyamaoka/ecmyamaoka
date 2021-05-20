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
h3{
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
        <h1>セット商品販売レポートとは</h1>
        <ul><li class="no-li-indent">セット商品販売レポートは、期間内におけるセット商品の販売実績数を得ることができます。</li></ul>
        <h1>価格</h1>
        <ul><li class="no-li-indent">ECマスターズ会員　無料<br>
        会員ではない方はECマスターズ会員に登録していただく必要があります。<br>
        詳細は<a href="https://seminar.ec-masters.net/index.php?contactus">こちらまで</a> お問い合わせください。
        </li></ul>
        <h1>表示サンプル</h1>
        <img class="img-fluid" src="/img/salesreport/sample.png">
    <div>
</div>




