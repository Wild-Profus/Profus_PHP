<?php
require_once dirname(__FILE__,2).'/shared/common.php';

function encryptFirst($password){
    $pepper = random_int(1, 999999999);
    $hash = encrypt($password,$pepper);
    return array($hash,$pepper);
}

function encrypt($password, $pepper){
    global $salt;
    $hash = password_hash($password . $pepper, PASSWORD_BCRYPT);
    return $hash;
}

function compareHash($hash1,$hash2){
    return ($hash1===$hash2);
}

function checkUsrPwd($user,$pwd){
    $array[':name']=$user;
    $query = "SELECT `PasswordHash`,`Pepper` FROM `users` WHERE `username`=:name LIMIT 1";
    //$query = "SELECT `password`,`salt` FROM `forum_users` WHERE `username`=:name LIMIT 1";
    $usrPars = sqldb::safesql($query,$array);
    //$hash = encrypt($pwd,$usrPars[0]['Pepper']);
    //$hash = md5(md5($usrpars[0]['salt']).md5($pwd));
    //return comparehash($hash,$usrpars[0]['PasswordHash']);
    return password_verify($pwd.$usrPars[0]['Pepper'],$usrPars[0]['PasswordHash']);
}

function usrExist($name){
    $query = "SELECT COUNT(*) FROM `users` WHERE `username`=:name";
    //$query = "SELECT COUNT(*) FROM `forum_users` WHERE `username`=:name LIMIT 1";
    $array[':name']=$name;
    $result = sqldb::safesql($query,$array);
    if (count($result)> 0) {
        return true;
    }
    else{
        return false;
    }
}

/*
function checkStatus($status){
    if (isset($_SESSION['login'])){
        //$query = "SELECT `$status` FROM `forum_users` WHERE `username`=:name";
        $query = "SELECT `$status` FROM `forum_users` WHERE `username`=:name";
        $array[':name'] = $_SESSION['login'];
        $ans =  sqldb::safesql($query,$array);
        if (($ans[0][$status]!="")&&($ans[0][$status]==1)){
            return true;
        }else{
            return false;
        }
    }
    else{
        return false;
    }
}*/