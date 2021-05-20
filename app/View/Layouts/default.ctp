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
<html lang="ja">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $this->fetch('title'); ?>
    </title>

    <script defer src="https://use.fontawesome.com/releases/v5.8.1/js/all.js" integrity="sha384-g5uSoOSBd7KkhAMlnQILrecXvzst9TdC09/VM+pjDTCM+1il8RHz5fKANTFFb+gQ" crossorigin="anonymous"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/united/bootstrap.min.css" rel="stylesheet" integrity="sha384-WTtvlZJeRyCiKUtbQ88X1x9uHmKi0eHCbQ8irbzqSLkE0DpAZuixT5yFvgX0CjIu" crossorigin="anonymous">
    
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <!-- <script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script> -->

<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" /> -->


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/jasondavies/d3-cloud/v1.2.1/build/d3.layout.cloud.js"></script>


    <?php
    // echo $this->Html->meta('icon');
    echo $this->Html->css('site');
    echo $this->Html->css('cake.generic');

    //DATEPICKER
    echo $this->Html->css('datepicker.css');
    echo $this->Html->script('datepicker.js');
    echo $this->Html->script('i18n/datepicker.ja.js');
    echo $this->Html->script('d3.min.js');

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
                </ul>
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item">
                    <?php echo '<h5>' . $this->fetch('title') . '</h5>'; ?>
                    </li>
                </ul>
                <!-- <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item">
                    <a class="nav-link" href="https://club.ec-masters.net/ecm/ext/logout.php">ログアウト</a>
                    </li>
                </ul> -->
            </div>
        </div>
    </div>
    <!-- <div class="container-fluid">
        <h1><?php //echo $this->fetch('title'); ?></h1>
    </div> -->
    <div class="container-fluid">   
    <?php echo $this->fetch('content'); ?>
    </div>
    
    <footer class="footer">
    <div class="container">
        <p class="text-muted text-center">Copyright © 2019-2021 日本ECサービス株式会社 All Rights Reserved.</p>
    </div>
    </footer>

    <?php echo $this->element('sql_dump'); ?>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html> 