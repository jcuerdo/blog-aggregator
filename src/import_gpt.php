<?php
require_once __DIR__.'/../src/pimple.php';

use Blog\Library\Gpt;

/**
 * @var Gpt $gpt
 */
$gpt = $app['gpt'];

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

    echo sprintf("Trying to publish post with title '%s'", $title);

    $postModel->insertPost($title, time(), $link, $description, $postImage);
} else {
    echo sprintf("Cannot publish post");
}






