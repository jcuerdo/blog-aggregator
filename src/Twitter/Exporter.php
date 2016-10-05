<?php

namespace Blog\Twitter;

class Exporter
{
    public function publishPost($status, $image = null)
    {
        \Codebird\Codebird::setConsumerKey("", "");
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken("", "");

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