<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$array = [];
$idols = [];
for ($j=0;$j<6;$j++){
    if(isset($_POST["idol".$j])){
        $array["idol".$j] = htmlspecialchars($_POST["idol".$j]);
        $idols[$j] = $array["idol".$j];
    }else{
        $array["idol".$j] = '';
    }
}
$query = "SELECT * FROM `0idols` WHERE `idolId` IN (:idol0,:idol1,:idol2,:idol3,:idol4,:idol5)";
$score = 0;
$compo = [];
$results = sqldb::safesql($query,$array);
$compo_length = count($results);
$incompatibles = [];
for ($i = 0; $i < $compo_length; $i++){
    $temp = json_decode($results[$i]['incompatibleMonsters']);
    if ($temp!==[]){
        foreach ($temp as $lim){
            $incompatibles[$lim]=$lim;
        }
    }
    $x = 1;
    if (count($idols)>1){
        foreach ($idols as $idol){
            $x = $x * floatval($results[$i][$idol]);
        }
    }
    $compo[$i]['id'] = $results[$i]['idolId'];
    $compo[$i]['x'] = $x;
    if ($x!==1){
        $score = $score + floor(intval($results[$i]['score'])*$x);
    }else{
        $score = $score + $results[$i]['score'];
    }
}
$scores = [];
$sql = "SELECT `idolId` AS `id`,`score` FROM `0idols` WHERE `idolId` NOT IN (:idol0,:idol1,:idol2,:idol3,:idol4,:idol5)";
$ref = sqldb::safesql($sql,$array);
foreach ($ref as $key=>$row){
    $local_score = 0;
    $z = 1;
    if ($compo_length<6) {
        foreach ($compo as $idol_key => $comp) {
            $u = $results[$idol_key][$ref[$key]['id']];
            $local_score = $local_score + floor(intval($results[$idol_key]['score']) * $compo[$idol_key]['x'] * floatval($u));
            $z = $z * $u;
        }
        $local_score = $local_score + floor(intval($ref[$key]['score']) * $z);
        $scores[intval($ref[$key]['id'])] = intval($local_score - $score);
    }else{
        $scores[intval($ref[$key]['id'])] = intval($ref[$key]['score']);
    }
}
$rest = "";
foreach ($incompatibles as $incompatible){
    $rest = $rest.$incompatible." - ";
}
//$data= [$scores,$score,$rest];
$data = [$scores,$score,$rest];
json($data);