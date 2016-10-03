<?php

namespace Blog\Model;

use Silex\Application;

class Db
{
    protected $app = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}