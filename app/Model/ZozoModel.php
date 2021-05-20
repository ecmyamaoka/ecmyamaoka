<?php
    App::uses('AppModel', 'Model');
    include("../app/Config/database.php");  //開発サーバ

    class Zozo extends AppModel {
        //public $hasOne = 'Profile';
        //public $useDbConfig = 'test';

        //$mysqldb
        public $useTable = false;
        /*public $name = 'a';
        public $hasMany = array(
            'Recipe' => array(
                'className' => 'Recipe',
                'conditions' => array('Recipe.approved' => '1'),
                'order' => 'Recipe.created DESC'
            )
        );*/
    }
?>