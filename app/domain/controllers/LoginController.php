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

        $response = json_encode(["status"=>"ok"]);
        return $response;
    }
}