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

$topics = [
    "consejos de arquitectura de software",
    "desarrollo de microservicios",
    "arquitectura hexagonal",
    "importancia del Pair y del mob programming",
    "como usar clean code y buenas practicas de programacion",
    "usos de chatgpt para programadores",
    "consejos para ser un buen engineer manager",
    "consejos para ser un buen software engineer",
    "una tecnologia relaccionada con php",
    "una tecnologia relaccionada con javascript",
    "una tecnologia relaccionada con java",
    "una tecnologia relaccionada con golang",
    "una tecnologia relaccionada con devops",
    "testing de aplicaciones web y moviles",
    "un framework web",
    "optimizacion de bases de datos",
    "programación asíncrona usando rabbitmq o kafka",
    "algoritmia avanzada en cualquier lenguaje de programacion",
        "alerting y monitoring en un saas",
        "tutorial de uso de tecnologia aws",
            "escalar aplicaciones con aws",
                "escalar aplicaciones con azure",

];


$query = sprintf('Articulo bien estructurado sobre %s de 2000 palabras en html con estructura
<h1></h1>
<h3></h3>
<p></p>
', $topics[array_rand($topics)]);
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






