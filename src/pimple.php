<?php


$app['postModel'] = function ($app) {
    return new \Blog\Model\Post($app);
};


$app['rssModel'] = function ($app) {
    return new \Blog\Model\Rss($app);
};


$app['visitModel'] = function ($app) {
    return new \Blog\Model\Visit($app);
};


$app['gpt'] = function ($app) {
    return new \Blog\Library\Gpt($app);
};

$app['images'] = function ($app) {
    return new \Blog\Library\Images($app);
};

$app['elasticClient'] = function ($app) {

    $builder = Elasticsearch\ClientBuilder::create();
    $builder->setHosts([$app['elastic_url']]);

    $client =  $builder->build();

    return $client;

};

