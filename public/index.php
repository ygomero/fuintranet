<?php

// Include  class
include('../app/shared/core/App.php');
include('../app/shared/common/defines.php');
include('../app/shared/core/Route.php');
include('../app/shared/core/View.php');
include('../app/shared/core/Session.php');
// include('../app/db/conn.php');
include('../app/db/db.php');

$conn = new SQLSRV_DataBase("fu_consultas", "Fusac2021", "BD_INFORMES", "192.168.1.5", 1433);
$conn2 = new SQLSRV_DataBase("fu_consultas", "Fusac2021", "LOLFAR9000", "192.168.1.5", 1433);

//esta seccion es para probar el query
$query = "SELECT * FROM FU_MODULES WHERE LEVEL_MOD=1";
$results = $conn->get_results( $query);
//var_dump($results);exit;
//fin 

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
