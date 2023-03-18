<?php

namespace Blog\Library;

class Images
{
    private $app;

    public function __construct($app){
        $this->app = $app;
    }

    public function generateImage($query){
        $result = file_get_contents(
            sprintf(
                "https://api.unsplash.com/search/photos?page=1&query=%s&per_page=1&lang=es&page=1&client_id=%s",
                urlencode($query),
                $this->app['unsplash_key'],
            )
        );

        $result = json_decode($result, true);

        if (isset($result['results'][0]['urls']['regular'])) {
            $image = $result['results'][0]['urls']['regular'];
        }

        return $image ?? null;
    }

}
