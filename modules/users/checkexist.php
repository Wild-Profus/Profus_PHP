<?php
require_once dirname(__FILE__).'/encryptpassword.php';
if (isset($_POST['user'])){
    $name = htmlspecialchars($_POST['user']);
    header('Content-Type: application/json');
    echo json_encode(usrExist($name));
}
