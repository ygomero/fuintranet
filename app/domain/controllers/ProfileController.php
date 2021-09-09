<?php

namespace App\Controllers;

class ProfileController
{
    public $app = null;

    function __construct($app)
    {
        $this->app = $app;
    }

 
    function main()
    {
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("profilesSelectAll"));
        $profiles = [];
        foreach($results as $item){
            $profiles[] = [
                "0"=>"",
                "1"=>$item->PROFILE_NAME,
                "2"=>$item->PROFILE_DESCRIPTION,
                "3"=>$item->STATUS_PROFILE,
            ];
        }
          
        return $profiles;
    }

    function modulos()
    {

        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("modulesSelectAll"));
        $tipoDoc = [];

        foreach ($results as $item) {
            $tipoDoc[] = [
                "value" => $item->MOD_ID,
                "name" => $item->NAME_MOD,
            ];
        }
        return $tipoDoc;
    }

}
