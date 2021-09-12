<?php

namespace App\Controllers;

class BanksController
{
    public $app = null;

    function __construct($app)
    {
        $this->app = $app;
    }

 
    function main()
    {
        $conn = $this->app->getConnection("conn1");
        $results = $conn->get_results($this->app->getQuery("banksSelectAll"));
        $banks = [];
        foreach($results as $item){
            $banks[] = [
                "0"=>"",
                "1"=>$item->NAME_COMPANY,
                "2"=>UTF8_ENCODE($item->NAME_BANK),
                "3"=>$item->NR_ACCOUNT,
                "4"=>$item->TYPE_ACCOUNT,
                "5"=>UTF8_ENCODE($item->COIN),
            ];
        }
          
        return $banks;
    }
}
