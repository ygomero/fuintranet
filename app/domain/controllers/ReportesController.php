<?php

namespace App\Controllers;

class ReportesController{
    
    public $app = null;

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
                "name" => utf8_encode($item->SISENT),
            ];
        }

        return $local;
    }

    function ventasProductosRecomendados()
    {

        $conn = $this->app->getConnection("conn2");
        $query = $this->app->getQuery("ventasProductosRecomendados");
 
        $fecha_desde='';
        $fecha_hasta='';

        //validaciones

        $where = [];
        if(isset($_POST["val_local"]) && intval($_POST["val_local"]) > 0){ 
            $where [] = "AND S.siscod = '".$_POST["val_local"]."' ";
        }

        if(isset($_POST["val_fecha_desde"]) && $_POST["val_fecha_desde"] != '' ){
            $fecha_desde = $_POST["val_fecha_desde"];
        }

        if(isset($_POST["val_fecha_hasta"]) && $_POST["val_fecha_hasta"] != '' ){
            $fecha_hasta = $_POST["val_fecha_hasta"];
        }

        $query = str_replace("{{fecha_desde}}",$fecha_desde,$query);
        $query = str_replace("{{fecha_hasta}}",$fecha_hasta,$query);

        if(count($where) > 0){
            $query = $query. implode(" ",$where);
        }

        // print_r($query);
        // exit();

        $results = $conn->get_results($query); 
        $docs = [];


        foreach ($results as $item) {

            $telefono ='';

             //Validación de las fechas de depósito cuando NO hay registro
             if ($item->TELEFONO === NULL || $item->TELEFONO === ' '){ 
                $telefono='-';
            }else{
                $telefono=$item->TELEFONO;
            }

            $invnum_r = $item->SVenta; 
            $codItem = $item->ITEM; 
            //http://localhost:82/api/etiqueta?dep=algunvalor
            $link = "/api/etiqueta?dep=".$invnum_r."&codItem=".$codItem;

            if ($item->SKU === '24298' || $item->SKU === '24297' || $item->SKU === '24299' || $item->SKU === '24300' || $item->SKU === '25121')
            {
                $btnView = '';
            }else{
                $btnView = '<a class="badge badge-info viewer" title="ver pdf" href="'.$link.'" target="_blank">
                    <i class="flaticon-381-file-2"></i>
                    </a>'; 
            }
            $docs[] = [
                "0"  => $btnView,
                "1"  => $item->FECHA->format('d-m-Y'),
                "2"  => $item->LOCAL,
                "3"  => utf8_encode($item->PACIENTE),
                "4"  => $telefono,
                "5"  => utf8_encode($item->MEDICO),
                "6"  => $item->TIPO,
                "7"  => $item->COMPROBANTE,
                "8"  => $item->SKU,
                "9"  => utf8_encode($item->PRODUCTO),
                "10"  => $item->UNIDADES,
                "11"  => money($item->TOTAL),
                "12"  => $item->SVenta,
                "13"  => number_format($item->DESCUENTO,2)."%", 
            ];
        }

        //  print_r($docs);
        //  exit();

        return $docs;
    }

    function consultaRecetasControladas(){
        
    }

}