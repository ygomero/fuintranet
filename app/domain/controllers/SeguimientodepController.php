<?php

namespace App\Controllers;

class SeguimientodepController{
    
    public $app = null;
    const SEARCH_BY_FECHA_VENTA = "venta";
    const SEARCH_BY_FECHA_CONFIRMACION = "confirmacion";

    function __construct($app)
    {
        $this->app = $app;
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

    function search(){

        //validaciones 

        if(!isset($_POST["fechaseg"])){
            responseError("Invalid params");
        }
        else{
            if(!in_array($_POST["fechaseg"],[self::SEARCH_BY_FECHA_VENTA, self::SEARCH_BY_FECHA_CONFIRMACION])){
                responseError("Invalid params");
            }
        }

        $tipoBusqueda = $_POST["fechaseg"];

        $conn = $this->app->getConnection("conn2");
        $query = $this->app->getQuery("documentosDepLiq");

        $deposito_estado = [];
        $liquid_estado = [];
        
        $fecha_desde='';
        $fecha_hasta='';
        $fecha_venta='';
        $fecha_confirm = '';

        $where = [];
        if(isset($_POST["val_local"]) && intval($_POST["val_local"]) > 0){ 
            $where [] = "AND S.siscod = '".$_POST["val_local"]."' ";
        }

        if(isset($_POST["val_estado_deposito"]) && $_POST["val_estado_deposito"] != ''){
            $deposito_estado []= intval($_POST["val_estado_deposito"]);
        }

        if(isset($_POST["val_estado_liquidado"]) && $_POST["val_estado_liquidado"] != '' ){
            $liquid_estado []= intval($_POST["val_estado_liquidado"]);
        }

        if(isset($_POST["val_fecha_desde"]) && $_POST["val_fecha_desde"] != '' ){
            $fecha_desde = $_POST["val_fecha_desde"];
        }

        if(isset($_POST["val_fecha_hasta"]) && $_POST["val_fecha_hasta"] != '' ){
            $fecha_hasta = $_POST["val_fecha_hasta"];
        }

        if(count($deposito_estado) == 0 ){
            $deposito_estado = [1,2];
        }
        if(count($liquid_estado) == 0 ){
            $liquid_estado = [1,2];
        }

        $fecha_venta = "    AND convert(date,F.facdat) >='{$fecha_desde}' 
                            AND convert(date,F.facdat) <='{$fecha_hasta}' ";

        $fecha_confirm = "  AND CONVERT(DATE,FDD.DATE_CONFIRMATION) >='{$fecha_desde}'  
                            AND CONVERT(DATE,FDD.DATE_CONFIRMATION) <='{$fecha_hasta}'";

        $deposito_estado = implode(",",$deposito_estado);
        $liquid_estado = implode(",",$liquid_estado);
                
        $query = str_replace("{{deposito_estado}}",$deposito_estado,$query);
        $query = str_replace("{{liquid_estado}}",$liquid_estado,$query);


        //validar el tipo de busqueda
        if($tipoBusqueda == self::SEARCH_BY_FECHA_VENTA){
            $query = str_replace("{{WHERE_FECHA_BUSQUEDA}}", $fecha_venta,$query);
        }
        else if($tipoBusqueda == self::SEARCH_BY_FECHA_CONFIRMACION){
            $query = str_replace("{{WHERE_FECHA_BUSQUEDA}}", $fecha_confirm,$query);
        }

        if(count($where) > 0){
            $query = $query. implode(" ",$where);
        }

        /*print_r($query);
        exit();*/

        $results = $conn->get_results($query); 
        $docs = [];

        

        foreach ($results as $item) {
            $estatusDep = '';
            $estatusLiq = '';

            $fecha_dep = '';
            $fecha_liq = '';
            $fecha_conf= '';

            if($item->DEPOSITO ==='DEPOSITADO'){
                $estatusDep ='<td><span class="badge light badge-success">DEP</span></td>';
            }else {
                $estatusDep ='<td><span class="badge light badge-danger">PEN</span></td>';
            }

            if($item->LIQUIQ ==='LIQUIDADO'){
                $estatusLiq ='<td><span class="badge light badge-success">LIQ</span></td>';
            }else {
                $estatusLiq ='<td><span class="badge light badge-danger">PEN</span></td>';
            }

            //Validación de las fechas de depósito cuando NO hay registro
            if ($item->FECHA_DEPOSITO === NULL){ 
                $fecha_dep='-';
            }else{
                $fecha_dep=$item->FECHA_DEPOSITO->format('d-m-Y');
            }

            //Validación de las fechas de confirmación cuando NO hay registro
            if ($item->FECH_CONFIR === NULL){
                $fecha_conf='-';
            }else{
                $fecha_conf=$item->FECH_CONFIR->format('d-m-Y');
            }

            //Validación de las fechas de liquidación cuando NO hay registro
            if ($item->FECHA_LIQ === NULL){
                $fecha_liq='-';
            }else{
                $fecha_liq=$item->FECHA_LIQ->format('d-m-Y');
            }

            $idDeposito = $item->DEPOSIT_DET_ID; 
            //http://localhost:82/api/testpdf?dep=algunvalor
            $link = "/api/testpdf?dep=".$idDeposito;
            if($item->DEPOSITO ==='DEPOSITADO'){
                $btnView = '<a class="badge badge-info viewer" title="ver pdf" href="'.$link.'" target="_blank">
                <i class="flaticon-381-file-2"></i>
                </a>'; 
            }else{
                $btnView= ''; 
            }

            $docs[] = [
                "0"  => $btnView,
                "1"  => utf8_encode($item->LOCAL_FAC),
                "2"  => $item->FECHA_DOC->format('d-m-Y'),
                "3"  => $item->SECUENCIA,
                "4"  => $item->SERIE,
                "5"  => $item->NRO_DOC,
                "6"  => money($item->MONTO_TOTAL),
                "7"  => $fecha_dep,
                "8"  => $fecha_conf,
                "9"  => utf8_encode($item->BANCO),
                "10"  => utf8_encode($item->NR_OPERACION),
                "11"  => money($item->MONTO_DEPOSITADO),
                "12"  => utf8_encode($item->LOCAL_ENTREGA),
                "13"  => utf8_encode($item->USUARIO_SOLICITANTE),
                "14"  => $fecha_liq,
                "15"  => $item->NRO_SECUENCIA,
                "16"  => utf8_encode($item->CAJERO),
                "17"  => $item->CAJA,
                "18"  => $estatusDep,
                "19"  => $estatusLiq, 
                "20"  => "id",
            ];
        }
        
        return $docs;
        
    }

}