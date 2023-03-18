<?php
require_once __DIR__.'/../src/pimple.php';

use Blog\Library\Gpt;

/**
 * @var Gpt $gpt
 */
$gpt = $app['gpt'];

/**
 * @var Blog\Library\GoogleClient $googleClient
 */
$googleClient = $app['google_client'];

$query = 'Articulo en www.diariotecnologia.es de 2000 palabras en html con estructura
<h1></h1>
<h3></h3>
<p></p>
';
$result = $gpt->generate($query);

$postModel = new \Blog\Model\Post($app);

if($result['title'] && $result['body']) {

    /**
     * @var \Blog\Library\Images $images
     */
    $images = $app['images'];

    $title = $result['title'];
    $link = '#';
    $description = $result['body'];
    $postImage = $images->generateImage($result['title']);
    $imageHtml = sprintf("<p class='main-image'><img src='%s'/></p>", $postImage);
    $description = $imageHtml . $description;

    echo sprintf("[INFO]Trying to publish post with title '%s'", $title);
    $slug = $postModel->insertPost($title, time(), $link, $description, $postImage);
    echo sprintf("[NOTICE]Published post with title '%s'", $title);

    if($googleClient->indexUrl($slug)) {
        echo sprintf("[NOTICE] Post '%s' indexed in google", $title);
    } else {
        echo sprintf("[ERROR] Post '%s' not indexed in google", $title);
    }

} else {
    echo sprintf("[ERROR]Cannot publish post");
}






