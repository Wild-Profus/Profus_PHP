<?php
session_start();
require dirname(__FILE__).'/encryptpassword.php';
require dirname(__FILE__,2).'/shared/common.php';
$oldpwd = htmlspecialchars($_POST['oldpwd']);
$newpwd = htmlspecialchars($_POST['newpwd']);
$usr = $_SESSION['login'];
if (usrexist($usr)) {
    if (checkusrpwd($usr, $oldpwd)) {
        $enc_pwd = encryptfirst($newpwd);
        $hash = $enc_pwd[0];
        $pepper = $enc_pwd[1];
        $query = "UPDATE `users` SET `PasswordHash`=:hash,`Pepper`=:pepper WHERE `username`=:usr ";
        $array[':usr']=$usr;
        $array[':hash']=$hash;
        $array[':pepper']=$pepper;
        sqldb::safesql($query,$array,false);
        $data=true;
    } else { $data = false;}
} else { $data = false;}
json($data);