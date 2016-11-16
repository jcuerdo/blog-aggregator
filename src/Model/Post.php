<?php

namespace Blog\Model;

use Doctrine\DBAL\Statement;

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



    public function getPosts($page = 0, $limit = 10 )
    {
        $sql = "SELECT * FROM post ORDER BY date DESC LIMIT ?,?";

        try{
            $stmt = $this->app['db']->executeQuery($sql, [$page*$limit,$limit]);

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
        $cacheKey = 'index' . '-' . $searchTerm . '-' . $page;
        if(!$this->app['debug'] && \apc_exists($cacheKey))
        {
            return  \apc_fetch($cacheKey);
        }
        $sql = "SELECT * FROM post WHERE content like ? or title like ? ORDER BY date DESC LIMIT ?,?";
        $searchTerm = '%' . $searchTerm . '%';
        try{
            /**
             * @var $stmt Statement
             */
            $stmt = $this->app['db']->executeQuery($sql, [$searchTerm,$searchTerm,$page*$limit,$limit]);

            if ( !$result = $stmt->fetchAll() )
            {
                return array();
            }
            if(!$this->app['debug']){
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
        if(!$this->app['debug'] && \apc_exists($slug))
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
            if(!$this->app['debug']){
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
