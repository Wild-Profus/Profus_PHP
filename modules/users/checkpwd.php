<?php
session_start();
require_once dirname(__FILE__).'/encryptpassword.php';
$pwd = htmlspecialchars($_POST['pwd']);
$usr = $_SESSION['login'];
if (usrExist($usr)) {
    if (checkUsrPwd($usr, $pwd)) {
        $data = true;
    } else { $data = false;}
} else { $data = false;}
json($data);