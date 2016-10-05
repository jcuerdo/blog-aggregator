<?php

namespace Blog\Twitter;

class Exporter
{
    public function publishPost($status, $image = null)
    {
        \Codebird\Codebird::setConsumerKey("YuPk4DmS6toGxkHx1D1SNw", "1eypDfHR90EUYGMUa7HdVHSxXeI9IkcAaAwkqP8Eo");
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken("1886557171-Zaq8aoMR71FzlBvPZpAp2Vi6YtqqYXc6FN0HEm9", "dW6RafSITZLzVuED5rkkRetWyOMun9RMywI0KJbR1E");

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