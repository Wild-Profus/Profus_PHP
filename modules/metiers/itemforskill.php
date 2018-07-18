<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$jobId = htmlspecialchars($_POST['jobId']);
$query = "SELECT `itemName`,`itemIconId`,`level`,`xp`,`ingName0`,`ingQty0`,`ingIconId0`,`ingName1`,`ingQty1`,`ingIconId1`,`ingName2`,`ingQty2`,`ingIconId2`,`ingName3`,`ingQty3`,`ingIconId3`,`ingName4`,`ingQty4`,`ingIconId4`,`ingName5`,`ingQty5`,`ingIconId5`,`ingName6`,`ingQty6`,`ingIconId6`,`ingName7`,`ingQty7`,`ingIconId7` FROM `0recipes` WHERE `jobId`=:jobId AND `xp` > 0 AND `level` < 200 ORDER BY `level`";
$results = sqldb::safesql($query,["jobId"=>$jobId]);
$data = [];
foreach ($results as $key=>$result){
    $data[$key]['level'] = $result["level"];
    $data[$key]['xp'] = $result["xp"];
    $data[$key]['img'] = '<img class="img-item" src="/images/all/'.$result["itemIconId"].'.png" alt="'.$result["itemName"].'" title="'.$result["itemName"].'" style="width:70px;">';
    $recipe = "<div>";
    for ($i=0;$i<8;$i++){
        if (isset($result["ingName".$i])){
            $recipe = $recipe.'<span class="itm"><img src="/images/all/'.$result["ingIconId".$i].'.png" alt="'.$result["ingName".$i].'" title="'.$result["ingQty".$i].' '.$result["ingName".$i].'">
                <p class="itm-qty">'.$result["ingQty".$i].'</p>
            </span>';
        }
    }
    $recipe = $recipe."</div>";
    $data[$key]['recipe'] = $recipe;
    $data[$key]['craftbtn'] = "<i class='tocraft glyphicon glyphicon-plus btn btn-success' data-itemName='".$result['itemName']."'></i>";
}
json($data);