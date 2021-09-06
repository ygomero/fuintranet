<?php

namespace App\Controllers;

class ModulesController
{

    static function getModules()
    {
        $query = "SELECT * FROM FU_MODULES WHERE LEVEL_MOD=1";
        $data = [];

        print_r($query);

        $modules = [
            [
                "name" => "",
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
