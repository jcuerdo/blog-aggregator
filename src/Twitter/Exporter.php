<?php

namespace Blog\Twitter;

class Exporter
{
    public function publishPost($status, $image = null)
    {
        \Codebird\Codebird::setConsumerKey("kNAUU4Zslpe9v2YJ3jxepSKIZ", "up7nqcx3TIuoUGpo4pmfydHpaIKHqlKzn1lst11ZyBoCSpw8Ql");
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken("1048102562858242050-rHosneX7iKpQrZus15AG3yBzausXMW", "KSRRlkCPkzRs6kQn8eoeei5rLVk9Bi8rbggzc4PdDvlqZ");

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
