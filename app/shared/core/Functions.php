<?php 

function responseOK($data = []){
    return [
        "status"=>"ok",
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