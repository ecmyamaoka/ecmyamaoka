<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'util/arraykey');
App::import('Vendor', 'util/Nslog');

$this->assign('title', '商品ページ比較君(Y)');

$opselect1 = '';
$opselect2 = '';
if ($op == 1) {
    $opselect1 = 'checked';
} else {
    $opselect2 = 'checked';
}

$arkey = new Util\arraykey();
$log = new Util\Nslog();

//echo $akey->check();





?>
<script type="text/javascript">
    //<![CDATA[

    window.onload = function() {

        var skillsToDraw = <?php echo json_encode($tag1) ?>;
        var skillsToDraw2 = <?php echo json_encode($tag2) ?>;

        var width = 600;
        var height = 350;
        var fill = d3.scale.category20();

        if (skillsToDraw.length > 0) {

            d3.layout.cloud()
                .size([width, height])
                .words(skillsToDraw)
                .rotate(function() {
                    return (~~(Math.random() * 6) - 2) * 2;
                })
                .font("Impact")
                .fontSize(function(d) {
                    return d.size;
                })
                .on("end", drawSkillCloud)
                .start();
        }
        if (skillsToDraw2.length > 0) {
            d3.layout.cloud()
                .size([width, height])
                .words(skillsToDraw2)
                .rotate(function() {
                    return (~~(Math.random() * 6) - 2) * 2;
                })
                .font("Impact")
                .fontSize(function(d) {
                    return d.size;
                })
                .on("end", drawSkillCloud2)
                .start();
        }

        function drawSkillCloud(words) {
            d3.select("#cloud1").append("svg")
                .attr("width", "100%")
                .attr("height", "100%")
                .append("g")
                .attr("transform", "translate(" + ~~(width / 2) + "," + ~~(height / 2) + ")")
                .selectAll("text")
                .data(words)
                .enter().append("text")
                .style("font-size", function(d) {
                    return d.size + "px";
                })
                .style("-webkit-touch-callout", "none")
                .style("-webkit-user-select", "none")
                .style("-khtml-user-select", "none")
                .style("-moz-user-select", "none")
                .style("-ms-user-select", "none")
                .style("user-select", "none")
                .style("cursor", "default")
                .style("font-family", "Impact")
                .style("fill", function(d, i) {
                    return fill(i);
                })
                .attr("text-anchor", "middle")
                .attr("transform", function(d) {
                    return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
                })
                .text(function(d) {
                    return d.text;
                });
        }



        // apply D3.js drawing API
        function drawSkillCloud2(words) {
            d3.select("#cloud2").append("svg")
                .attr("width", "100%")
                .attr("height", "100%")
                .append("g")
                .attr("transform", "translate(" + ~~(width / 2) + "," + ~~(height / 2) + ")")
                .selectAll("text")
                .data(words)
                .enter().append("text")
                .style("font-size", function(d) {
                    return d.size + "px";
                })
                .style("-webkit-touch-callout", "none")
                .style("-webkit-user-select", "none")
                .style("-khtml-user-select", "none")
                .style("-moz-user-select", "none")
                .style("-ms-user-select", "none")
                .style("user-select", "none")
                .style("cursor", "default")
                .style("font-family", "Impact")
                .style("fill", function(d, i) {
                    return fill(i);
                })
                .attr("text-anchor", "middle")
                .attr("transform", function(d) {
                    return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
                })
                .text(function(d) {
                    return d.text;
                });
        }

        var svg = document.getElementsByTagName("svg")[0];
        if (svg != null) {
            var bbox = svg.getBBox();
            var viewBox = [bbox.x, bbox.y, bbox.width, bbox.height].join(" ");
            svg.setAttribute("viewBox", viewBox);
        }
        var svg2 = document.getElementsByTagName("svg")[1];
        if (svg2 != null) {
            var bbox2 = svg2.getBBox();
            var viewBox2 = [bbox2.x, bbox2.y, bbox2.width, bbox2.height].join(" ");
            svg2.setAttribute("viewBox", viewBox2);
        }


    }

    //]]>
