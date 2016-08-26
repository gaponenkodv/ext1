<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => '\yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
    'modules' =>[
        'gii'            => [
            'class'          => 'yii\gii\Module',
           // 'password'       => '123456',
            /*'generatorPaths'=>array(
                'common.gii',
            ),
           /* 'ipFilters'      => ['127.0.0.1', '::1'],
            'generatorPaths' => [
                'bootstrap.gii', // since 0.9.1
                'application.generators',
            ],*/
        ],
    ],

];
