<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__ . '/config.dev.php';
require_once __DIR__ . '/sitemap.generator.php';