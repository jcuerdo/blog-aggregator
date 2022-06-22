<?php

namespace Blog\Model;

use Doctrine\DBAL\Statement;
use PDO;

class Rss extends Db
{

    public function insert($url)
    {
        $sql = "INSERT INTO rss (url) VALUES(?)";

        try {
            $this->app['db']->executeQuery($sql, [
                $url
            ]);
            return true;
        }
        catch(\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

    public function delete($url)
    {
        $sql = "DELETE FROM rss where url = ?";

        try {
            $this->app['db']->executeQuery($sql, [
                $url
            ]);
            return true;
        }
        catch(\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }


    public function getAll()
    {
        $sql = "SELECT * FROM rss";

        try{
            $stmt = $this->app['db']->prepare($sql);
 
	    $stmt->execute();
            if ( !$result = $stmt->fetchAll() )
            {
                return array();
            }

            foreach ($result as $key => $rss){
                $result[$key] = $rss['url'];
            }

            return $result;
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();
            return [];
        }

    }
}
