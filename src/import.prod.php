<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__ . '/config.prod.php';
require_once __DIR__ . '/import.php';