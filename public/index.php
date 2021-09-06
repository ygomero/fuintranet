<?php

// Include  class
include('../app/shared/core/App.php');
include('../app/shared/common/defines.php');
include('../app/shared/core/Route.php');
include('../app/shared/core/View.php');
include('../app/shared/core/Session.php');
include('../app/db/conn.php');

$app = new App();
$app->load_guards([
    "auth"
]);

$app->load_controllers([
    "modules"
]);

// Add base route (startpage)
Route::add('/', function () {
    echo View::render("home");
},"get",["auth"]);

Route::add('/home', function () {
    echo View::render("home");
},"get",["auth"]);

Route::add('/modules', function () { 
    echo View::render("modules");
},"get",["auth"]);

Route::add('/usuarios',function(){
    echo View::render("usuarios");
},"get",["auth"]);

Route::add('/perfiles', function () {
    echo View::render("perfiles");
},"get",["auth"]);

Route::add('/bancos', function () {
    echo View::render("bancos");
},"get",["auth"]);

Route::add('/login', function () {
    echo View::render("login",true);
});

//pagina que se mostrará cuando no se encuentra ninguna ruta
Route::pathNotFound(function () {
    echo View::render("404",true);
});

Route::run('/');
