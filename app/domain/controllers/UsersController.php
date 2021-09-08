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

    function perfil(){
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("profilesSelectAll"));
        $profile= [];

        foreach($results as $item){
            $profile[]=[
                "value"=>$item->PROFILE_ID,
                "name"=>$item->PROFILE_NAME,
            ];
        }

        return $profile;
    }

    function area(){
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("areaSelectAll"));
        $area= [];

        foreach($results as $item){
            $area[]=[
                "value"=>$item->AREA_ID,
                "name"=>$item->NAME_ID,
            ];
        }

        return $area;
    }
        
    function register(){
        $tipoDoc = $_POST["val_tipoDocumento"];
        $nroDoc = $_POST["val-nroDoc"];
        $nroContacto = $_POST["val-nroContacto"];
        $apePat = $_POST["val-apePat"];
        $apeMat = $_POST["val-apeMat"];
        $apeName = $_POST["val-names"];
        $user = $_POST["val-username"];
        $apePass = password_hash($_POST["val-password"],PASSWORD_BCRYPT);
        $perfil = $_POST["val-perfil"];
        $area = $_POST["val-area"]; 

        $conn = INSERT("FU_USERS",[
            "USER_NAMES"=>$user,
            "LAST_NAME_PAT"=>"$apePat",
         ]);

    }

}
