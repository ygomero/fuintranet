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

        $user = $_POST["user"];
        $password = $_POST["password"];

        //validaciones 

        $conn = $this->app->getConnection("conn1");
        $result = $conn->get_row("SELECT 
                                    PASS,USER_NAMES,FU.PROFILE_ID AS PROFILE_ID,CONCAT(LAST_NAME_PAT,' ', LAST_NAME_MAT,' ', NAMES) as NAME, FP.PROFILE_NAME AS PROFILE_NAME
                                FROM FU_USERS FU
                                INNER JOIN FU_PROFILE FP ON FU.PROFILE_ID = FP.PROFILE_ID WHERE USER_NAMES='".$user."'"); 

        if($result==false)
        {
            return responseError('USUARIO INVALIDO');
        }

        $resultp = password_verify($password,$result->PASS);

        if($resultp==false)
        {
            return responseError('CONTRASEÑA INCORRECTA');
        }

        $oUser = new stdClass(); 
        $oUser->usuario = $result->USER_NAMES; 
        $oUser->name = $result->NAME; 
        $oUser->namePerfil = $result->PROFILE_NAME; 
        $oUser->profile = $result->PROFILE_ID;
        $_SESSION["user"] = $oUser;
        //guardar en tabla session
        //

        return responseOK();
    }

    function logout(){
        unset($_SESSION["user"]);
        $response = ["status"=>"ok"];
        return $response;
    }
}