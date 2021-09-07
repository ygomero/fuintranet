<?php

namespace App\Controllers;

class UsersController
{
    public $app = null;

    function __construct($app)
    {
        $this->app = $app;
    }

 
    function main()
    {
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("usersSelectAll"));

        
        $users = [];
               
        return json_encode($results);
    }
}
