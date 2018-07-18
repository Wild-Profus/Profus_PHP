<?php
session_start();
require_once dirname(__FILE__).'/encryptpassword.php';
require_once dirname(__FILE__,2).'/shared/common.php';
$usr = htmlspecialchars($_POST['user']);
$pwd = htmlspecialchars($_POST['password']);
if (usrExist($usr)) {
    if (checkUsrPwd($usr, $pwd)) {
        $_SESSION['login']=$usr;
        $query="SELECT `servor` FROM `users` WHERE `username`=:username";
        $user[':username']=$usr;
        $Server=sqldb::safesql($query,$user);
        $_SESSION['servor']=$Server[0]['servor'];
        $data = true;
    } else { $data = false;}
} else { $data = false;}
json($data);