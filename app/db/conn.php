<?php
include('../app/db/db.php');

$serverName = "192.168.1.5";

$conn = new SQLSRV_DataBase("fu_consultas", "Fusac2021", "BD_INFORMES", $serverName, 1433);
$conn2 = new SQLSRV_DataBase("fu_consultas", "Fusac2021", "LOLFAR9000", $serverName, 1433);

?>