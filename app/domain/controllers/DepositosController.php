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
                "name" => $item->NAME_BANK,
            ];
        }

        return $banks;
    }

}
