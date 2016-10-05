<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array (
        'mysql' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'blogaggregator',
            'user'      => 'root',
            'password'  => '',
            'charset'   => 'utf8mb4',
        ),
    ),
));

$app['debug'] = false;
$app['url'] = 'http://www.andsoyoucode.xyz';
$app['name'] = 'And So You Code';
$app['description'] = 'Noticias sobre tecnología e internet';