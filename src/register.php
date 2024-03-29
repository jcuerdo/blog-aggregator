<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
    'twig.options'		=> array(
        'cache'			=> '/tmp/twig',
	'auto_reload' => true, 
   ),
));


$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => '/tmp/http',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());


$app->register(new Silex\Provider\SecurityServiceProvider(), array(

));

$app->register(new \Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => $app['log_dir'] ?? __DIR__ . '/../logs/app.log',
    'monolog.level' => \Monolog\Logger::WARNING,
));

$app['security.firewalls'] = array(
    'admin' => array(
        'pattern' => '^/admin/',
        'http' => true,
        'users' => array(
            'admin' => array('ROLE_ADMIN', 'GFhUOqZRlyJqqqjfbyDbcKaM0RDrH4fXt/yomjZZzTwQXC7MHrns0vZMIjxvWG1VKoMI4e3RSIaj00YDluPLTA=='),
        ),
    ),
);

$app->finish(function (Request $request, Response $response) use ($app) {
    if (strpos($request->getRequestUri(), '/api/') === 0) {
        /**
         * @var \Blog\Model\Visit $visitModel
         */
        $visitModel = $app['visitModel'];
        $visitModel->insert( $request->getRequestUri(),$request->headers->get('User-Agent') , $request->getClientIp());
    }

});
