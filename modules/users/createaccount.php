<?php
session_start();
require dirname(__FILE__,2).'/shared/common.php';
require dirname(__FILE__).'/encryptpassword.php';
$username = htmlspecialchars($_POST['user']);
if (isset($_POST['user']) && isset($_POST['password']) && usrExist($username)) {
    $enc_pwd = encryptfirst(htmlspecialchars($_POST['password']));
    if (isset($_POST['email'])&&$_POST['email']!=""){
        $email = htmlspecialchars($_POST['email']);
    }else{$email=null;}
    $query = "INSERT INTO `users` (`username`,`PasswordHash`,`Pepper`,`Email`) VALUES (:user , :hash , :pepper , :email )";
    $array[':user'] = $username;
    $array[':hash'] = $enc_pwd[0];
    $array[':pepper'] = $enc_pwd[1];
    $array[':email'] = $email;
    $req = sqldb::safesql($query, $array,false);
    if($req){
        $_SESSION['login'] = $username;
        $data = true;
    }else{
        $data = 'Une erreur inconnue s\'est produite.';
    }
    json($data);
}
else{
    $data = "Erreur lors de la création du compte. Pas de nom d'utilisateur et/ou de mot de passe renseigné.";
    json($data);
}