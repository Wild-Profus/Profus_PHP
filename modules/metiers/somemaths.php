<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
require dirname(__FILE__,1).'/maths.php';
$fct = htmlspecialchars($_POST['fct']);
$varsarray = $_POST['vars'];
$varsarrayescaped = [];
foreach ($varsarray as $key=>$value){
    $varsarrayescaped[$key]=intval(htmlspecialchars($value));
}
$bonus = htmlspecialchars($_POST['bonus']);
if ($fct=="lvl_and_qty"){
    json(lvl_and_qty($varsarrayescaped[0],$varsarrayescaped[1],$varsarrayescaped[2],$varsarrayescaped[3],$bonus));
}elseif($fct=="lvl_and_lvlgoal"){
    json(lvl_and_lvlgoal($varsarrayescaped[0],$varsarrayescaped[1],$varsarrayescaped[2],$varsarrayescaped[3],$bonus));
}