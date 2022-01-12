<?php

namespace App\Controllers;

use DateTime;
use App\Log\Logger;
use App\Controllers\LoginController;

class DepositosController
{
    public $app = null;

    function __construct($app)
    {
        $this->app = $app;
    }

    function main()
    {
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("depositosSelectAll"));
        // print_r($results);exit;
        $depositos = [];
        $index = 0;
        
       

        foreach ($results as $item) {
            $checkbox = '';
            if($item->SALDO > 0){
                $checkbox ='
                <div class="custom-control custom-checkbox ml-2" >
                <input type="checkbox" class="custom-control-input" onclick="agregarDeposito('.$item->DEPOSIT_ID.')" name="check_deposito" id="customCheckBox' . $index . '">
                <label class="custom-control-label" for="customCheckBox' . $index . '"></label>
                </div>
                ';
            }
            $depositos[] = [
                "0" => $checkbox,
                "1" => utf8_encode($item->NAME_BANK),
                "2" => $item->DATEOPER->format('d-m-Y'),
                "3" => $item->REFERENCE_ONE,
                "4" => $item->REFERENCE_TWO,
                "5" => $item->NR_OPERATION,
                "6" => money($item->IMPORTE),
                "7" => money($item->SALDO),
                "8" => $item->DEPOSIT_ID
            ];
            $index++;
        }
        return $depositos;
    }

    function register()
    {
        //validar datos requeridos antes de procesar, tambien validar en el front
        if (!isset($_POST["val-fecha-oper"]) || !$_POST["val-fecha-oper"]) return responseError("Fecha de operación es requerido");
        if (!isset($_POST["val_bank"]) || !$_POST["val_bank"]) return responseError("Banco es requerido");
        if (!isset($_POST["val-primer-refer"]) || !$_POST["val-primer-refer"]) return responseError("Primera referencia es requerida");
        if (!isset($_POST["val-n-oper"]) || !$_POST["val-n-oper"]) return responseError("N° de operación es requerido");
        if (!isset($_POST["val-importe"]) || !$_POST["val-importe"]) return responseError("Importe es requerido");

        $fechaOper = $_POST["val-fecha-oper"];
        $bank = $_POST["val_bank"];
        $primerRef = $_POST["val-primer-refer"];
        $segundoRef = $_POST["val-segundo-refer"];
        $nOper = $_POST["val-n-oper"];
        $importe = $_POST["val-importe"];

        $conn = $this->app->getConnection("conn1");

        $bankDetail = "SELECT * FROM FU_BANK_ACCOUNT WHERE BANK_ID=" . $bank . "";
        $resultBank = $conn->get_row($bankDetail);

        $newDate = date(SQL_DATETIME_FORMAT, strtotime($fechaOper));
        $user_reg = $_SESSION["user"]->usuario;

        //armar array será insertado
        $dataToInsert = [
            "DATE_OPERATION" => $newDate,
            "REFERENCE_ONE" => $primerRef,
            "REFERENCE_TWO" => $segundoRef,
            "IMPORTE" => $importe,
            "NR_OPERATION" => $nOper,
            "BANK_ID" => $bank,
            "NAME_BANK" => utf8_encode($resultBank->NAME_BANK),
            "NR_ACCOUNT" => strval($resultBank->NR_ACCOUNT),
            "USER_REG" => $user_reg,
            "DATE_REG" => date(SQL_DATETIME_FORMAT),
            "DATE_MOD" => '',
            "SALDO"=> $importe,
        ];

        $result = $this->registroDeposito($dataToInsert);

        if ($result) {
            return responseOK();
        } else {
            return responseError("Error guardando DEPOSITO");
        }
    }

    function bancos()
    {

        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("banksSelectAll"));
        $banks = [];

        foreach ($results as $item) {
            $banks[] = [
                "value" => $item->BANK_ID,
                "name" => utf8_encode($item->NAME_BANK),
            ];
        }

        return $banks;
    }

    function local()
    {

        $conn = $this->app->getConnection("conn2");
        $results = $conn->get_results($this->app->getQuery("localSelectAll"));
        $local = [];

        foreach ($results as $item) {
            $local[] = [
                "value" => $item->SISCOD,
                //"value" => utf8_encode($item->SISENT),
                "name" => utf8_encode($item->SISENT),
            ];
        }

        return $local;
    }

    function usuarios()
    {

        $conn = $this->app->getConnection("conn2");
        $results = $conn->get_results($this->app->getQuery("usersLolfarSelectAll")." ORDER BY usenam");
        $users = [];

        foreach ($results as $item) {
            $users[] = [
                "value" => utf8_encode($item->useusr),
                "name" => utf8_encode($item->usenam),
            ];
        }

        return $users;
    }

    function usuariosResponsables()
    {

        $conn = $this->app->getConnection("conn2");
        $results = $conn->get_results($this->app->getQuery("usersLolfarSelectAll")." AND grucod IN (
            'GDTC','GR0006','GR0012','GREG','DELYV','INT01') ORDER BY usenam");
        $users = [];

        foreach ($results as $item) {
            $users[] = [
                "value" => utf8_encode($item->useusr),
                "name" => utf8_encode($item->usenam),
            ];
        }

        return $users;
    }

    function search()
    {
        $type = 0; // 1 => por secuencia || 2 => por serie y nro documento
        if(isset($_POST["val-secuencia"]) && $_POST["val-secuencia"]!= ''){
            $secuencia = $_POST["val-secuencia"]; 
            $type = 1;
        }
        else{
            if(isset($_POST["val-serie"]) && isset($_POST["val-documento"]) && $_POST["val-serie"]!= '' && $_POST["val-documento"]!= '' ){
                $serie = $_POST["val-serie"];
                $nro_documento = $_POST["val-documento"];
                $type = 2;
            }
            else{
                return responseError("No se puede iniciar busqueda, completar todos los campos"); 
            }
        }
        
        $depositos = $_POST["depositos"];
        $depositos = json_decode($depositos);
        
        if (!count($depositos)) {
            return responseError("Depositos no seleccionados");
        }

        $depositos = implode(",", $depositos);

        $resultsDep = $this->searchDepositosTotal($depositos);
        if (!$resultsDep) {
            return responseError("Depositos no encontrados");
        }
 
        //buscar documento
        if($type ==  1 ){
            $resultDoc = $this->searchBySecuencia($secuencia);
        }
        elseif($type ==  2 ){
            $resultDoc = $this->searchBySerie($serie, $nro_documento);
        }

        if(!$resultDoc){
            return responseError("Sin resultados");
        }

        //acumulado de depositos para un el documento
        $acumuladoTotDepxDoc = $this->TotaldepositoPorDocumento($resultDoc->SECUENCIA,$resultDoc->NROSERIE,$resultDoc->DOC);

        if(!$acumuladoTotDepxDoc){
            return responseError();
        }

        $totalDepositadoDoc = ($acumuladoTotDepxDoc->IMPORTE == '')? 0 : $acumuladoTotDepxDoc->IMPORTE;
        
        //calculo de deposito vs documento
        $totalDocumento = $resultDoc->TOTAL - $totalDepositadoDoc;
        $totalDepositos = $resultsDep->IMPORTE;
        $saldoDocumento = 0;
        $saldoDeposito = 0;

        if(!($totalDocumento > 0)){
            return responseError("Documento no tiene saldo por pagar");
        }

        //caso 1 
        if ($totalDocumento < $totalDepositos) {
            $saldoDeposito = $totalDepositos - $totalDocumento;
            $totalDepositos =  $totalDocumento;
        }

        //caso 2
        if ($totalDocumento > $totalDepositos) {
            $saldoDocumento = $totalDocumento - $totalDepositos;
        }


        $documento = [];

        $documento[] = [
            "0"  => $resultDoc->SECUENCIA,
            "1"  => $resultDoc->NROSERIE,
            "2"  => $resultDoc->DOC,
            "3"  => money($resultDoc->TOTAL),
            "4"  => money($totalDepositos),
            "5"  => money($saldoDocumento),
            "6"  => UTF8_ENCODE($resultDoc->NOMCLI)
        ];

        return responseOk($documento); 
    }

    function guardarAsociacion()
    { 
        if (!isset($_POST["val_local"]) || !$_POST["val_local"]) return responseError("Local de destino es requerido");
        if (!isset($_POST["val_solicitante"]) || !$_POST["val_solicitante"]) return responseError("Solicitante es requerido");
        if (!isset($_POST["val_responsable"]) || !$_POST["val_responsable"]) return responseError("Responsable es requerido");
        if (!isset($_POST["val_documento"]) || !$_POST["val_documento"]) return responseError("Ningun documento seleccionado");
        if (!isset($_POST["val_depositos"]) || !$_POST["val_depositos"]) return responseError("Ningun deposito seleccionado");

        //descomponer documento
        $arDocumento = explode("/",$_POST["val_documento"]);

        $secuencia = $arDocumento[0];
        $serie = $arDocumento[1];
        $nro_documento = $arDocumento[2];
        $local = $_POST["val_local"];
        $solicitante = $_POST["val_solicitante"];
        $responsable = $_POST["val_responsable"];
        $depositos = $_POST["val_depositos"];
        $totalDocumento = 0;
        $totalDepositos = 0;

        $arDepositos = json_decode($depositos);
        $user_reg = $_SESSION["user"]->usuario;
        
        if (!count($arDepositos)) {
            return responseError("Depositos no seleccionados");
        }

        $depositos = implode(",", $arDepositos);

        //**Validaciones  */
        //el documento debe existir
        $validacionDoc = $this->searchBySecuencia($secuencia);
        
        if(!$validacionDoc){
            return responseError("Documento no válido");
        }

        $totalDocumento = $validacionDoc->TOTAL;

        //los depositos deben existir y tener saldo
        $validacionDep = $this->searchDepositos($depositos);

        if(!$validacionDep){
            return responseError("Deposito no válido");
        }

        foreach($validacionDep as $itemDep){
            $totalDepositos += $itemDep->SALDO;
        }

        if($totalDepositos == 0){
            return responseError("Depósitos sin saldo");
        }

        //Validación de Total del deposito por documento
        $validacionTotDepxDoc = $this->TotaldepositoPorDocumento($secuencia,$serie,$nro_documento);

        if(!$validacionTotDepxDoc){
            return responseError();
        }
        
        $totalDepositadoDoc = ($validacionTotDepxDoc->IMPORTE == '')? 0 : $validacionTotDepxDoc->IMPORTE;
        $saldoDocumento = $totalDocumento - $totalDepositadoDoc;

        if(!($saldoDocumento > 0)){
            return responseError("Documento no tiene saldo por pagar");
        }
        
        /*
        YADIRA
        */ 
        /*$conn = $this->app->getConnection("conn2");

        $bankDetail = "SELECT * FROM FU_BANK_ACCOUNT WHERE BANK_ID=" . $bank . "";
        $resultBank = $conn->get_row($bankDetail); */

        /*
        YADIRA
        */ 
        $registros = [];
        //saldo del documento
        foreach($validacionDep as $deposito){

            if($saldoDocumento > 0){
                //monto que el deposito aporta

                $aporte = $deposito->SALDO;
                
                if($saldoDocumento < $aporte){
                    $aporte = $saldoDocumento;
                }
                
                $saldoDocumento = $saldoDocumento - $aporte;
                $saldoDeposito  = $deposito->SALDO - $aporte;
                
                $dataToInsert = [
                    "DEPOSIT_ID"    => $deposito->DEPOSIT_ID,
                    "NR_SEQUENCE"   => $secuencia,
                    "TYPE_DOC"      => '',
                    "NR_SERIE"      => $serie,
                    "NR_DOC"        => $nro_documento,
                    "FAC_TOT"       => $validacionDoc->TOTAL,
                    "IMPORTE"       => $aporte,
                    "SALDO"         => $saldoDocumento,
                    "DOC_CLIENT"    => '',
                    "NAME_CLIENT"   => utf8_encode($validacionDoc->NOMCLI),
                    "LOCAL_GUIA"    => $local,
                    "USER_APPLICANT"=> $solicitante,
                    "USER_CONFIRM"=> $responsable,
                    "DATE_CONFIRMATION" =>date(SQL_DATETIME_FORMAT),
                    "DATE_LIQUIDATE" =>'',
                    "NR_SEQUENCE_CASH" =>'',
                    "NAME_CASH" =>'',
                    "USER_REG"  =>$user_reg,
                    "USER_MOD"  =>'',
                    "DATE_REG"  =>date(SQL_DATETIME_FORMAT),
                    "DATE_MOD"  =>'',
                ];
                Logger::info($dataToInsert);
                $result = $this->registroDepositoDet($dataToInsert);

                if (!$result) {
                    return responseError("Error guardando ASOCIACION");
                    exit;
                } 

                //actualizar saldo deposito
                $dataUpdate = [
                    "SALDO"=>$saldoDeposito
                ];
                $this->updateDeposito($dataUpdate,$deposito->DEPOSIT_ID);
                //actualizar algo mas 

                
                $registros[] = $result;
            }
            else{
                break;
            }

        }
        //devuelves todo lo que se registró
        //ahora cuando registras fijate tu network que devuelve
        return responseOk(["registros"=>$registros]);
    }

    function cargamasiva()
    {
        //guardar carga masiva
        if (!isset($_POST["id_file"]) || !$_POST["id_file"]) return responseError("No se ha cargado ningun archivo");

        $idFile = $_POST["id_file"];
        $tmp_name = $idFile. '.xlsx';
        $dirFile = DIR_CACHE . DS . $tmp_name;
        if(!file_exists($dirFile)){
            return responseError("Error al cargar Depositos");
        }

        $conn = $this->app->getConnection("conn1");

        //leer archivo 
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($dirFile)->getActiveSheet();

        $reading = true;
        $cell = "A";
        $row = 2; //fila desde donde se empieza la lecura
        while ($reading) {
            //validar si continua o no 
            $value = $spreadsheet->getCell($cell . $row)->getCalculatedValue();
            if ($value == "") {
                $reading = false; //se corta la lectura por que llegó al final del archivo
            } else {

                //obtener datos
                $fechaOper = $spreadsheet->getCell("A" . $row)->getCalculatedValue();
                $bank = $spreadsheet->getCell("B" . $row)->getCalculatedValue();
                $primerRef = $spreadsheet->getCell("C" . $row)->getCalculatedValue();
                $segundoRef = $spreadsheet->getCell("D" . $row)->getCalculatedValue();
                $nOper = $spreadsheet->getCell("E" . $row)->getCalculatedValue();
                $importe = $spreadsheet->getCell("F" . $row)->getCalculatedValue();
                
                $formato = 'd/m/Y';
                
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fechaOper);
                $newDate = $date->format(SQL_DATETIME_FORMAT);

                $bankDetail = "SELECT * FROM FU_BANK_ACCOUNT WHERE BANK_ID=" . $bank . "";
                $resultBank = $conn->get_row($bankDetail);  
                
                $user_reg = $_SESSION["user"]->usuario;
                
                $dataToInsert = [
                    "DATE_OPERATION" => $newDate,
                    "REFERENCE_ONE" => $primerRef,
                    "REFERENCE_TWO" => $segundoRef,
                    "IMPORTE" => $importe,
                    "NR_OPERATION" => $nOper,
                    "BANK_ID" => $bank,
                    "NAME_BANK" => utf8_encode($resultBank->NAME_BANK),
                    "NR_ACCOUNT" => strval($resultBank->NR_ACCOUNT),
                    "SALDO" => $importe,
                    "USER_REG" => $user_reg,
                    "DATE_REG" => date(SQL_DATETIME_FORMAT),
                    "DATE_MOD" => '',
                ];

                $result = $this->registroDeposito($dataToInsert);

                if (!$result) {
                    return responseError("Error guardando DEPOSITO");
                    exit;
                } 

                $row++;
            }
        }
        return responseOk();
    }

    function upload()
    {
        if (isset($_FILES["file"])) {

            $file = $_FILES["file"];

            //validar formato
            if ($file["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $prefix = "desposit";
                //guardar en app/data/cache
                $idFile = $prefix . '_' . randomString(5);
                $tmp_name = $idFile. '.xlsx';
                
                copy(
                    $_FILES['file']['tmp_name'],
                    DIR_CACHE . DS . $tmp_name
                );

                //prueba : esto debe ir en cargamasiva
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load(DIR_CACHE . DS . $tmp_name)->getActiveSheet();

                $reading = true;
                $cell = "A";
                $row = 2; //fila desde donde se empieza la lecura
                while ($reading) {
                    //validar si continua o no 
                    $value = $spreadsheet->getCell($cell . $row)->getCalculatedValue();
                    if ($value == "") {
                        $reading = false; //se corta la lectura por que llegó al final del archivo
                    } else {
                        //obtener datos
                        $fechaOper = $spreadsheet->getCell("A" . $row)->getCalculatedValue();
                        $bank = $spreadsheet->getCell("B" . $row)->getCalculatedValue();
                        $primerRef = $spreadsheet->getCell("C" . $row)->getCalculatedValue();
                        $segundoRef = $spreadsheet->getCell("D" . $row)->getCalculatedValue();
                        $nOper = $spreadsheet->getCell("E" . $row)->getCalculatedValue();
                        $importe = $spreadsheet->getCell("F" . $row)->getCalculatedValue();

                        if ($fechaOper == "") {
                            return responseError("Fecha de operación es requerido para: " . $cell . $row);
                        }
                        if ($bank == "") {
                            return responseError("Banco es requerido para: " . $cell . $row);
                        }
                        if ($primerRef == "") {
                            return responseError("Primera referencia es requerida para: " . $cell . $row);
                        }
                        if ($segundoRef == "") {
                            // return responseError("Telefono requerido para: " . $cell . $row);
                        }
                        if ($nOper == "") {
                            return responseError("N° de operación es requerido para: " . $cell . $row);
                        }
                        if ($importe == "") {
                            return responseError("Importe es requerido para: " . $cell . $row);
                        }

                        $row++;
                    }
                }
                //fin prueba

                $data = [
                    "id" => $idFile
                ];
                return responseOk($data);
            }
            return responseError("formato no permitido, solo excel");
        }
        return responseError("subir archivo");
    }

    //***funciones internas***//

    function registroDeposito($data)
    {
        $conn = $this->app->getConnection("conn1");

        $types = [
            "NR_ACCOUNT" => ["varchar", "NULLABLE"],
            "NR_OPERATION" => ["varchar", "NULLABLE"],
            "REFERENCE_ONE" => ["varchar", "NULLABLE"],
            "REFERENCE_TWO" => ["varchar", "NULLABLE"],
        ];

        $conn->insert("FU_DEPOSIT", $data, $types);

        //trae el id del registro insertado 
        $last_insert_id = $conn->last_insert_id();

        //si el last id es > 0 significa que logró registrarse
        if ($last_insert_id > 0) {
            return true;
        } else {
            Logger::error($conn->has_error());
            return false;
        }

    }
    function updateDeposito($data,$depositoId){
        $conn = $this->app->getConnection("conn1");
        $where = [
            "DEPOSIT_ID"=>$depositoId
        ];
        $conn->update("FU_DEPOSIT", $data, $where);
    }
    function registroDepositoDet($data)
    {
        $conn = $this->app->getConnection("conn1");

        $conn->insert("FU_DEPOSIT_DET", $data);

        //Id del registro insertado
        $last_insert_id = $conn->last_insert_id();
        //si el last id es >0
        if ($last_insert_id > 0) {
            //return true;
            return $last_insert_id; 
        } else {
            Logger::error($conn->has_error());
            return false;
        }
        
    }
    function searchBySerie($serie,$nroDocumento)
    {
        $conn2 = $this->app->getConnection("conn2");
        $queryDoc = "SELECT invnum as SECUENCIA,CONCAT(LEFT(TDOFAC,1),tdoidser) AS NROSERIE,facnum AS DOC, FACNET AS TOTAL, NOMCLI
        FROM facturas F
        LEFT JOIN fa_clientes C ON F.pachis = C.codcli
        WHERE CONCAT(LEFT(TDOFAC,1),tdoidser) ='" . $serie . "'   and facnum ='" . $nroDocumento . "'";
        $resultDoc = $conn2->get_row($queryDoc);
        return $resultDoc;
    }
    function searchBySecuencia($secuencia){
        $conn2 = $this->app->getConnection("conn2");
        $queryDoc = "SELECT invnum as SECUENCIA,CONCAT(LEFT(TDOFAC,1),tdoidser) AS NROSERIE,facnum AS DOC, FACNET AS TOTAL, NOMCLI
        FROM facturas F
        LEFT JOIN fa_clientes C ON F.pachis = C.codcli
        WHERE invnum = '" . $secuencia . "'";
        $resultDoc = $conn2->get_row($queryDoc);
        return $resultDoc;
    }
    function searchDepositosTotal($depositos)
    {
        $conn1 = $this->app->getConnection("conn1");
        $queryDep = "SELECT SUM(SALDO) as IMPORTE from FU_DEPOSIT WHERE DEPOSIT_ID IN (" . $depositos . ") ";
        $resultsDep = $conn1->get_row($queryDep);
        return $resultsDep;
    }
    function searchDepositos($depositos)
    {
        $conn1 = $this->app->getConnection("conn1");
        $queryDep = "SELECT  SALDO,DEPOSIT_ID FROM FU_DEPOSIT WHERE DEPOSIT_ID IN (" . $depositos . ") ";
        $resultsDep = $conn1->get_results($queryDep);
        return $resultsDep;
    }
    function TotaldepositoPorDocumento($secuencia,$serie,$nroDocumento)
    {
        $conn1 = $this->app->getConnection("conn1");
        $queryTotDepxDoc = "SELECT SUM(FDD.IMPORTE) AS IMPORTE FROM FU_DEPOSIT_DET FDD
        WHERE FDD.NR_SEQUENCE='".$secuencia."' AND FDD.NR_SERIE = '".$serie."' AND FDD.NR_DOC='".$nroDocumento."'";
        $resultsTotDepxDoc = $conn1->get_row($queryTotDepxDoc);
        return $resultsTotDepxDoc;
    }
}
