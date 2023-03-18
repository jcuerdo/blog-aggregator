<?php

namespace Blog\Library;

class GoogleClient
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function indexUrl($url)
    {
        $client = new \Google\Client;

        $client->setAuthConfig($this->app['google_verification_json']);
        $client->addScope(\Google\Service\Indexing::INDEXING);


        $httpClient = $client->authorize();

        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

        $content = '{
            "url": "' . $this->app['url'] . '/' . $url . '",
            "type": "URL_UPDATED"
        }';

        $response = $httpClient->post($endpoint, ['body' => $content]);

        $status_code = $response->getStatusCode();

        return $status_code == 200;
    }
}
