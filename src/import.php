<?php

$reader = new Blog\Rss\RssReader();
$postModel = new \Blog\Model\Post($app);
$rssModel = new \Blog\Model\Rss($app);

$rssList = $rssModel->getAll();
echo "Start importing \n";
foreach($rssList as $rss){
    echo "Importing $rss \n";
    $posts = $reader->getItems($rss);
    $count = 0;
    foreach($posts as $post)
    {
	if($count > 3){
	 break;
	}
	$count ++;
        $title = isset($post->title) ? (string)$post->title : null;
        $link = isset($post->link) ? (string)$post->link : null;
        $date = isset($post->pubDate) ? (string)$post->pubDate : null;
        $description = isset($post->description) ? (string) $post->description: null;

        if (strlen($description) < 1500) {
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
