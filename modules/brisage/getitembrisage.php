<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$array['query'] = htmlspecialchars($_POST['item']);
$query_main = "SELECT `itemName` as `name`,`level`,`itemId`,`typeName`as`type`,`craftable`,`iconId` FROM `0items` WHERE `itemName` = :query LIMIT 1";
$info = sqldb::safesql($query_main,$array)[0];
$bkr = 50 + 50 *$info['craftable'];
$info['craftable'] = "Taux de Brisage : <span id=\"brokerate\" contenteditable=\"true\">".$bkr."</span> %";
$info['img'] = '<img class="img-item" src="/images/all/'.$info['iconId'].'.png" alt="'.$info['name'].'" title="'.$info['name'].'" style="width:200px;"/>';
$info['shopbtn'] = button::shopping($info['name'],1);
$srv = htmlspecialchars($_POST['srv']);
$query_craft = "SELECT DISTINCT `effectId`,`max`,`min` FROM `0effects` WHERE (`itemId`=:itemId) AND (`effectId` IN(111,112,115,117,118,119,123,124,125,126,128,138,158,160,161,174,176,178,182,210,211,212,213,214,220,225,226,240,241,242,243,244,410,412,414,416,418,420,422,424,426,428,430,752,753,795,2800,2803,2804,2807,2008,2812))";
$result_craft = sqldb::safesql($query_craft,['itemId'=>$info['itemId']]);
$runes_query = "SELECT `stat`,`rune`,`effectId`,`weight`,`base`,`$srv` as `srv` FROM `runes`";
$runes = sqldb::safesql($runes_query);
$itm = [];
$k = 0;
foreach ($result_craft as $key=>$effectArray){
    $rIndex = findRuneIndex($effectArray['effectId'],$runes);
    $itm[$k]['stat'] = $runes[$rIndex]['stat'];
    $itm[$k]['min'] = intval($effectArray['min']);
    $itm[$k]['max'] = intval($effectArray['max']);
    $itm[$k]['moy'] = intval(($itm[$k]['max']+$itm[$k]['min'])/2);
    $itm[$k]['jet'] = $itm[$k]['moy'];
    $itm[$k]['rune'] = $runes[$rIndex]['rune'];
    $itm[$k]['base'] = intval($runes[$rIndex]['base']);
    $itm[$k]['weight'] = intval($runes[$rIndex]['weight']);
    $itm[$k]['price'] = intval($runes[$rIndex]['srv']);
    $k++;
}
unset($info['iconId']);
unset($info['itemId']);
$sct = "<select id='exo' class='form-control'>";
foreach ($runes as $key=>$row){
    $sct = $sct."<option value='".$runes[$key]['weight']."' data-price='".$runes[$key]['srv']."'>".$runes[$key]['stat']."</option>";
}
$sct = $sct."<option selected='selected' value='0' data-price='0'>Pas d'exo</option></select>";
json([$info,$bkr,$srv,$itm,$sct]);

function findRuneIndex($effectId,$runesResults){
    foreach($runesResults as $index => $row) {
        if($row["effectId"]===$effectId) return $index;
    }
    return FALSE;
}