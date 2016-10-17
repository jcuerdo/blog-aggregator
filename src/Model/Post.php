<?php

namespace Blog\Model;

use Doctrine\DBAL\Statement;

class Post extends Db
{
    public function insertPost($title, $date, $link, $content)
    {
        $slag = $this->generateSlag($title);
        $date = $this->generateDate($date);

        $sql = "INSERT INTO post (title, slag, date, link, content) VALUES(?,?,?,?,?)";

        try {
            $this->app['db']->executeQuery($sql, [
                $title,
                $slag,
                $date,
                $link,
                $content
            ]);
            return $slag;
        }
        catch(\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }



    public function getPosts($page = 0, $limit = 10 )
    {
        $sql = "SELECT * FROM post ORDER BY date DESC LIMIT $page,$limit";

        try{
            $stmt = $this->app['db']->executeQuery($sql, []);

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

    public function searchPosts($searchTerm = '', $page = 0, $limit = 10 )
    {
        $sql = "SELECT * FROM post WHERE content like ? or title like ? ORDER BY date DESC LIMIT $page,$limit";
        $searchTerm = '%' . $searchTerm . '%';
        try{
            /**
             * @var $stmt Statement
             */
            $stmt = $this->app['db']->executeQuery($sql, [$searchTerm,$searchTerm]);

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


    public function getPost($slug)
    {
        $sql = "SELECT * FROM post where slag = ?";

        try{
            $stmt = $this->app['db']->executeQuery($sql, [$slug]);

            if ( !$result = $stmt->fetch() )
            {
                return null;
            }

            return $result;
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();
            return null;
        }

    }

    private function generateSlag($title)
    {
        $title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
        $title = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $title);
        $title = strtolower(trim($title, '-'));
        $title = preg_replace("/[\/_|+ -]+/", '-', $title);
        return $title;
    }

    private function generateDate($date)
    {
        $time = time();

	    return date ('Y-m-d H:i:s' , $time);
    }

}
