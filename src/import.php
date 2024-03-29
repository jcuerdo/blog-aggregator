<?php
require_once __DIR__.'/../src/pimple.php';


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
        $description=preg_replace('/style=".*?"/', '', $description);
        $description=preg_replace('/id=".*?"/', '', $description);
        $description = preg_replace("/<\\/?" . "script" . "(.|\\s)*?>/","",$description);
        $description = preg_replace("/{\"videoId(.)+}/","",$description);
        $description = preg_replace("/\(function[\s\S]*;/","",$description);
        $description = preg_replace("/La noticia[\s\S]*\./","",$description);


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

            /**
             * @var Blog\Library\GoogleClient $googleClient
             */
            $googleClient = $app['google_client'];

            if($googleClient->indexUrl($slug)) {
                echo sprintf("[NOTICE] Post '%s' indexed in google", $title);
            } else {
                echo sprintf("[ERROR] Post '%s' not indexed in google", $title);
            }
        }
    }
    echo sprintf("Imported finished for rss %s, total imported %s \n", $rss, $imported);
}

echo sprintf("Process finished imported %s posts \n", $totalImported);
