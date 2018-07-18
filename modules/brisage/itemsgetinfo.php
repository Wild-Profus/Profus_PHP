<?php
require_once dirname(__FILE__) . '/itemgetinfo.php';
$type = htmlspecialchars($_POST['type']);
$itemid = htmlspecialchars($_POST['id']);
if ($itemid!==""&&$itemid!==null&&$itemid!=="undefined"){
    $data = itemgetinfo($type,$itemid);
    echo json_encode($data);
}