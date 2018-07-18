<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
if (isset($_POST['server'])){
    $server = htmlspecialchars($_POST['server']);
    $query = "SELECT `rune`,`$server` as `srv` FROM `runes`";
    $prices = sqldb::safesql($query);
    json($prices);
}