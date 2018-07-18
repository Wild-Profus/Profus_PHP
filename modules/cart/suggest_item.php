<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$input['query'] = htmlspecialchars($_POST['request']);
$query = "SELECT `itemName` as `name`,`level`,`iconId` FROM `0items` WHERE `itemName` LIKE CONCAT('%',:query, '%') LIMIT 10";
$result = sqldb::safesql($query,$input);
foreach ($result as $key=>$array){
    $result[$key]['value'] = $array['name'];
    $result[$key]['image'] = '<img class="img-item" src="/images/all/'.$array["iconId"].'.png" alt="'.$array["name"].'" title="'.$array["name"].'" style="width:40px;">';
}
json($result);