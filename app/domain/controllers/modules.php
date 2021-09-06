<?php

namespace App\Controllers;

class ModulesController
{

    static function getModules()
    {
        $modules = [
            [
                "name" => "Configuracion",
                "route" => "",
                "childs" => [
                    [
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
