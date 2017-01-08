<?php
require_once __DIR__ . '/../config/rss.php';

$reader = new Blog\Rss\RssReader();
$postModel = new \Blog\Model\Post($app);
$rssModel = new \Blog\Model\Rss($app);

$databaseRss = $rssModel->getAll();

$app->rss = array_merge($app->rss, $databaseRss);

foreach($app->rss as $rss){
    $rssModel->insert($rss  );
    echo "Importing $rss \n";
    $posts = $reader->getItems($rss);

    foreach($posts as $post)
    {
        $title = isset($post->title) ? (string)$post->title : null;
        $link = isset($post->link) ? (string)$post->link : null;
        $date = isset($post->pubDate) ? (string)$post->pubDate : null;
        $description = isset($post->description) ? (string) $post->description: null;

        if (strlen($description) < 650) {
            echo "Discarted description too short: " . $description;
            continue;
        }

        if ($slug = $postModel->insertPost($title, $date, $link, $description)) {
            $exporter = new \Blog\Twitter\Exporter();
            $exporter->publishPost($title . ' - ' . $app['url'] . '/' . $slug);
        }
    }
    echo "Imported finished \n";
}

echo "Proccess finished imported \n";
