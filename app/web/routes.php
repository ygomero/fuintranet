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
];
