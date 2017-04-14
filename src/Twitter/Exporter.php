<?php

namespace Blog\Twitter;

class Exporter
{
    public function publishPost($status, $image = null)
    {
        \Codebird\Codebird::setConsumerKey("n6zRGJk1d1PVY9if1UBJnyWB0", "hPnpabm8fUeeg1FR0HS3p0zvRdanJ90w0yRDch2NJACqr6Qtai");
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken("1886557171-VdAyNjORhEz1laz6N27LlQCYWhh2pqu2GPddDiI", "Cet3UAyQG67AfR7Ni5vabd2xbX7ESau5zUc19EfjdcC1B");

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
