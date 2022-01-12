<?php

namespace App\Controllers;
Use App\Libs\FPDF;

class PdfconfirdepController{

    public $app = null;

    function __construct($app)
    {
        $this->app = $app;
    }

    function main(){
        //http://localhost:82/api/testpdf?dep=987 // para GET dep es un parametro
        $dep = $_GET["dep"]; // = 987

        $conn = $this->app->getConnection("conn2");
        $query = $this->app->getQuery("depositosTicket");
        $query = str_replace("{{id}}",intval($dep),$query);  
   
        $results = $conn->get_row($query); 
        $coin = '';
        //print_r($results);exit; 
        if ($results->COIN ==='SOLES'){
             $coin='S/.';
         }else {
             $coin='$';
         }
        $pdf = new FPDF();
        
        $pdf = new FPDF('P','mm',array(72,279.4));
        $pdf->SetMargins(3, 3, 3);
        

        $DateAndTime = date('d-m-Y h:i:s a', time());
        $pdf->AddPage();
        $pdf->SetFont('Courier','B',9.2);
        $pdf->Cell(0,0,utf8_decode('** CONFIRMACIÓN DE ABONO **'),0,0,'C');
        $pdf->Ln(5);
        //$pdf->Cell(0,0,$DateAndTime,0,0,'C');
        $pdf->Cell(0,0,$results->DATE_CONFIRMATION->format('d-m-Y H:i:s'),0,0,'C');
        $pdf->SetLeftMargin(0.01);
        $pdf->Ln(5);
        $pdf->SetFont('Courier','B',8.5);
        $pdf->Cell(10,0,'BANCO:        '.$results->BANCO);
        $pdf->Ln(3);
        $pdf->Cell(10,0,utf8_decode('N° DE CTA:    '.$results->NR_ACCOUNT));
        $pdf->Ln(3);
        $pdf->Cell(10,0,utf8_decode('FECHA OPER.:  '.$results->FECHA_DEPOSITO->format('d-m-Y')));
        $pdf->Ln(3);
        $pdf->Cell(10,0,utf8_decode('N° DE OPER.:  '.$results->NR_OPERATION));
        $pdf->Ln(3);
        $pdf->Cell(10,0,utf8_decode('REFER. 1:     '.$results->REFERENCE_ONE));
        $pdf->Ln(3);
        $pdf->Cell(10,0,utf8_decode('REFER. 2:     '.$results->REFERENCE_TWO));
        $pdf->Ln(3);
        $pdf->Cell(10,0,'ABONO:        '.$coin.number_format((float)$results->ABONO, 2, '.', ''));
        $pdf->Ln(3);
        $pdf->Cell(10,0,'CANCELADO:    '.$coin.number_format((float)$results->CANCELADO, 2, '.', ''));
        $pdf->Ln(3);
        $pdf->Cell(10,0,'FAVOR DEL CL: '.$coin.number_format((float)$results->FAVOR_CLIENTE, 2, '.', ''));
        $pdf->Ln(3);
        $pdf->Cell(10,0,'FALTANTE:     '.$coin.number_format((float)$results->FALTANTE, 2, '.', ''));
        //$pdf->Cell(10,0,money_format(setlocale() ,$results->MONTO_DEPOSITADO));
        $pdf->Ln(2);
        $pdf->Cell(10,0,utf8_decode('_______________________________________'));
        $pdf->Ln(5);
        $cli = 'CLIENTE:   '.$results->NAME_CLIENT;
        $pdf->WordWrap($cli,80);
        //$pdf->Cell(30,0,'CLIENTE: '.$results->NAME_CLIENT);
        //$pdf->Ln(5);
        //$pdf->Cell(30,0,$cli);
        $pdf->Write(3,$cli);
        $pdf->Ln(5);
        $pdf->Cell(10,0,utf8_decode('N° DOC:   '.$results->SERIE.' - '.$results->NRO_DOC));
        $pdf->Ln(3);
        $pdf->Cell(30,0,'TOTAL:    S/.'.$results->MONTO_TOTAL);
        $pdf->Ln(3);
        $pdf->Cell(30,0,'SOLICITA: '.$results->USER_SOLICITA);
        $pdf->Ln(3);
        $pdf->Cell(30,0,'CONFIRM.: '.$results->USER_CONFIRM);
        $pdf->Ln(3);
        $pdf->Output();

    }


}
