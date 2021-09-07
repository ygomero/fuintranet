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
                "name"=>UTF8_DECODE($item1->NAME_MOD),
                "route"=>$item1->NAME_MOD,
                "childs"=>[],
            ];
            
            //buscar sgundo nivel
            foreach($nivel2 as $item2){
                
                //solo los que empiecen con el los digitos del 1 nivel
                if(substr($item2->MOD_ID, 0, 2) === $item1->MOD_ID){
                    $module2 =[
                        "name"=>$item2->NAME_MOD,
                        "route"=>strtolower($item2->NAME_MOD),
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
