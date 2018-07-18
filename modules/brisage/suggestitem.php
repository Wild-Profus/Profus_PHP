<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$input["query"] = htmlspecialchars($_POST['request']);
$query = "SELECT `itemId`,`itemName` as `name`,`level`,`iconId` FROM `0items` WHERE `itemName` LIKE CONCAT('%',:query, '%') AND (`typeId` IN (1,2,3,4,5,6,7,8,9,10,11,16,17,18,19,21,22,23,82,102,121,151)) ORDER BY `itemName` LIMIT 10";
$result = sqldb::safesql($query,$input);
foreach ($result as $key=>$array){
    $result[$key]['value'] = $result[$key]['name'];
    $result[$key]['image'] = '<img class="img-item" src="/images/all/'.$result[$key]['iconId'].'.png" alt="'.$result[$key]['name'].'" title="'.$result[$key]['name'].'" style="width:40px;"/>';
}
json($result);