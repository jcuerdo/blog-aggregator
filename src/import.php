<?php

$reader = new Blog\Rss\RssReader();
$postModel = new \Blog\Model\Post($app);
$rssModel = new \Blog\Model\Rss($app);

$rssList = $rssModel->getAll();
echo "Start importing \n";
$imported = 0;
foreach($rssList as $rss){
    echo "Importing $rss \n";
    $posts = $reader->getItems($rss);
    $count = 0;
    foreach($posts as $post)
    {
        $import_max_posts = isset($app['import_max_posts']) ? $app['import_max_posts'] : 3;
        if($count > $import_max_posts){
            break;
        }
        $count ++;
        $title = isset($post->title) ? (string)$post->title : null;
        $link = isset($post->link) ? (string)$post->link : null;
        $date = isset($post->pubDate) ? (string)$post->pubDate : null;
        $description = isset($post->description) ? (string) $post->description: null;

        if ($post->children('media', True)->content) {
            $image = $post->children('media', True)->content->attributes();
            $imageHtml = sprintf("<p class='main-image'><img src='%s'/></p>", $image->url);
            $description = $imageHtml . $description;
        }
        $import_max_length = isset($app['import_max_length']) ? $app['import_max_length'] : 1500;
        if (strlen($description) < $import_max_length) {

            echo sprintf("Discarted description too short: Length %s , expected %s \n", strlen($description), $import_max_length);
            continue;
        }

        if ($slug = $postModel->insertPost($title, $date, $link, $description)) {
            $exporter = new \Blog\Twitter\Exporter();
            $exporter->publishPost($title . ' - ' . $app['url'] . '/' . $slug);
            $imported++;
        }
    }
    echo "Imported finished \n";
}

echo sprintf("Proccess finished imported %s posts \n", $imported);
