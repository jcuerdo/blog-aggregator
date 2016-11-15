<?php
require_once __DIR__.'/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array (
        'mysql' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'blogaggregator',
            'user'      => 'root',
            'password'  => 'root',
            'charset'   => 'utf8mb4',
        ),
    ),
));

$app['debug'] = true;

require_once __DIR__.'/parameters.php';