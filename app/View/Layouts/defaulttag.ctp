<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>

<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $this->fetch('title'); ?>
    </title>
    <meta http-equiv="Content-Language" content="ja">
    <script defer src="https://use.fontawesome.com/releases/v5.8.1/js/all.js" integrity="sha384-g5uSoOSBd7KkhAMlnQILrecXvzst9TdC09/VM+pjDTCM+1il8RHz5fKANTFFb+gQ" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/flatly/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="/css/datepicker.css"/>
    <script type="text/javascript" src="/js/datepicker.js"></script>
    <script type="text/javascript" src="/js/i18n/datepicker.ja.js"></script>
    <script type="text/javascript" src="/js/jsoneditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js" integrity="sha256-NXRS8qVcmZ3dOv3LziwznUHPegFhPZ1F/4inU7uC8h0=" crossorigin="anonymous"></script>
    
    <?php
	// echo $this->Html->meta('icon');
	echo $this->Html->css('cake.generic');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>
    <!-- Latest compiled and minified CSS -->
  
</head>
<style>
    .navbar {
        background-color: #fff !important;
        border-bottom: 1px solid #F0F0F0;
    }


</style>
<body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-expand-md fixed-top navbar-light bg-light">
        <div class="container-fluid">
            <a href="https://tool.ec-masters.net/home.php?" class="navbar-brand"><img src="https://tool.ec-masters.net/img/logo_tool.png" height="38"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav">
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="download">ツール <span class="caret"></span></a>
                        <div class="dropdown-menu" aria-labelledby="download">
                            <a class="dropdown-item" target="_blank" href="https://jsfiddle.net/bootswatch/jmg3gykg/">Open in JSFiddle</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../4/flatly/bootstrap.min.css" download>bootstrap.min.css</a>
                            <a class="dropdown-item" href="../4/flatly/bootstrap.css" download>bootstrap.css</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../4/flatly/_variables.scss" download>_variables.scss</a>
                            <a class="dropdown-item" href="../4/flatly/_bootswatch.scss" download>_bootswatch.scss</a>
                        </div>
                    </li> -->

                    <li class="nav-item">
                        <a class="nav-link" href="https://tool.ec-masters.net/home.php?">ECマスターズツール</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://club.ec-masters.net/">会員サイト</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://forum.ec-masters.net/forum_index.php">フォーラム</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://tool.ec-masters.net/account.php">登録情報</a>
                    </li>

                </ul>

                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item">
                    <a class="nav-link" href="https://club.ec-masters.net/ecm/ext/logout.php">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
              <h1><span class="mr-3 mb-1" id="backnavi" style="display:none;"><?php
             echo $this->Html->image('/img/back.png', array('url'=>array('controller'=>'tag','action'=>'index')));
            ?></span><?php echo $this->fetch('title'); ?></h1>
            </div>
        </div>
    </div>
    <div class="container-fluid">
    <?php echo $this->fetch('content'); ?>
    </div>
    


    <?php echo $this->element('sql_dump'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html> 