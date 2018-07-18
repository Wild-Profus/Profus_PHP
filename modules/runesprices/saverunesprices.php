<?php
chdir($_SERVER['DOCUMENT_ROOT']."/forums/");
define("IN_MYBB", 1);
require("./global.php");
chdir("../");
session_start();
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$server = htmlspecialchars($_POST['server']);
unset($_POST['server']);
foreach ($_POST as $key=>$value){
    $rune = str_replace('_',' ',htmlspecialchars($key));
    $price = intval(htmlspecialchars($value));
    $query = "UPDATE `runes` SET `$server`= $price WHERE `rune` = '$rune'";
    sqldb::safesql($query,false,false);
}
json("Le prix des runes pour le serveur ".$server." ont bien été enregistrées.");
new debug($mybb->user['username'],'prices_update.txt');
new debug($_POST,'prices_update.txt');