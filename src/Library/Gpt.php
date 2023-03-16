<?php

namespace Blog\Library;

class Gpt
{
    private $app;

    public function __construct($app){
        $this->app = $app;
    }

    public function generate($query)
    {
        $client = \OpenAI::client($this->app['gpt_key']);

        try {

            $results = $client->completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $query,
                'max_tokens' => 4000,
            ]);

        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        foreach ($results->choices as $result) {

            if (!isset($result->text)) {
                echo sprintf("[ERROR]There are no results");
                break;
            }
            /**
             * @var OpenAI\Responses\Completions\CreateResponseChoice $result
             */
            $result = $result->text;

            preg_match('/<h1.*?>(.*)<\/h1>/', $result, $subresult);

            $title = $subresult[1];
            $body = str_replace($subresult[0], '', $result);
        }

        return [
            'title' => $title,
            'body' => $body
        ];
    }
}
