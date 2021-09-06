<?php

$serverName = "192.168.1.5";

$connectionInfo = array( "Database"=>"BD_INFORMES", "UID"=>"fu_consultas", "PWD"=>"Fusac2021");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$connectionInfo2 = array( "Database"=>"LOLFAR9000", "UID"=>"fu_consultas", "PWD"=>"Fusac2021");
$conn2 = sqlsrv_connect( $serverName, $connectionInfo2);

?>