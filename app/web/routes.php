<?php

$routes =  [
    array(
        'path' => "/debug/log",
        'controller' => "home",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/",
        'controller' => "home",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/home",
        'controller' => "home",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/usuarios",
        'controller' => "usuarios",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/usuarios/nuevo",
        'controller' => "usuario-nuevo",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/usuarios/modificar",
        'controller' => "modificar-usuario",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/perfil/nuevo",
        'controller' => "perfil-nuevo",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/perfil/modificar",
        'controller' => "modificar-perfil",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/perfil",
        'controller' => "perfiles",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/bancos",
        'controller' => "bancos",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/registro",
        'controller' => "registro-deposito",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/explorador",
        'controller' => "explorador-deposito",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/seguimiento",
        'controller' => "seguimiento-deposito",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/productos%20recomendados%20-%20ventas",
        'controller' => "REPORTE DE VENTAS DE PRODUCTOS RECOMENDADOS",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/recetas%20controladas",
        'controller' => "RECETAS CONTROLADAS",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/login",
        'controller' => "login",
        'method' => "GET",
        'fullpage'=>true
    ),
    array(
        'path' => "/api/login",
        'controller' => "login",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/login/logout",
        'controller' => "login",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/reportes/ventasProductosRecomendados",
        'controller' => "reportes",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/reportes/consultaRecetasControladas",
        'controller' => "reportes",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/users/register",
        'controller' => "users",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/users/documentos",
        'controller' => "users",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/users/perfil",
        'controller' => "users",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/users/area",
        'controller' => "users",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/users/establecimiento",
        'controller' => "users",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/users",
        'controller' => "users",
        'method' => "GET",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/profile",
        'controller' => "profile",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/profile/guardar",
        'controller' => "profile",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/profile/modulosCharge",
        'controller' => "profile",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/profile/search-item",
        'controller' => "profile",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/profile/actualizar",
        'controller' => "profile",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/banks",
        'controller' => "banks",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/depositos",
        'controller' => "depositos",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/depositos/bancos",
        'controller' => "depositos",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/depositos/local",
        'controller' => "depositos",
        'method' => "GET",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/seguimientodep/local",
        'controller' => "seguimientodep",
        'method' => "GET",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/reportes/local",
        'controller' => "reportes",
        'method' => "GET",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/seguimientodep/search",
        'controller' => "seguimientodep",
        'method' => "POST",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/depositos/usuarios",
        'controller' => "depositos",
        'method' => "GET",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/depositos/usuariosResponsables",
        'controller' => "depositos",
        'method' => "GET",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/depositos/upload",
        'controller' => "depositos",
        'method' => "POST",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/depositos/cargamasiva",
        'controller' => "depositos",
        'method' => "POST",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/depositos/search",
        'controller' => "depositos",
        'method' => "POST",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/depositos/register",
        'controller' => "depositos",
        'method' => "POST",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/depositos/guardar-asociacion",
        'controller' => "depositos",
        'method' => "POST",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/testpdf",
        'controller' => "pdfconfirdep",
        'method' => "GET",
        'fullpage'=>false
    ),
    array(
        'path' => "/api/etiqueta",
        'controller' => "Etiquetafmpr",
        'method' => "GET",
        'fullpage'=>false
    ),
    
   
];
