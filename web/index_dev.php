<?php

require_once __DIR__ . '/../config/config.dev.php';
require_once __DIR__.'/../src/register.php';
require_once __DIR__.'/../src/routes.php';
require_once __DIR__.'/../src/pimple.php';

$app->run();
