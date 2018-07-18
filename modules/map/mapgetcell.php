<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$array['cell']=htmlspecialchars($_POST['cell']);
$query = "SELECT * FROM `dofusmap` WHERE id=:cell";
$results = sqldb::safesql($query,$array);
foreach ($results as $key=>$result){
    foreach ($result as $resources=>$qty){
        if(is_null($qty)){
            unset($results[$key][$resources]);
        }
    }
}
json($results);