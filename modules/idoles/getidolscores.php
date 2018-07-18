<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$req = htmlspecialchars($_POST['data']);
if (isset($req)&&$req!=="{}"){
    $source = json_decode(stripslashes($_POST['data']));
    $string = "";
    foreach ($source as $key=>$idol){
        $string = $string.',"'.htmlspecialchars($idol).'"';
    }
    $string = ltrim($string, ',');
    $query = "SELECT `id`,`img`,`score` FROM `idolescores` WHERE idole0 NOT IN (".$string.") 
                                                  AND idole1 NOT IN (".$string.")  
                                                  AND idole2 NOT IN (".$string.") 
                                                  AND idole3 NOT IN (".$string.") 
                                                  AND idole4 NOT IN (".$string.") 
                                                  AND idole5 NOT IN (".$string.")
                                                  ORDER BY `score` DESC LIMIT 20";
}else{
    $query = "SELECT `id`,`img`,`score` FROM `idolescores` ORDER BY `score` DESC LIMIT 20";
}
$res = safesql($query);
echo json_encode($res);