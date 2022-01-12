<?php

namespace App\Controllers;
use App\Log\Logger;
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
            $editar='';

            $status='';
            if ($item->STATUS_USER===1){
                $status='ACTIVO';
            }else{
                $status='INACTIVO';
            }
            
            $editar='<div class="d-flex">
						<a href="/usuarios/modificar" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
                    </div>';

            $users[] = [
                "0" => $editar,
                "1" => utf8_encode($item->USER_NAMES),
                "2" => utf8_encode($item->NOMBRE),
                "3" => $item->NR_DOC,
                "4" => utf8_encode($item->PROFILE_DESCRIPTION),
                "5" => utf8_encode($item->WORKSTATION),
                "6" => $status,
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
                "name" => utf8_encode($item->PROFILE_NAME),
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

    function establecimiento()
    {
        $conn = $this->app->getConnection("conn2");
        $results = $conn->get_results($this->app->getQuery("localSelectAll"));
        $establecimiento = [];

        foreach($results as $item){
            $establecimiento[]=[
                "value" => $item->SISCOD,
                "name" => $item->SISENT,
            ];
        }

        return $establecimiento;
    }

    function register()
    {
        
        if(!isset($_POST["val-tipoDocumento"]) || !$_POST["val-tipoDocumento"]) return responseError("Tipo de documento es requerido");
        if(!isset($_POST["val-nroDoc"]) || !$_POST["val-nroDoc"]) return responseError("Nro de documento es requerido");
        if(!isset($_POST["val-nroContacto"]) || !$_POST["val-nroContacto"]) return responseError("Nro de contacto es requerido");
        if(!isset($_POST["val-apePat"]) || !$_POST["val-apePat"]) return responseError("Apellido paterno es requerido");
        if(!isset($_POST["val-apeMat"]) || !$_POST["val-apeMat"]) return responseError("Apellido materno es requerido");
        if(!isset($_POST["val-names"]) || !$_POST["val-names"]) return responseError("Nombres son requeridos");
        if(!isset($_POST["val-username"]) || !$_POST["val-username"]) return responseError("Username es requerido");
        if(!isset($_POST["val-password"]) || !$_POST["val-password"]) return responseError("Password es requerido");
        if(!isset($_POST["val-confirm-password"]) || !$_POST["val-confirm-password"]) return responseError("Confirmacion Password es requerido");
        if($_POST["val-password"] != $_POST["val-confirm-password"]) return responseError("Password y Confirmar Password NO SON IGUALES");
        if(!isset($_POST["val-perfil"]) || !$_POST["val-perfil"]) return responseError("Perfil es requerido");
        if(!isset($_POST["val-area"]) || !$_POST["val-area"]) return responseError("Area es requerido");
        if(!isset($_POST["val-cargo"]) || !$_POST["val-cargo"]) return responseError("Cargo es requerido");
        if(!isset($_POST["val-establecimiento"]) || !$_POST["val-establecimiento"]) return responseError("Establecimiento es requerido");
        
        $tipoDoc = strval($_POST["val-tipoDocumento"]);
        $nroDoc = $_POST["val-nroDoc"];
        $nroContacto = $_POST["val-nroContacto"];
        $apePat = $_POST["val-apePat"];
        $apeMat = $_POST["val-apeMat"];
        $apeName = $_POST["val-names"];
        $user = $_POST["val-username"];
        $apePass = password_hash($_POST["val-password"],PASSWORD_BCRYPT);
        $perfil = $_POST["val-perfil"];
        $area = $_POST["val-area"];
        $cargo = $_POST["val-cargo"];
        $establecimiento = $_POST["val-establecimiento"];
        $estado = 0;
        if(isset($_POST["val-enable"])){
            $estado = $_POST["val-enable"];
        }

        

        $conn = $this->app->getConnection("conn1");
        //Validaciones
        $query = "SELECT USER_NAMES FROM FU_USERS WHERE USER_NAMES ='".$user."'";
        $query2 = $conn->get_results($this->app->getQuery("tipoDocSelectAll"));

        $validacionUser = $conn->get_row($query);
        if ($validacionUser === false){
            //error interno
        }
        if ($validacionUser){
            //error que existe ya el user
            return responseError("El usuario ya existe, intente nuevamente");
        }

        //armar array será insertado
        $dataToInsert = [
            "USER_NAMES"    => $user,
            "LAST_NAME_PAT" => $apePat,
            "LAST_NAME_MAT" => $apeMat,
            "NAMES"         => $apeName,
            "COD_TD"        => $tipoDoc,
            "NR_DOC"        => $nroDoc,
            "NR_CONTACT"    => $nroContacto,
            "WORKSTATION"   => $cargo,
            "PROFILE_ID"    => $perfil,
            "STATUS_USER"   => $estado,
            "AREA_ID"       => $area,
            "PASS"          => $apePass,
            "ESTABLISHMENT" => $establecimiento,

        ];

       /* print_r($dataToInsert);
        exit;*/

        $result = $this->registerUser($dataToInsert); 
        
        if (!$result) {
            return responseError("ERROR GUARDANDO USUARIO");
            exit;
        } 
        return responseOK();

    }
    function registerUser($data)
    {
        $conn = $this->app->getConnection("conn1");

        $types = [
            "COD_TD" => ["varchar", "NULLABLE"],
        ];

        $conn->insert("FU_USERS", $data, $types);

        //trae el id del registro insertado 
        $last_insert_id = $conn->last_insert_id();

        //si el last id es > 0 significa que logró registrarse
        if ($last_insert_id > 0) {
            return true;
        } else {
            Logger::error($conn->has_error());
            return false;
        }

    }

    function modifyUser(){

    }
}
