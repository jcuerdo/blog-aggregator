<?php

namespace Blog\Model;

class Post extends Db
{
    public function insertVideo($content)
    {
        $sql = "INSERT INTO posts (content) VALUES(?)";

        try {
            $this->app['db']->executeQuery($sql, array($content));
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    public function getPosts()
    {
        $sql = "SELECT * FROM posts";

        try{
            $stmt = $this->app['db']->executeQuery($sql);

            if ( !$result = $stmt->fetchAll() )
            {
                return array();
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