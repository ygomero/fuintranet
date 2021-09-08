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
        foreach($results as $item){
            $users[] = [
                "0"=>"",
                "1"=>$item->USER_NAMES,
                "2"=>$item->NOMBRE,
                "3"=>$item->NR_DOC,
                "4"=>$item->PROFILE_ID,
                "5"=>$item->WORKSTATION,
                "6"=>$item->STATUS_USER,
            ];
        }
           
        return $users;
    }
}
