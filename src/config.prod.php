<?php
require_once __DIR__.'/config.php';

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array (
        'mysql' => array(
            'driver'    => 'pdo_mysql',
            'host'          =>  getenv('OPENSHIFT_MYSQL_DB_HOST'),
            'port'          =>  getenv('OPENSHIFT_MYSQL_DB_PORT'),
            'dbname'          =>  getenv('OPENSHIFT_APP_NAME'),
            'user'          =>  getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
            'password'	=>  getenv('OPENSHIFT_MYSQL_DB_PASSWORD'),
            'charset'   => 'utf8mb4',
        ),
    ),
));

$app['debug'] = false;

require_once __DIR__.'/parameters.php';