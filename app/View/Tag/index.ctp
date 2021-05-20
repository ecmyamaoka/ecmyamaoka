<?php
use Aws\PhpHash;

if(strpos($this->Html->url('', true),'service-dev') !== false ){
    $this->assign('title', '<span class="badge badge-pill badge-danger">DEBUG</span> タグマネージャー(R) ');
}else{
    $this->assign('title', 'タグマネージャー(R)');
}
?>

<!--The Main Thing-->


<div class="row margin20">
    <div class="col-lg-12 col-md-12 col-xs-12 margin30btm">
        <div>
            商品ページ、カテゴリページ等に作成したタグを入れておくことでイベントバナーや共通コンテンツ等を簡単に表示することができます。<br>
            <a href="/tag/help">初期設定</a>から該当箇所にスクリプトの設置をお願いします。
        </div>
    </div>

    <div class="col-lg-5 col-md-3 col-sm-3">
        <?php echo $this->Session->flash(); ?>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3">
        <?php
        echo $this->Html->link('新しいタグを追加', 'new', array('class' => 'btn btn-success btn-block m-1'));
        ?>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3">
        <a class="btn btn-outline-danger btn-block m-1" target="_blank" href="https://www.rakuten.co.jp/<?php echo $shopid;?>/?force-site=ipn">ショップトップ</a>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-3">
        <?php
        echo $this->Html->link('初期設定', 'help', array('class' => 'btn btn-outline-danger btn-block m-1'));
        ?>
    </div>
</div>
<div class="row margin20">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <table class="table table-striped ">

            <tr>
                <th scope="col">タグ名</th>
                <th scope="col">貼付タグ</th>
                <th scope="col">備考</th>
                <th scope="col">タグタイプ</th>
                <th scope="col">表示</th>
                <th scope="col" class="text-right">編集</th>
            </tr>

            <?php
            foreach ($result as $value) {
                echo '<tr>' . PHP_EOL;
                echo '<td>' . $value['tagdata']['tagname'] . '</td>';

                echo '<td>&lt;div id=&quot;_' . $value['tagdata']['tagname'];
                echo '&quot; =&quot;&quot;&gt;&lt;/div =&quot;&quot;&gt;</td>';

                echo '<td>' . $value['tagdata']['etc'] . '</td>';
                echo '<td>' . $value['tagdata']['plugintype'] . '</td>';

                echo '<td>';
                if($value['tagdata']['display']=='有効'){
                    echo '<div class="btn btn-success btn-sm">' . $value['tagdata']['display'] . '</div>';
                }else{
                    echo '<div class="btn btn-danger btn-sm">' . $value['tagdata']['display'] . '</div>';
                }
                echo '</td>';
                
                echo '<td class="text-right">';

                $parameter['id'] = $value['tagdata']['id'];
                echo $this->Html->link(
                    '編集',
                    array(
                        'action' => 'edit?id=' . $value['tagdata']['id']
                    ),
                    array('class' => 'btn btn-primary btn-sm')
                );

                echo '</td>';
                echo '</tr>' . PHP_EOL;
            }
            ?>
        </table>
    </div>
</div>
<div class="row margin20">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="alert alert-dismissible alert-light">
            <strong>初期設定後、貼付タグを商品ページ、カテゴリページ等に挿入してください。</strong>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-xs-12">
        <h3 class="title-h3">注意事項</h3>
        <ul>
            <li>楽天GOLDには挿入できません</li>
            <li>楽天市場で仕様変更あった場合、動作しなくなる場合があります。（保証しません）</li>
            <li>楽天市場公式アプリ内には表示されません</li>
            <li>挿入されるHTML等は検索対象になりません</li>
        </ul>
    </div>
</div>