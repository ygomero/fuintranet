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
        foreach ($results as $item) {
            $users[] = [
                "0" => "",
                "1" => $item->USER_NAMES,
                "2" => $item->NOMBRE,
                "3" => $item->NR_DOC,
                "4" => $item->PROFILE_ID,
                "5" => $item->WORKSTATION,
                "6" => $item->STATUS_USER,
            ];
        }

        return $users;
    }

    function documentos()
    {

        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("tipoDocSelectAll"));
        $tipoDoc = [];

        foreach ($results as $item) {
            $tipoDoc[] = [
                "value" => $item->COD_TD,
                "name" => $item->SHORT_DESCRIPTION,
            ];
        }
        return $tipoDoc;
    }

    function perfil()
    {
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("profilesSelectAll"));
        $profile = [];

        foreach ($results as $item) {
            $profile[] = [
                "value" => $item->PROFILE_ID,
                "name" => $item->PROFILE_NAME,
            ];
        }

        return $profile;
    }

    function area()
    {
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("areaSelectAll"));
        $area = [];

        foreach ($results as $item) {
            $area[] = [
                "value" => $item->AREA_ID,
                "name" => $item->NAME_ID,
            ];
        }

        return $area;
    }

    function register()
    {
        //validar datos requeridos antes de procesar, tambien validar en el front
        if(!isset($_POST["val_tipoDocumento"]) || !$_POST["val_tipoDocumento"]) return responseError("Tipo de documento es requerido");
        if(!isset($_POST["val-nroDoc"]) || !$_POST["val-nroDoc"]) return responseError("Nro de documento es requerido");
        if(!isset($_POST["val-nroContacto"]) || !$_POST["val-nroContacto"]) return responseError("Nro de contacto es requerido");
        if(!isset($_POST["val-apePat"]) || !$_POST["val-apePat"]) return responseError("Apellido paterno es requerido");
        if(!isset($_POST["val-apeMat"]) || !$_POST["val-apeMat"]) return responseError("Apellido materno es requerido");
        if(!isset($_POST["val-names"]) || !$_POST["val-names"]) return responseError("Nombres son requeridos");
        if(!isset($_POST["val-username"]) || !$_POST["val-username"]) return responseError("Username es requerido");
        if(!isset($_POST["val-password"]) || !$_POST["val-password"]) return responseError("Password es requerido");
        if(!isset($_POST["val-perfil"]) || !$_POST["val-perfil"]) return responseError("Perfil es requerido");
        if(!isset($_POST["val-area"]) || !$_POST["val-area"]) return responseError("Area es requerido");
        
        $tipoDoc = $_POST["val_tipoDocumento"];
        $nroDoc = $_POST["val-nroDoc"];
        $nroContacto = $_POST["val-nroContacto"];
        $apePat = $_POST["val-apePat"];
        $apeMat = $_POST["val-apeMat"];
        $apeName = $_POST["val-names"];
        $user = $_POST["val-username"];
        $apePass = password_hash($_POST["val-password"], PASSWORD_BCRYPT);
        $perfil = $_POST["val-perfil"];
        $area = $_POST["val-area"];

        $conn = $this->app->getConnection("conn1");

        //armar array será insertado
        $dataToInsert = [
            "USER_NAMES" => $user,
            "LAST_NAME_PAT" => $apePat
        ];

        $conn->insert("FU_USERS", $dataToInsert);

        //trae el id del registro insertado 
        $last_insert_id = $conn->last_insert_id();

        //si el last id es > 0 significa que logró registrarse
        if($last_insert_id > 0){
            return responseOK();
        }
        else{
            return responseError("Error guardando user");
        }
    }
}
