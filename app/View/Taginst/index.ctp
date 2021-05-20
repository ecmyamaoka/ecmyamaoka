<style>


    .details {
        padding-bottom: 20px;
    }

    .contents {
        margin: 20px 20px 20px 60px;
    }

    ol {
        display: block;
        list-style-type: decimal;
        margin-block-start: 1em;
        margin-block-end: 1em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        padding-inline-start: 0px;
    }


    .rms-breadcrumb {
        margin: 20px 0;
        text-align: left;
        font-size: 1.0rem;
    }

    .rms-breadcrumb .header::before {
        display: inline-block;
        position: relative;
        top: 4px;
        width: 42px;
        height: 20px;
        content: "";
        padding: 0 8px 0 0;
        background: url(https://c.ap6.content.force.com/servlet/servlet.ImageServer?id=0150K0000080cxW&oid=00D280000020VxK&lastMod=1540171098000) no-repeat;
        background-position: -135px -5px;
    }

    .rms-breadcrumb ol {
        display: table;
        width: 97%;
        margin: 10px 0 0 0;
        list-style: none;
        letter-spacing: -0.4em;
        font-size: 0.84rem;
    }

    .rms-breadcrumb ol li {
        display: table-cell;
        position: relative;
        width: 20%;
        height: 46px;
        letter-spacing: normal;
        line-height: 1.4;
        padding: 5px 10px 5px 25px;
        box-sizing: border-box;
        background: #ababab;
        text-align: center;
        text-decoration: none;
        color: #000000;
        text-align: left;
        vertical-align: middle;
    }

    .rms-breadcrumb ol li:before {
        content: "";
        border-top: 23px solid transparent;
        border-bottom: 23px solid transparent;
        border-left: 14px solid #fff;
        position: absolute;
        left: 0;
        top: 0;
        z-index: 2;
    }

    .rms-breadcrumb ol li:after {
        content: "";
        border-top: 23px solid transparent;
        border-bottom: 23px solid transparent;
        border-left: 14px solid #ababab;
        position: absolute;
        right: -14px;
        top: 0;
        z-index: 3;
    }

    .rms-breadcrumb ol.expand li {
        height: 64px;
    }

    .rms-breadcrumb ol.expand li::before,
    .rms-breadcrumb ol.expand li::after {
        border-top-width: 32px;
        border-bottom-width: 32px;
    }

    .rms-breadcrumb ol li.step-1 {
        background: #686868;
        color: #ffffff;
    }

    .rms-breadcrumb ol li.step-1:after {
        border-left: 14px solid #686868;
    }

    .rms-breadcrumb ol li.step-2 {
        left: 2px;
        background: #888888;
        color: #ffffff;
    }

    .rms-breadcrumb ol li.step-2:after {
        border-left: 14px solid #888888;
    }

    .rms-breadcrumb ol li.step-3,
    .rms-breadcrumb ol li.step-4 {
        background: #e8e8e8;
    }

    .rms-breadcrumb ol li.step-3:after,
    .rms-breadcrumb ol li.step-4:after {
        border-left: 14px solid #e8e8e8;
    }

    .rms-breadcrumb ol li.step-3 {
        left: 4px;
    }

    .rms-breadcrumb ol li.step-4 {
        left: 6px;
    }

    .rms-breadcrumb ol li.last-step {
        left: 8px;
        background: #bf0000;
        color: #ffffff;
    }

    .rms-breadcrumb ol.element2 li.last-step {
        left: 2px;
    }

    .rms-breadcrumb ol.element3 li.last-step {
        left: 4px;
    }

    .rms-breadcrumb ol.element4 li.last-step {
        left: 6px;
    }

    .rms-breadcrumb ol li.last-step:after {
        border-left: 14px solid #bf0000;
    }

    .rms-breadcrumb ol li:first-child {
        padding-left: 45px;
    }

    .rms-breadcrumb ol li:first-child:before {
        display: none;
    }

    .rms-breadcrumb ol li.last-child a {
        padding-right: 10px;
    }

    .rms-breadcrumb ol li.last-child a:after {
        display: none;
    }

    .rms-breadcrumb ol li.active a,
    .rms-breadcrumb ol li a:hover {
        background: #004696;
    }

    .rms-breadcrumb ol li.active a:after,
    .rms-breadcrumb ol li a:hover:after {
        border-left-color: #004696;
    }

    .rms-breadcrumb ol li.step-1::before {
        display: inline-block;
        position: absolute;
        top: 14px;
        left: 15px;
        content: "";
        width: 18px;
        height: 18px;
        border: none;
        background-image: url(https://c.ap6.content.force.com/servlet/servlet.ImageServer?id=0150K0000080cxv&oid=00D280000020VxK&lastMod=1540171251000);
        background-repeat: no-repeat;
    }

    .rms-breadcrumb ol.expand li.step-1::before {
        top: 23px;
    }

    .rms-breadcrumb ol li.icon-01::before {
        background-position: -5px -5px;
    }

    .rms-breadcrumb ol li.icon-02::before {
        background-position: -33px -5px;
    }

    .rms-breadcrumb ol li.icon-03::before {
        background-position: -5px -33px;
    }

    .rms-breadcrumb ol li.icon-04::before {
        background-position: -33px -33px;
    }

    .rms-breadcrumb ol li.icon-05::before {
        background-position: -61px -5px;
    }

    .rms-breadcrumb ol li.icon-06::before {
        background-position: -61px -33px;
    }

    .rms-breadcrumb ol li.icon-07::before {
        background-position: -5px -61px;
    }

    .rms-breadcrumb ol li.icon-none::before {
        display: none;
    }

    .rms-breadcrumb ol li.icon-none {
        padding-left: 18px;
    }

    .floatR .rms-breadcrumb {
        font-size: 1.4rem;
    }
</style>
<script type="text/javascript">
$(function(){
    $('#backnavi').remove();
});
</script>

<?php
use Aws\PhpHash;

$this->assign('title', 'トラッキングタグ　インストール方法');

?>
<div class="row">
    <div class="col-lg-10 col-md-10 col-sm-4">
        <?php echo $this->Session->flash(); ?>
    </div>
</div>
<?php
echo  $this->Form->create(
    false,
    array('url' => array('action' => 'install'), 'type' => 'post')
);
?>
<div class="row margin20">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <h3 class="title-h3">トラッキングタグ仕様</h3>
        <div class="contents">
            <ul>
            <li>PCおよびスマートフォンでのRPPの掲載元を調べることができます。</li>
            <li>アプリの計測はできません</li>
            <li>https://からは始まる外部サイトからはキーワードを取得できません</li>
            </ul>
        </div>
    </div>
</div>
<div class="row margin20">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <h3 class="title-h3">楽天GOLDにスクリプトをアップロードする</h3>
        <div class="contents">
            <?php
            echo $this->Form->submit('Ver 1.0.1 楽天GOLDにアップロード', array(
                'div' => false,
                'class' => 'btn btn-success btn-lg col-lg-5 col-md-5 col-sm-12'
            ));
            ?>
        </div>
    </div>
</div>
<div class="row margin20">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <h3 class="title-h3">SPサイトにスクリプトを挿入する(初回のみ)</h3>
        <div class="contents">
            <div class="card border-success mb-4" style="max-width: 50rem;">
                <div class="card-header">SP用スクリプト</div>
                <div class="card-body">
                    <p class="card-text">&lt;script ="" src="https://www.rakuten.ne.jp/gold/<?php echo $shopid; ?>/tagmng/tagmng.js?191028" charset="UTF-8"&gt;&lt;/script =""&gt;</p>
                </div>
            </div>
            <?php 
                if($plan == 20 || $plan == 19 || $plan == 2 || $plan == 5){
            ?>
            <div class="card border-danger mb-4" style="max-width: 50rem;">
                <div class="card-header text-danger">※注意事項</div>
                <div class="card-body">
                    <p class="card-text">RPPトラッキングをご使用中の場合<br>
                    https://www.rakuten.ne.jp/gold/xxxxxx/k4ysptkr.js<br>
                    https://www.rakuten.ne.jp/gold/xxxxxx/scrypt.js<br>
                    いずれかのスクリプトは削除のほどお願いします。<br>
                    ご不明な場合は、専用のチャットワークでご連絡ください。
                    </p>
                </div>
            </div>
            <?php 
               }
            ?>



            <div class="rms-breadcrumb" style="max-width: 47rem;">
                <ol class="element4">
                    <li class="step-1 icon-01">RMS 店舗設定</li>
                    <li class="step-2">2 スマートフォン デザイン設定</li>
                    <li class="last-step">トップページ編集</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    　 <div class="details">トップ説明文(1)にSP用スクリプトをいれてください</div>
                    <img width="800" src="/img/tag/pc3.jpg">
                </div>
            </div>

            <div class="rms-breadcrumb" style="max-width: 47rem;">
                <ol class="element4">
                    <li class="step-1 icon-01">RMS 店舗設定</li>
                    <li class="step-2">2 スマートフォン デザイン設定</li>
                    <li class="last-step">商品ページ共通パーツ設定</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    　 <div class="details">商品ページ共通説明文にSP用スクリプトをいれてください</div>
                    <img width="800" src="/img/tag/sp2.jpg">
                </div>
            </div>

            <div class="rms-breadcrumb" style="max-width: 47rem;">
                <ol class="element4">
                    <li class="step-1 icon-01">RMS 店舗設定</li>
                    <li class="step-2">2 スマートフォン デザイン設定</li>
                    <li class="last-step">カテゴリページ共通パーツ設定</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    　 <div class="details">カテゴリページ共通説明文にSP用スクリプトをいれてください</div>
                    <img width="800" src="/img/tag/sp3.jpg">
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-xs-12 margintop20">
        <h3 class="title-h3">PCサイトにスクリプトを挿入する(初回のみ)</h3>
        <div class="contents">
            <div class="card border-success mb-4" style="max-width: 50rem;">
                <div class="card-header">PC用スクリプト</div>
                <div class="card-body">
                    <p class="card-text">&lt;body ="" onpageshow="var script = document.createElement('script'); script.src = 'https://www.rakuten.ne.jp/gold/<?php echo $shopid; ?>/tagmng/tagmng.js?191028'; script.charset = 'UTF-8'; document.body.appendChild(script);"&gt;&lt;/body =""&gt;</p>
                </div>
            </div>

            <div class="rms-breadcrumb" style="max-width: 47rem;">
                <ol class="element4">
                    <li class="step-1 icon-01">RMS 店舗設定</li>
                    <li class="step-2">1 PC デザイン設定</li>
                    <li class="last-step">ヘッダー・フッター・レフトナビ</li>
                </ol>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="details">テンプレートの編集を選択</div>
                    <img width="500" src="/img/tag/pc1.jpg">
                    <div class="details">
                        　 ヘッダーコンテンツに、PC用スクリプトをいれてください<br>
                        複数テンプレート使用している場合、すべてのテンプレートにいれてください。
                    </div>
                    <img width="800" src="/img/tag/pc2.jpg"><br>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<?php
echo $this->Form->end();
?>