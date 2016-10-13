<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

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
$app['url'] = 'http://www.lineadecodigo.es';
$app['name'] = 'Linea de código';
$app['description'] = 'Noticias sobre tecnología e internet';