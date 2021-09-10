<?php

namespace App\Controllers;

class DepositosController
{
    public $app = null;

    function __construct($app)
    {
        $this->app = $app;
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
        print_r($results);
        exit;

        return $banks;
    }

}
