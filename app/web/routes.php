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
        'path' => "/perfil/nuevo",
        'controller' => "perfil-nuevo",
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
        'controller' => "registro",
        'method' => "GET",
        'guards' => [
            "auth"
        ]
    ),
    array(
        'path' => "/explorador",
        'controller' => "explorador",
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
        'path' => "/api/profile/modulos",
        'controller' => "profile",
        'method' => "GET",
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
        'path' => "/api/depositos/local",
        'controller' => "depositos",
        'method' => "GET",
        'fullpage'=>false
    ),

    array(
        'path' => "/api/depositos/usuarios",
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
];
