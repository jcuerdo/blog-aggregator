<?php

namespace Blog\Model;

use Doctrine\DBAL\Statement;
use PDO;

class Post extends Db
{
    /**
     * @var int
     */
    const SINGLE_POST_TTL = 10000000;
    const INDEX_TTL = 3600;

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

    public function updatePost($title, $content, $slag)
    {

        $sql = "UPDATE post set title = ?, content = ?  where slag = ?";

        try {
            $this->app['db']->executeQuery($sql, [
                $title,
                $content,
                $slag
            ]);
            return true;
        }
        catch(\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

    public function deletePost($slag)
    {

        $sql = "DELETE FROM post where slag = ?";

        try {
            $this->app['db']->executeQuery($sql, [
                $slag
            ]);
            return true;
        }
        catch(\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }



    public function getPosts($page = 0, $limit = 10 )
    {
        $start = $page * $limit;
        $sql = "SELECT * FROM post ORDER BY date DESC LIMIT :start,:limit";

        try{
            $stmt = $this->app['db']->prepare($sql);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
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
        $start = $page * $limit;
        $cacheKey = 'index' . '-' . $searchTerm . '-' . $page;
        if($this->app['apc'] && \apc_exists($cacheKey))
        {
            return  \apc_fetch($cacheKey);
        }
        $sql = "SELECT * FROM post WHERE content like :search or title like :search ORDER BY date DESC LIMIT :start ,:limit";
        $searchTerm = "%$searchTerm%";
        try{
            /**
             * @var $stmt Statement
             */
            $stmt = $this->app['db']->prepare($sql);

            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            if ( !$result = $stmt->fetchAll() )
            {
                return array();
            }

            if($this->app['apc']){
                \apc_add($cacheKey, $result, self::INDEX_TTL);
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
        if($this->app['apc'] && \apc_exists($slug))
        {
            return  \apc_fetch($slug);
        }

        $sql = "SELECT * FROM post where slag = ?";

        try{
            $stmt = $this->app['db']->executeQuery($sql, [$slug]);

            if ( !$result = $stmt->fetch() )
            {
                return null;
            }
            if($this->app['apc']){
                \apc_add($slug, $result, self::SINGLE_POST_TTL);
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
