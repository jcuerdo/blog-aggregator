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
