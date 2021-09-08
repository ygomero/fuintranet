<?php 

namespace App\Controllers;

use stdClass;

class LoginController{

    private $app = null;

    function __construct($app)
    {
        $this->app = $app;
    }

    function main(){
        $user = new stdClass();
        $user->name = "Yadira";
        $_SESSION["user"] = $user;
        $response = ["status"=>"ok"];
        return $response;
    }

    function logout(){
        unset($_SESSION["user"]);
        $response = ["status"=>"ok"];
        return $response;
    }
}