</script>



<div class="container-fluid">
    <?php
    echo  $this->Form->create(
        false,
        array('url' => array('action' => 'index'), 'type' => 'get')
    );
    ?>
    <div class="row">
        <div class="col-lg-4 col-md-3 col-sm-2 col-xs-12">
            <?php
            echo $this->Form->input('u1', array(
                'label' => false,
                'div' => array(
                    'class' => 'form-group row'
                ),
                'value' => $param['u1'],
                'between' => '<div class="col-sm-12">',
                'after' => '</div>',
                'class' => 'form-control',
                'placeholder' => '比較元商品URL'
            ));
            ?>
        </div>
        <div class="col-lg-4 col-md-3 col-sm-2 col-xs-12">
            <?php
            echo $this->Form->input('u2', array(
                'label' => false,
                'div' => array(
                    'class' => 'form-group row'
                ),
                'value' => $param['u2'],
                'between' => '<div class="col-sm-12">',
                'after' => '</div>',
                'class' => 'form-control',
                'placeholder' => '比較先商品URL'
            ));
            ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
            <div class="form-check-inline line-height">
                <b>解析</b>
            </div>
            <div class="form-check-inline line-height">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="op" value="1" <?php echo $opselect1 ?>>キーワード
                </label>
            </div>
            <div class="form-check-inline line-height">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="op" value="2" <?php echo $opselect2 ?>>形態素
                </label>
            </div>
            <div class="form-check-inline line-height">
                <?php
                echo $this->Form->submit('取得', array(
                    'div' => false,
                    'class' => 'btn btn-primary btn-block ml-3'
                ));
                ?>
            </div>
        </div>
    </div>
    <?php
    echo $this->Form->end();
    ?>

    <hr>
    <div Class="row margintop20">
        <div Class="col-md-6">
            <h5><?php echo $arkey->check($res1, '商品名') ?></h5>
            <?php echo $errorMes1 ?>
        </div>
        <div Class="col-md-6">
            <h5><?php echo $arkey->check($res2, '商品名') ?></h5>
            <?php echo $errorMes2 ?>
        </div>
    </div>


    <div Class="row margintop20">
        <div Class="col-md-6">
            <div Class="row m-1">
                <?php
                foreach ($imgres1 as $value) {
                    echo '<div class="col-md-2">';
                    echo '<img src="' . $value . '" class="img-thumbnail">';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <div Class="col-md-6">
            <div Class="row m-1">
                <?php
                foreach ($imgres2 as $value) {
                    echo '<div class="col-md-2">';
                    echo '<img src="' . $value . '" class="img-thumbnail">';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    <div Class="row margintop20">
    </div>
    <div Class="row">
        <div Class="col-md-6">
            <div id="cloud1"></div>
        </div>
        <div Class="col-md-6">
            <div id="cloud2"></div>
        </div>
    </div>



    <div Class="row margintop20">
        <div Class="col-md-6">
            <?php
            if (!empty($res1['result'])) {
                echo '<Table Class="table table-sm table-striped resulttable">';
                echo '<tr>';
                echo '<th class="text-center">キーワード</th>';
                echo '<th class="text-center">合計</th>';
                echo '<th class="text-center">割合</th>';
                echo '<th class="text-center">商品名</th>';
                echo '<th class="text-center">キャッチコピー</th>';
                echo '<th class="text-center">商品情報</th>';
                echo '</tr>';

                foreach ($res1['result'] as $value) {
                    echo '<tr>';
                    echo '<td>' . $value['keyword'] . '</td>';
                    echo '<td class="text-right">' . $value['ct'] . '</td>';
                    echo '<td class="text-right">' . number_format($value['ratio'], 2) . '%</td>';
                    echo '<td class="text-right">' . $value['s1'] . '</td>';
                    echo '<td class="text-right">' . $value['s2'] . '</td>';
                    echo '<td class="text-right">' . $value['s3'] . '</td>';
                    echo '</tr>';
                }
                echo '</Table>';
            }
            ?>
        </div>
        <div Class="col-md-6">
            <?php
            if (!empty($res2['result'])) {
                echo '<Table Class="table table-sm table-striped resulttable">';
                echo '<tr>';
                echo '<th class="text-center">キーワード</th>';
                echo '<th class="text-center">合計</th>';
                echo '<th class="text-center">割合</th>';
                echo '<th class="text-center">商品名</th>';
                echo '<th class="text-center">キャッチコピー</th>';
                echo '<th class="text-center">商品情報</th>';
                echo '</tr>';

                foreach ($res2['result'] as $value) {
                    echo '<tr>';
                    echo '<td>' . $value['keyword'] . '</td>';
                    echo '<td class="text-right">' . $value['ct'] . '</td>';
                    echo '<td class="text-right">' . number_format($value['ratio'], 2) . '%</td>';
                    echo '<td class="text-right">' . $value['s1'] . '</td>';
                    echo '<td class="text-right">' . $value['s2'] . '</td>';
                    echo '<td class="text-right">' . $value['s3'] . '</td>';
                    echo '</tr>';
                }
                echo '</Table>';
            } ?>
        </div>
    </div>

    <div Class="row margintop20">
        <div Class="col-md-12">
            <Table Class="table table-sm table-borderless resulttable tablefont">
                <tr>
                    <th width="5%" class="diff-td table-stripe-td">商品名</th>
                    <td width="45%" class="diff-td table-stripe-td"><?php echo $arkey->check($res1, '商品名') ?></td>
                    <td width="5" bgcolor="#fff"></td>
                    <th width="5%" class="diff-td table-stripe-td">商品名</th>
                    <td width="45%" class="diff-td table-stripe-td"><?php echo $arkey->check($res2, '商品名') ?></td>
                </tr>
                
                <tr>
                    <th width="5%" class="diff-td">キャッチコピー</th>
                    <td width="45%" class="diff-td"><?php echo $arkey->check($res1, 'キャッチコピー') ?></td>
                    <td width="5" bgcolor="#fff"></td>
                    <th width="5%" class="diff-td">キャッチコピー</th>
                    <td width="45%" class="diff-td"><?php echo $arkey->check($res2, 'キャッチコピー') ?></td>
                </tr>
                <tr>
                    <th width="5%" class="diff-td table-stripe-td">商品情報</th>
                    <td width="45%" class="diff-td table-stripe-td"><?php echo $arkey->check($res1, '商品情報') ?></td>
                    <td width="5" bgcolor="#fff"></td>
                    <th width="5%" class="diff-td table-stripe-td">商品情報</th>
                    <td width="45%" class="diff-td table-stripe-td"><?php echo $arkey->check($res2, '商品情報') ?></td>
                </tr>
                <tr>
            </Table>


        </div>
    </div>



    <div class="row margintop100">
        <div Class="col-md-4">
            <h5>用語説明</h5>
            <Table Class="table table-sm table-striped resulttable">
                <tr>
                    <th>キーワード</th>
                    <td>検出したキーワード</td>
                </tr>
                <tr>
                    <th>合計</th>
                    <td>出現件数</td>
                </tr>
                <tr>
                    <th>割合</th>
                    <td>出現したキーワードの割合</td>
                </tr>
                <tr>
                    <th>商品名</th>
                    <td>商品名</td>
                </tr>
                <tr>
                    <th>キャッチコピー</th>
                    <td>キャッチコピー</td>
                </tr>
                <tr>
                    <th>商品情報</th>
                    <td>商品情報</td>
                </tr>
            </Table>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-12">※ 100%キーワードを抽出できない場合があります。
        </div>
    </div>

</div>