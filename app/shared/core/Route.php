<?php

function redirect($path){
    header("Location: ".WEB_URL."/".$path);
    exit();
}