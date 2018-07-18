<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
//$prepare = "SELECT `date`,`bonus`,`qty`,`itemName`,`iconId`,`meryde`,`reward`, IF( DAYOFYEAR(`date`) - DAYOFYEAR(CURDATE())<1, DAYOFYEAR(`date`) - DAYOFYEAR(CURDATE())+366, DAYOFYEAR(`date`) - DAYOFYEAR(CURDATE())) as days FROM `almanax` order by days asc";
$prepare = "SELECT `date`,`bonus`,`class`,`qty`,`itemName`,`itemIconId` as `iconId`,`meryde`,`reward`, IF( DAYOFYEAR(`date`) - DAYOFYEAR(CURDATE())<0, DAYOFYEAR(`date`) - DAYOFYEAR(CURDATE())+365, DAYOFYEAR(`date`) - DAYOFYEAR(CURDATE())) as days FROM `0almanax` WHERE `date` Is Not Null order by days asc";
$results = sqldb::safesql($prepare);
$next_year = 366 - date('z');
foreach ($results as $key=>$value){
    if($results[$key]['days']<$next_year){
        $add = date('/y');
    }else{
        $add = date('/y', strtotime('+1 year'));
    }
    $mer = $results[$key]['meryde'];
    $results[$key]['date'] = "$mer<br>".date('d/m',strtotime($results[$key]['date'])).$add."<br/><img class='gremind' src='images/gc.gif'>";
    $item = $results[$key]['itemName'];
    $iconId = $results[$key]['iconId'];
    $qty = $results[$key]['qty'];
    $rw = $results[$key]['reward'];
    $btn = button::shopping($item,$qty);
    $draw = '<span class="itm">
                <img src="/images/all/'.$iconId.'.png" alt="'.$item.'" title="'.$qty.' '.$item.'">
                <p class="itm-qty">'.$results[$key]['qty'].'</p>
            </span>';
    $results[$key]['offering'] = "$draw<span style='margin-left:15%;'>$btn</span><br><span>$qty $item</span><br><span>$rw<img class=\"img-rounded\" src=\"/images/icons/characteristics/103.png\" style='width: 18px;vertical-align: -4px''/></span>";
}
if (date('L')==0){
    $pos = intval(date('z'));
    if ($pos>59){
        unset($results[366-$pos-1+59]);
    }else{
        unset($results[59-$pos-1]);
    }
}
$results = array_values($results);
json($results);