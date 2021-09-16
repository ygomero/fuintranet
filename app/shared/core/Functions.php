<?php 

function responseOK($data = [], $msg = "Operación realizada correctamente"){
    return [
        "status"=>"ok",
        "msg"=>$msg,    
        "code"=>200,
        "data"=>$data
    ];
}

function responseError($msg = "Ocurrió un error",$code=500){
    return [
        "status"=>"fail",
        "msg"=>$msg,
        "code"=>$code
    ];
}

function randomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function money($amount){
    return MONEY_SYMBOL.number_format($amount,2);
}