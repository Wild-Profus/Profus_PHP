<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$srv = htmlspecialchars($_POST['srv']);
foreach ($_POST['prices'] as $id => $row){
    $array["runeName"] = htmlspecialchars($row["runeName"]);
    $array["runePrice"] = intval(htmlspecialchars($row["runePrice"]));
    if($array["runePrice"]>1 && $array["runeName"]!==""){
        $query = "UPDATE `runes` SET `$srv`=:runePrice WHERE `stat`=:runeName ;";
        sqldb::safesql($query,$array);
    }
    $array = null;
}