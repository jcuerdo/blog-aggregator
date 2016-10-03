<?php

$app['postModel'] = function ($app) {
    return new \Blog\Model\Post($app);
};