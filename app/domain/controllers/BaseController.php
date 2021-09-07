<?php 

namespace App\Controllers;

class BaseController{
    public $app = null;
    function __construct($app)
    {
        $this->app = $app;
        print_r($this->app);exit;
    }
}