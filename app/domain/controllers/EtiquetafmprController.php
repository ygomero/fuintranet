<?php

namespace App\Controllers;
Use App\Libs\RPDF;

class EtiquetafmprController{

    public $app = null;
    

    function __construct($app)
    {
        $this->app = $app;
    }

    function main(){

        //http://localhost:82/api/etiqueta?dep=987 // para GET dep es un parametro
        $dep = $_GET["dep"]; // = 987
        $codItem = $_GET["codItem"];

            
        $conn = $this->app->getConnection("conn2");
        $query = $this->app->getQuery("etiquetasFMProd.Rec.");
        $query = str_replace("{{id}}",intval($dep),$query);  
        $query = str_replace("{{item}}",intval($codItem),$query); 

        $results = $conn->get_row($query); 
        //print_r($results);exit; 
        
        $pdf = new RPDF();
  
        $pdf = new RPDF('P','mm',array(55,70));
        $pdf->SetMargins(1, 3, 1);
        
        $pdf->AddPage('L');

        $pdf->SetLeftMargin(0.01);
        $pdf->SetFont('Helvetica','B',6);
        $pdf->SetX(30);
        $pdf->Cell( 0, 0, $results->PRODUCTO, 0, 0, 'R' ); 
        $pdf->Ln(6);
        $pdf->Cell(10,0,utf8_decode('RP: '.$results->SECUENCIA.' - '.$results->TIPOUSO));
        $pdf->Ln(2.8);
        $pdf->SetFont('Helvetica','B',8);
        $pdf->Cell(10,0,utf8_decode('PAC:  '.$results->PACIENTE));
        $pdf->Ln(2.8);
        $pdf->SetFont('Helvetica','B',6);      
        $pdf->Cell(10,0,utf8_decode('MED: Q.F. NATALY CARDENAS H.'));
        $pdf->Ln(2);
        $pdf->Cell(10,0,utf8_decode('MANTENER ALEJADO DE LOS NIÑOS'));
        $pdf->Ln(2);
        $pdf->Cell(10,0,utf8_decode('USAR SEGUN INDICACION MEDICA'));
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica','B',5);   
        $pdf->Cell(10,0,utf8_decode('Almacenese a temperatura no mayor de 25 °C Elaborado en: Av. Emancipacion 799 - Lima'));
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica','B',6);            
        $pdf->TextWithDirection(63,20,'E: '.$results->FECHAE->format('d/m/Y'),'U');
        $pdf->TextWithDirection(67,20,'V: '.$results->FECHAV->format('d/m/Y'),'U');
        $pdf->Ln(2);
        $pdf->Output();

    }


}
