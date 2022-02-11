<?php
require '../vendor/autoload.php';
//require siempre que el código sea importante (Funciones reutilizables de PHP, configuraciones…), 
//include  en casos en los que el código no es vital para la ejecución del script (cabeceras y pies HTML o similares).
include('../app/shared/common/defines.php');
require('../app/shared/core/Logger.php');
require('../app/shared/core/Functions.php');
require('../app/shared/core/Session.php');
require('../app/shared/core/View.php');
require('../app/shared/core/App.php');
require('../app/web/routes.php');
require('../app/shared/core/Route.php');
require('../app/db/conn.php');
require('../app/db/query.php');
/*print_r(password_hash('41594916',PASSWORD_BCRYPT));
/*var_dump(password_verify('YADIRA','$2y$10$Pz/NFAj5V9N6sEPONlzUQu/7l6mn2UcYyVwPNQ65UuCQpw0FLGjuq'));
exit;*/
date_default_timezone_set('America/Bogota');

$app = new App();
$app->addConnection("conn1",$conn);
$app->addConnection("conn2",$conn2);
$app->addRoutes($routes);
$app->pathNotFound(function () {
    View::render("404",true);
});
$app->addQuerys($querys);

View::setApp($app);
$app->process();