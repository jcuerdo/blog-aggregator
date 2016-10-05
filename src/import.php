<?php
require_once __DIR__ . '/rss.php';

$reader = new Blog\Rss\RssReader();

foreach($app->rss as $rss){
    echo "Importing $rss \n";
    $posts = $reader->getItems($rss);

    $postModel = new \Blog\Model\Post($app);

    foreach($posts as $post)
    {
        $title = isset($post->title) ? (string)$post->title : null;
        $link = isset($post->link) ? (string)$post->link : null;
        $date = isset($post->pubDate) ? (string)$post->pubDate : null;
        $description = isset($post->description) ? (string) $post->description: null;

        if ($slug = $postModel->insertPost($title, $date, $link, $description)) {
            $exporter = new \Blog\Twitter\Exporter();
            $exporter->publishPost($app['url'] . '/' . $slug);
        }
    }
    echo "Imported finished \n";
}

echo "Proccess finished imported \n";