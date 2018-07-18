<?php
ini_set('session.cookie_lifetime', 86400 * 7);
session_set_cookie_params(86400 * 7);
session_start();
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
if (isset($_SESSION['cart'])){
    unset ($_SESSION['cart']);
}