<?php
require_once __DIR__ . '/../config/config.prod.php';
require_once __DIR__.'/../src/register.php';

$app->error(function (\Exception $e, $code) use($app) {
    $app['monolog']->error($e->getMessage(), $e->getTrace());
    switch ($code) {
        case 404:
            return $app->redirect($app["url_generator"]->generate("home"));
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
