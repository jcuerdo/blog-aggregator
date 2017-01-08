<?php

$app->mount( '/', new Blog\Controller\IndexController() );

$app->mount( '/admin', new Blog\Controller\AdminController() );