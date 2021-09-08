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

    function documentos(){
        //$tipoDoc=$_POST["val-nroDocumento"];
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("tipoDocSelectAll"));
        $tipoDoc = [];
        
        foreach($results as $item){
            $tipoDoc [] =[
                "value"=>$item->COD_TD,                
                "name"=>$item->SHORT_DESCRIPTION,                
            ];
        }

        return $tipoDoc;
        
    }
        
    function update(){
        
    }

}
