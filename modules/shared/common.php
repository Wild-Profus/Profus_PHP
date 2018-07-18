<?php

function autoload($class) {

    include $_SERVER['DOCUMENT_ROOT'].'/modules/shared/' . $class . '.php';

}



function json($data){

    header('Content-Type: application/json');

    echo json_encode($data);

}



spl_autoload_register('autoload');

$version = "b_";



function trace($data=""){

    static $t = 0;

    $msg = "Call $t : $data ;";

    if ($t===0){

        file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/trace.txt", $msg);

    }else{

        file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/trace.txt", "\n".$msg, FILE_APPEND | LOCK_EX);

    }

    $t++;

}