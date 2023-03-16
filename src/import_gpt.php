<?php
require_once __DIR__.'/../src/pimple.php';


use Blog\Library\Gpt;

/**
 * @var Gpt $gpt
 */
$gpt = $app['gpt'];



$query = 'Articulo en www.diariotecnologia.es de 2000 palabras en html con estructura
<h1></h1>
<h2></h2>
<p></p>

escrito por un profesional
';
$result = $gpt->generate($query);

$postModel = new \Blog\Model\Post($app);

if($result['title'] && $result['body']) {
    $title = $result['title'];
    $link = '#';
    $description = $result['body'];
    $postImage = null;

    echo sprintf("Trying to publish post with title '%s'", $title);

    $postModel->insertPost($title, time(), $link, $description, $postImage);
} else {
    echo sprintf("Cannot publish post");
}






