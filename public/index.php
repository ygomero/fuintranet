<?php

// Include  class
include('../app/shared/core/App.php');
include('../app/shared/common/defines.php');
include('../app/shared/core/Route.php');
include('../app/shared/core/View.php');
include('../app/shared/core/Session.php');

$app = new App();
$app->load_guards([
    "auth"
]);


// Add base route (startpage)
Route::add('/', function () {
    echo View::render("home");
},"get",["auth"]);

Route::add('/home', function () {
    echo View::render("home");
},"get",["auth"]);

Route::add('/profile', function () {
    echo View::render("profile");
},"get",["auth"]);

Route::add('/login', function () {
    echo View::render("login",true);
});

//pagina que se mostrará cuando no se encuentra ninguna ruta
Route::pathNotFound(function () {
    echo View::render("404",true);
});

Route::run('/');
