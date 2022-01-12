<?php

namespace App\Controllers;
use App\Controllers\ModulesController;
use App\Log\Logger;

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
            $editar = '';
            $status='';
            if ($item->STATUS_PROFILE===1){
                $status='ACTIVO';
            }else{
                $status='INACTIVO';
            }

            $editar='<div class="d-flex">
						<a href="/perfil/modificar?id='.$item->PROFILE_ID.'" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
                    </div>';

            $profiles[] = [
                "0"=>$editar,
                "1"=>utf8_encode($item->PROFILE_NAME),
                "2"=>utf8_encode($item->PROFILE_DESCRIPTION),
                "3"=>$status,
            ];
        }
          
        return $profiles;
    }


    function modulosCharge()
    {
        $modulesCtrl = new ModulesController($this->app);
        $modules = $modulesCtrl->getModules();
        $modules = json_decode(json_encode($modules));

        $tabla = '<table  class="display" style="min-width: 845px">'; 

        foreach ($modules as $modulo) { 
           $name = "permisos[".$modulo->id."]";
           $tabla .= '<thead>
           <tr>
             <th scope="col" colspan="3">
                <div class="form-check">
                    <label class="form-check-label" for="flexCheckChecked" style="margin-right: 5%;"> '. $modulo->name .'</label>
                    <input class="form-check-input" type="checkbox" checked="" id="'.$modulo->id.'" name="'.$name.'" value="'.$modulo->id.'">
                </div>
             </th>
           </tr>
           </thead>';
           $tabla .= '<tbody>';
            foreach($modulo->childs as $submodulo){
                $name = "permisos[".$submodulo->id."]";
                $tabla .= '<tr>
                <td>&nbsp;
                </td>
                <td>'.$submodulo->name.'</td>
                <td>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked="" id="'.$submodulo->id.'" name="'.$name.'" value="'.$submodulo->id.'">
                        <label class="form-check-label" for="flexCheckChecked">
                        </label>
                    </div>
                    </td>
                 </tr>';
            }

           $tabla .= '</tbody>'; 
        } 
        $tabla .= '</table>';
        return $tabla;
    }

    function guardar()
    { 
        if (!isset($_POST["val-perfil"]) || !$_POST["val-perfil"]) return responseError("Perfil es requerido");
        if (!isset($_POST["val-descripcion"]) || !$_POST["val-descripcion"]) return responseError("Descripcion es requerido");
        $modulosSeleccionados = [];
        if(isset($_POST["permisos"])){
            $modulosSeleccionados = $_POST["permisos"];
        }

        $perfil = $_POST["val-perfil"];
        $descripcion = $_POST["val-descripcion"];

        $estado = 0;
        if(isset($_POST["val-enable"])){
            $estado = $_POST["val-enable"];
        }

        $conn = $this->app->getConnection("conn1");

        //insert o update de perfil
        $dataToInsert = [
            "PROFILE_NAME"  => $perfil,
            "PROFILE_DESCRIPTION" => $descripcion,
            "STATUS_PROFILE"      => $estado,
        ];

        Logger::info($dataToInsert);
        $idPerfil = $this->registroPerfil($dataToInsert);

        if(!$idPerfil){
            return responseError("Error al guardar perfil");
        }

        //desactivar todos los permisos para el perfil 

        $scriptUpdateProfile = " UPDATE FU_PERMISSIONS SET PER_DEFAULT = 0 WHERE PROFILE_ID = ". $idPerfil;
        $conn->query($scriptUpdateProfile);
        
        //insert o update de permisos
        
        foreach($modulosSeleccionados as $modulo){
            //verificar si existe o no el permiso

            $queryVerificacion = " SELECT * FROM FU_PERMISSIONS WHERE PROFILE_ID = ". $idPerfil ." AND MOD_ID = ".$modulo;
            $verify = $conn->get_row($queryVerificacion);
           
            if($verify){
                
                $scriptUpdateProfile = " UPDATE FU_PERMISSIONS SET PER_DEFAULT = 1 WHERE PROFILE_ID = ". $idPerfil ." AND MOD_ID = ".$modulo;;
                $conn->query($scriptUpdateProfile);
            }
            else{
               
                $registerPermiso = $this->registroPermiso([
                    "PROFILE_ID" => $idPerfil,
                    "MOD_ID" => $modulo,
                    "PER_DEFAULT" => 1
                ]);
            }
        }
        return responseOK();
    }

    function actualizar()
    { 
        if (!isset($_POST["idProfile"]) || !$_POST["idProfile"]) return responseError("idPerfil es requerido");
        if (!isset($_POST["val-perfil"]) || !$_POST["val-perfil"]) return responseError("Perfil es requerido");
        if (!isset($_POST["val-descripcion"]) || !$_POST["val-descripcion"]) return responseError("Descripcion es requerido");
        $modulosSeleccionados = [];
        if(isset($_POST["permisos"])){
            $modulosSeleccionados = $_POST["permisos"];
        }

        $idPerfil = $_POST["idProfile"];
        $perfil = $_POST["val-perfil"];
        $descripcion = $_POST["val-descripcion"];

        $estado = 0;
        if(isset($_POST["val-enable"])){
            $estado = $_POST["val-enable"];
        }

        $conn = $this->app->getConnection("conn1");

        //verificar que el perfil exista
        $query = $this->app->getQuery("profilesSearchById"); 
        $query = str_replace("{{idProfile}}",$idPerfil,$query);
        $profileResult = $conn->get_row($query);
        if(!$profileResult){
            return responseError("Perfil no encontrado");
        }

        //update de perfil
        $dataToUpdate = [
            "PROFILE_NAME"  => $perfil,
            "PROFILE_DESCRIPTION" => $descripcion,
            "STATUS_PROFILE"      => $estado,
        ];

        Logger::info($dataToUpdate);
        $where = ["PROFILE_ID" => $idPerfil];
        $this->updatePerfil($dataToUpdate, $where);

        //desactivar todos los permisos para el perfil 

        $scriptUpdateProfile = " UPDATE FU_PERMISSIONS SET PER_DEFAULT = 0 WHERE PROFILE_ID = ". $idPerfil;
        $conn->query($scriptUpdateProfile);
        
        //insert o update de permisos
        
        foreach($modulosSeleccionados as $modulo){
            //verificar si existe o no el permiso

            $queryVerificacion = " SELECT * FROM FU_PERMISSIONS WHERE PROFILE_ID = ". $idPerfil ." AND MOD_ID = ".$modulo;
            $verify = $conn->get_row($queryVerificacion);
           
            if($verify){
                
                $scriptUpdateProfile = " UPDATE FU_PERMISSIONS SET PER_DEFAULT = 1 WHERE PROFILE_ID = ". $idPerfil ." AND MOD_ID = ".$modulo;;
                $conn->query($scriptUpdateProfile);
            }
            else{
               
                $registerPermiso = $this->registroPermiso([
                    "PROFILE_ID" => $idPerfil,
                    "MOD_ID" => $modulo,
                    "PER_DEFAULT" => 1
                ]);
            }
        }
        return responseOK();
    }

    function registroPerfil($data)
    {
        $conn = $this->app->getConnection("conn1");

        $types = [];

        $conn->insert("FU_PROFILE", $data, $types);

        //trae el id del registro insertado 
        $last_insert_id = $conn->last_insert_id();

        //si el last id es > 0 significa que logró registrarse
        if ($last_insert_id > 0) {
            return $last_insert_id ;
        } else {
            Logger::error($conn->has_error());
            return false;
        }

    }
    function updatePerfil($data, $where)
    {
        $conn = $this->app->getConnection("conn1");

        $types = [];

        $conn->update("FU_PROFILE", $data, $where, $types);

        if ($conn->has_error()) {
            Logger::error($conn->has_error());
            return false;
        }
        return true;
    }
    function registroPermiso($data)
    {
        $conn = $this->app->getConnection("conn1");

        $types = [
            "MOD_ID" => ["varchar", "NULLABLE"],
        ];

        $conn->insert("FU_PERMISSIONS", $data, $types);

        //trae el id del registro insertado 
        $last_insert_id = $conn->last_insert_id();

        //si el last id es > 0 significa que logró registrarse
        if ($last_insert_id > 0) {
            return $last_insert_id ;
        } else {
            Logger::error($conn->has_error());
            return false;
        }

    }

    function searchItem(){

        if(!isset($_POST["idProfile"]) || !($_POST["idProfile"] > 0)){
            return responseError("parameter error");
        }

        $conn = $this->app->getConnection("conn1");
        $query = $this->app->getQuery("profilesSearchById");
        $idProfile = intval($_POST["idProfile"]);
        $query = str_replace("{{idProfile}}",$idProfile,$query);
        
        $perfil = $conn->get_row($query);

        $query = "SELECT * FROM FU_MODULES FM
        INNER JOIN FU_PERMISSIONS FP ON FM.MOD_ID = FP.MOD_ID
        WHERE  FP.PER_DEFAULT = 1 AND FP.PROFILE_ID = ".$idProfile;

        $permisos = $conn->get_results($query);

        return responseOK(["perfil"=>$perfil,"permisos"=>$permisos]);
    }
}
