<?php

namespace Blog\Model;

use Doctrine\DBAL\Statement;
use PDO;

class Visit extends Db
{

    public function insert($url, $agent, $ip, $extra = '')
    {
        $sql = "INSERT INTO visit (url,agent,ip,extra) VALUES(?,?,?,?)";

        try {
            $this->app['db']->executeQuery($sql, [
                $url,
                $agent,
                $ip,
                $extra
            ]);
            return true;
        }
        catch(\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

}
