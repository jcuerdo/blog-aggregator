<?php

namespace Blog\Model;

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
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    public function getPosts()
    {
        $sql = "SELECT * FROM post";

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
        return strtotime($date);
    }

}