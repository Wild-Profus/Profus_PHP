<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
if (isset($_POST['item'])){
    $array['item']=htmlspecialchars($_POST['item']);
    $itemQuery = "SELECT `itemName`,`itemId`,`level`,`typeName` FROM `0items` WHERE `itemName`=:item LIMIT 1";
    $effectsQuery = "SELECT `effectName`,`max` FROM `0effects` WHERE `itemId`=:itemId";
    $item = sqldb::safesql($itemQuery,$array)[0];
    $effects = sqldb::safesql($effectsQuery,["itemId"=>$item["itemId"]]);
    $header = $item["itemName"]." - ".$item["level"]." - ".$item["typeName"];
    $body = "<table class='table table-condensed table-hover'>";
    foreach ($effects as $effect){
        $stat = explode("#2",$effect["effectName"])[1];
        if ($effect["effectName"][0]==="-"){
            $body = $body."<tr><td>".$stat."</td><td class='text-center'>-".$effect["max"]."</td></tr>";
        }else{
            $body = $body."<tr><td>".$stat."</td><td class='text-center'>".$effect["max"]."</td></tr>";
        }
    };
    $body = $body."</table>";
    json([$header,$body]);
}