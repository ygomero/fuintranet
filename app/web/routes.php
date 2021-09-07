<?php

$routes =  [
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
        'path' => "/usuarios/nuevoUsuario.html",
        'controller' => "nuevoUsuario",
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
        'path' => "/api/users",
        'controller' => "users",
        'method' => "GET",
        'fullpage'=>false
    ),
];
