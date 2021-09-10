<?php 

$querys = [
    "modulesSelectAll"  => "SELECT * FROM FU_MODULES",
    "usersSelectAll"    => "SELECT USER_NAMES, CONCAT(LAST_NAME_PAT,' ',LAST_NAME_MAT,' ',NAMES) AS NOMBRE, NR_DOC, PROFILE_ID, WORKSTATION,STATUS_USER FROM FU_USERS ",
    "profilesSelectAll" => "SELECT * FROM FU_PROFILE",
    "banksSelectAll"    => "SELECT * FROM FU_BANK_ACCOUNT B
                            INNER JOIN FU_COMPANY C ON C.COMPANY_ID = B.COMPANY_ID",
    "tipoDocSelectAll"  => "SELECT * FROM FU_TIPO_DOC",
    "areaSelectAll"     => "SELECT * FROM FU_AREA"
];      