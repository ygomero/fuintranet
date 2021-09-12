<?php

namespace App\Controllers;

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
        $depositos = [];
        foreach ($results as $item) {
            $depositos[] = [
                "0" => "",
                "1" => $item->NAME_BANK,
                "2" => $item->DATE_OPERATION,
                "3" => $item->REFERENCE_ONE,
                "4" => $item->REFERENCE_TWO,
                "5" => $item->IMPORTE,
                "6" => $item->NR_OPERATION,
            ];
        }

        return $depositos;
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


    function cargamasiva(){
        //guardar carga masiva


    }

    function upload()
    {
        if (isset($_FILES["file"])) {

            $file = $_FILES["file"];

            //validar formato
            if ($file["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $prefix = "desposit";
                //guardar en app/data/cache
                $tmp_name = $prefix . '_' . randomString(5) . '.xlsx';
                $name = basename($_FILES["file"]["name"]);
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
                $row = 1; //fila desde donde se empieza la lecura
                while($reading){
                    //validar si continua o no 
                    $value = $spreadsheet->getCell($cell."1")->getCalculatedValue();
                    if($value == ""){
                        $reading = false; //se corta la lectura por que llegó al final del archivo
                    }
                    else{
                        //obtener datos
                        $fieldDni = $spreadsheet->getCell("A".$row)->getCalculatedValue();
                        $fieldNombre = $spreadsheet->getCell("B".$row)->getCalculatedValue();
                        $fieldApellido = $spreadsheet->getCell("C".$row)->getCalculatedValue();
                        $fieldTelefono = $spreadsheet->getCell("D".$row)->getCalculatedValue();

                        if($fieldDni == ""){
                            return responseError("Dni requerido para: ".$cell.$row);
                        }
                        if($fieldNombre == ""){
                            return responseError("Nombre requerido para: ".$cell.$row);
                        }
                        if($fieldApellido == ""){
                            return responseError("Apellido requerido para: ".$cell.$row);
                        }
                        if($fieldTelefono == ""){
                            return responseError("Telefono requerido para: ".$cell.$row);
                        }

                        $row++;
                    }
                }
                //fin prueba

                $data = [
                    "id" => $tmp_name
                ];
                return responseOk($data);
            }
            return responseError("formato no permitido, solo excel");
        }
        return responseError("subir archivo");
    }
}
