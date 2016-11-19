<?php
require_once __DIR__ . '/../config/config.prod.php';
require_once __DIR__.'/../src/register.php';

$app->error(function (\Exception $e, $code) use($app) {
    switch ($code) {
        case 404:
            $message = $app['twig']->render('error/404.twig');
            break;
        default:
            $message = $app['twig']->render('error/default.twig');
    }

    return new Symfony\Component\HttpFoundation\Response($message, $code);
});

require_once __DIR__.'/../src/routes.php';
require_once __DIR__.'/../src/pimple.php';

$app->run();
