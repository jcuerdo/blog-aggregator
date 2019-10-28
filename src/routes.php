<?php

$app->mount( '/', new Blog\Controller\IndexController() );
$app->mount( '/api', new Blog\Controller\ApiController() );
$app->mount( '/admin', new Blog\Controller\AdminController() );