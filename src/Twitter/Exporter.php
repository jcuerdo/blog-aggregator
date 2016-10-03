<?php

namespace Blog\Twitter;

class Exporter
{
    public function publishPost($status, $image = null)
    {
        \Codebird\Codebird::setConsumerKey("6qA4YOU7mxDobBZA9VitBRxWv", "3OsEiA4dPjLrWwIECkY9icv6oeIRLQgfziMMnL7w3su5J5mKC1");
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken("1202191764-xl5G1qRO2b60ZMOYwQQHlhIL9rMjBbK4ZNdJtor", "JrxQxO6m6jnakfgFQS5jYBAr6klDG0Eu6rSFPwbVCTSnV");

        $params = array(
            'status' => $status
        );
        if($image) {
            $params['media[]'] = $image;
            $reply = $cb->statuses_updateWithMedia($params);
        }
        else
        {
            $reply = $cb->statuses_update($params);
        }


        return $reply;
    }
}