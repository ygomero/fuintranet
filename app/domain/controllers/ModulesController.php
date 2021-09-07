<?php

namespace App\Controllers;

class ModulesController
{
    public $app = null;

    function __construct($app)
    {
        $this->app = $app;
    }

    function getModules()
    {
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("modulesGetAll"));

        $modules = [
            [
                "name" => "Configuracion",
                "route" => "",
                "childs" => [

                    [
                        "name" => "Usuarios",
                        "route" => "users"
                    ],
                    [
                        "name" => "Perfiles",
                        "route" => "profiles"
                    ],
                    [
                        "name" => "Bancos",
                        "route" => "banks"
                    ],


                ]
            ],
            [
                "name" => "Deposito de bancos",
                "route" => "",
                "childs" => [
                    []
                ]
            ],
            [
                "name" => "Reportes",
                "route" => "",
                "childs" => [
                    []
                ]
            ],
        ];

        return $modules;
    }
}
