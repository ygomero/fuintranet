<?php

namespace App\Controllers;

class ModulesController
{
    public $app = null;

    function __construct($app)
    {
        $this->app = $app;
    }

    function nivel1($module){
        $lenId = strlen($module->MOD_ID);
        if($lenId==2) return true;
        return false;
    }
    function nivel2($module){
        $lenId = strlen($module->MOD_ID);
        if($lenId==4) return true;
        return false;
    }

    function getModules()
    {
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("modulesSelectAll"));

    
        $modulos = [];
        $nivel1 = array_filter($results, function($module){
            $lenId = strlen($module->MOD_ID);
             if($lenId==2) return true;
             return false;
         });

        $nivel2 = array_filter($results, function($module){
            $lenId = strlen($module->MOD_ID);
            if($lenId==4) return true;
            return false;
        });
       
        foreach($nivel1 as $item1){
            
            //primer nivel
            $module1 = [
                "id" => $item1->MOD_ID,
                "name"=>UTF8_DECODE($item1->NAME_MOD),
                "route"=>"/".$item1->NAME_MOD,
                "childs"=>[],
            ];
            
            //buscar sgundo nivel
            foreach($nivel2 as $item2){
                
                //solo los que empiecen con el los digitos del 1 nivel
                if(substr($item2->MOD_ID, 0, 2) === $item1->MOD_ID){
                    $module2 =[
                        "id" => $item2->MOD_ID,
                        "name"=>$item2->NAME_MOD,
                        "route"=>"/".strtolower($item2->NAME_MOD),
                        "childs"=>[],
                    ];
                    $module1["childs"][] = $module2;
                }
            }
            $modulos[] = $module1;
        }
        
        return $modulos;
    }

    function getModulesPermission()
    {
        $conn = $this->app->getConnection("conn1");
        $profile = $_SESSION["user"]->profile;
        $query = "SELECT * FROM FU_MODULES FM
                INNER JOIN FU_PERMISSIONS FP ON FM.MOD_ID = FP.MOD_ID
                WHERE FP.PROFILE_ID = ".$profile;
        
        $results = $conn->get_results($query);
    
        $modulos = [];
        $nivel1 = array_filter($results, function($module){
            $lenId = strlen($module->MOD_ID);
             if($lenId==2) return true;
             return false;
         });

        $nivel2 = array_filter($results, function($module){
            $lenId = strlen($module->MOD_ID);
            if($lenId==4) return true;
            return false;
        });
       
        foreach($nivel1 as $item1){
            
            //primer nivel
            $module1 = [
                "id" => $item1->MOD_ID,
                "name"=>UTF8_DECODE($item1->NAME_MOD),
                "route"=>"/".$item1->NAME_MOD,
                "icon"=> $item1->ICON,
                "childs"=>[],
            ];
            
            //buscar sgundo nivel
            foreach($nivel2 as $item2){
                
                //solo los que empiecen con el los digitos del 1 nivel
                if(substr($item2->MOD_ID, 0, 2) === $item1->MOD_ID){
                    $module2 =[
                        "id" => $item2->MOD_ID,
                        "name"=>$item2->NAME_MOD,
                        "route"=>"/".strtolower($item2->NAME_MOD),
                        "childs"=>[],
                    ];
                    $module1["childs"][] = $module2;
                }
            }
            $modulos[] = $module1;
        }
        
        return $modulos;
    }
}
