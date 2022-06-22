<?php

$reader = new Blog\Rss\RssReader();
$postModel = new \Blog\Model\Post($app);
$rssModel = new \Blog\Model\Rss($app);

$rssList = $rssModel->getAll();

echo "Start importing \n";
$totalImported = 0;
foreach($rssList as $rss){
    echo "Importing $rss \n";
    $posts = $reader->getItems($rss);
    $imported = 0;
    $import_max_posts = isset($app['import_max_posts']) ? $app['import_max_posts'] : 3;
    foreach($posts as $post)
    {
        if($imported > $import_max_posts){
            break;
        }

        $title = isset($post->title) ? (string)$post->title : null;
        $link = isset($post->link) ? (string)$post->link : null;
        $date = isset($post->pubDate) ? (string)$post->pubDate : null;
        $description = isset($post->description) ? (string) $post->description: null;
        $postImage = null;
        
        $description=preg_replace('/class=".*?"/', '', $description);
        $description=preg_replace('/id=".*?"/', '', $description);
        $description = preg_replace("/<\\/?" . "script" . "(.|\\s)*?>/","",$description);


        if (false && $post->children('media', True)->content) {
            $image = $post->children('media', True)->content->attributes();
            $imageHtml = sprintf("<p class='main-image'><img src='%s'/></p>", $image->url);
            $description = $imageHtml . $description;
            $postImage = $image->url;
        }
        else {
            $htmlDom = new DOMDocument();
            @$htmlDom->loadHTML($description);
            $imageTags = $htmlDom->getElementsByTagName('img');
            if(count($imageTags) > 0 ) {
                $postImage = $imageTags[0]->getAttribute('src');
            }
        }

        $import_max_length = isset($app['import_max_length']) ? $app['import_max_length'] : 1500;
        if (strlen($description) < $import_max_length) {
            echo sprintf("Discarted description too short: Length %s , expected %s \n", strlen($description), $import_max_length);
            continue;
        }
        
        if ($slug = $postModel->insertPost($title, $date, $link, $description, $postImage)) {
            $exporter = new \Blog\Twitter\Exporter();
            $exporter->publishPost($title . ' - ' . $app['url'] . '/' . $slug);
            $totalImported++;
            $imported ++;
        }
    }
    echo sprintf("Imported finished for rss %s, total imported %s \n", $rss, $imported);
}

echo sprintf("Process finished imported %s posts \n", $totalImported);
