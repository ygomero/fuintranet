<?php

namespace App\Guards;
//guard que verifica que existe una session de usuario iniciada

class AuthGuard{

    static function main($app){
        if(!isset($_SESSION["user"])){
            //redirigir al login 
            redirect("login");     

        }

        if($app->currentModule["path"] != '/home'){
            //Verify modules access
            $profile = $_SESSION["user"]->profile;
            
            $conn = $app->getConnection("conn1");
            $queryVerificacion = "SELECT * FROM FU_MODULES FM
            INNER JOIN FU_PERMISSIONS FP ON FM.MOD_ID = FP.MOD_ID
            WHERE FP.PROFILE_ID = ".$profile."  AND CONCAT('/',LOWER(NAME_MOD)) = '".strtolower($app->currentModule["path"])."'";
            $verify = $conn->get_row($queryVerificacion);
            if(!$verify){
                //redirect("home");
            }
        }
    }
}