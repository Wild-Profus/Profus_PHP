<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$res = htmlspecialchars($_POST['res']);
$query = "SELECT * FROM `dofusmap` WHERE `$res` >0";
$results = sqldb::safesql($query);
foreach ($results as $row=>$columns){
    $res_qty = $results[$row][$res];
    if ($res_qty>9){
        $results[$row]['color'] = [255,  0, 0,0.5];
    }elseif ($res_qty>3){
        $results[$row]['color'] = [255,153,51,0.5];
    }else{
        $results[$row]['color'] = [  0,255, 0,0.3];
    }
    foreach ($columns as $resource=>$qty){
        if(is_null($qty)){
            unset($results[$row][$resource]);
        }else{
            $results[$row][$resource]=intval($results[$row][$resource]);
        }
    }
}
json([$res,$results]);