<?php
/*chdir($_SERVER['DOCUMENT_ROOT']."/forums/"); // path to MyBB
define("IN_MYBB", 1);
require("./global.php");
chdir("../");*/
session_start();
require dirname(__FILE__,2).'/shared/common.php';
$user[':servor']=htmlspecialchars($_POST['servor']);
$user[':usr']=htmlspecialchars($_SESSION['login']);
$query = "UPDATE `users` SET `servor`=:servor WHERE `username`=:usr";
sqldb::safesql($query,$user,false);
$_SESSION['servor']=$user[':servor'];