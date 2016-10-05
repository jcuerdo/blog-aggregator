<?php

use Blog\Library\SitemapGenerator;

$sitemap = new SitemapGenerator($app['url'], "/");


$sitemap->sitemapFileName = __DIR__ . "/../web/sitemap.xml";
$sitemap->robotsFileName = __DIR__ . "/../web/robots.txt";

$postsModel = new \Blog\Model\Post($app);

$posts = $postsModel->getPosts();

foreach($posts as $post) {
    $sitemap->addUrl(
        $app['url'] . '/' . urldecode($post['slag']),
        date('c'),
        'daily',
        '0.5'
    );
}


$sitemap->createSitemap();
$sitemap->writeSitemap();