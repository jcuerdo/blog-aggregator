<?php
$reader = new Blog\Rss\RssReader();

$posts = $reader->getItems('http://feeds.weblogssl.com/xataka2');
$posts = $reader->getItems('http://www.20minutos.es/rss/tecnologia');
$posts = $reader->getItems('http://feeds.weblogssl.com/genbeta');

$postModel = new \Blog\Model\Post($app);

foreach($posts as $post)
{
    $title = isset($post->title) ? (string)$post->title : null;
    $link = isset($post->link) ? (string)$post->link : null;
    $date = isset($post->pubDate) ? (string)$post->pubDate : null;
    $description = isset($post->description) ? (string) $post->description: null;

    $postModel->insertPost($title, $date, $link, $description);
}

echo "Proccess finished imported";