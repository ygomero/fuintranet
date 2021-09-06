<?php

namespace App\Guards;
//guard que verifica que existe una session de usuario iniciada

class AuthGuard{

    static function main(){
        if(!isset($_SESSION["user"])){
            //redirigir al login            
        }
    }
}