<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__ . '/../config/config.prod.php';
require_once __DIR__ . '/import_gpt.php';
