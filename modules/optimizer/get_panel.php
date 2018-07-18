<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
$effectsId = [
    //effectId=>oppositeEffectId,equivalentEffectId,equivalentOppositeEffectId,equivalentRatio,shortName
    125=>[153,false,false,false,"Vita"],
    111=>[168,false,false,false,"PA"],
    128=>[169,false,false,false,"PM"],
    117=>[116,false,false,false,"PO"],
    182=>[false,false,false,false,"Invoc"],
    174=>[175,false,false,false,"Ini"],
    176=>[177,false,false,false,"Prospe"],
    178=>[179,false,false,false,"Soin"],
    158=>[159,false,false,false,"Pods"],
    220=>[false,false,false,false,"Dommages renvoyés"],
    138=>[153,[118,126,123,119],[157,155,152,154],0.25,"Puissance"],
    118=>[157,[138],false,1,"Force"],
    126=>[155,[138],false,1,"Intel"],
    123=>[152,[138],false,1,"Chance"],
    119=>[154,[138],false,1,"Agi"],
    430=>[431,[112],false,1,"Do Neutre"],
    422=>[423,[112],false,1,"Do Terre"],
    424=>[425,[112],false,1,"Do Feu"],
    426=>[427,[112],false,1,"Do Eau"],
    428=>[429,[112],false,1,"Do Air"],
    112=>[153,[430,422,424,426,428],[431,423,525,427,429],0.25,"Dommages"],
    2800=>[2801,false,false,false,"% Do Mêlée"],
    2804=>[2805,false,false,false,"% Do Distance"],
    2812=>[2813,false,false,false,"% Do Sort"],
    2808=>[2809,false,false,false,"% Do Arme"],
    115=>[171,false,false,false,"Crit"],
    418=>[419,false,false,false,"Do Crit"],
    414=>[415,false,false,false,"Do Pou"],
    225=>[false,false,false,false,"Do Piège"],
    226=>[false,false,false,false,"Pui Piège"],
    101=>[false,false,false,false,"PA(cible)"],
    100=>[false,false,false,false,"Dgt Neutre"],
    97=>[false,false,false,false,"Dgt Terre"],
    99=>[false,false,false,false,"Dgt Feu"],
    96=>[false,false,false,false,"Dgt Eau"],
    98=>[false,false,false,false,"Dgt Air"],
    108=>[false,false,false,false,"PdV Rendus"],
    795=>[false,false,false,false,"Arme de Chasse"],
    124=>[156,false,false,false,"Sasa"],
    410=>[411,[124],[156],0.1,"Ret PA"],
    412=>[413,[124],[156],0.1,"Ret PM"],
    160=>[162,[124],[156],0.1,"Esq PA"],
    161=>[163,[124],[156],0.1,"Esq PM"],
    752=>[754,[119],[154],0.1,"Fuite"],
    753=>[755,[119],[154],0.1,"Tacle"],
    1=>[false,[214,210,213,211,212],[219,215,218,216,217],1,"% Rez"],
    2=>[false,[214,210,213,211,212],false,1,"Rez Fixes"],
    214=>[219,false,false,false,"% Res Neutre"],
    210=>[215,false,false,false,"% Res Terre"],
    213=>[218,false,false,false,"% Res Feu"],
    211=>[216,false,false,false,"% Res Eau"],
    212=>[217,false,false,false],"% Res Air",
    244=>[false,false,false,false,"Ré Neutre"],
    240=>[false,false,false,false,"Ré Terre"],
    243=>[false,false,false,false,"Ré Feu"],
    241=>[false,false,false,false,"Ré Eau"],
    242=>[false,false,false,false,"Ré Air"],
    2803=>[2802,false,false,false,"% Res Mêlée"],
    2807=>[2806,false,false,false,"% Res Distance"],
    420=>[421,false,false,false,"Ré Crit"],
    416=>[417,false,false,false,"Ré Pou"]
];
$types = "";
if(isset($_POST["types"])){
    foreach ($_POST["types"] as $type){
        $types = $types."'".htmlspecialchars($type)."',";
    }
    $types = rtrim($types,',');
}
if (isset($_POST['lvlMin'])){$lvlMin = htmlspecialchars($_POST['lvlMin']);}else{$lvlMin = 1;}
if (isset($_POST['lvlMax'])){$lvlMax = htmlspecialchars($_POST['lvlMax']);}else{$lvlMax = 200;}
$effects = "";
$select = "";
$conditions = "";
$orderBy = "";
if(isset($_POST['must'])){
    foreach ($_POST['must'] as $stat){
        build_sql($stat,false,true);
    }
}
if(isset($_POST['mustEq'])){
    foreach ($_POST['mustEq'] as $stat){
        build_sql($stat,true,true);
    }
}
if(isset($_POST['see'])){
    foreach ($_POST['see'] as $stat){
        build_sql($stat,false,false);
    }
}
if(isset($_POST['seeEq'])){
    foreach ($_POST['seeEq'] as $stat){
        build_sql($stat,true,false);
    }
}
$effects = ltrim($effects,',');
$conditions = ltrim($conditions,' AND ');
$orderBy = "SUM(CASE e.effectId $orderBy END) DESC,";
$query = "
SELECT i.itemName AS `Nom`, i.level AS `Niv`$select
FROM 0items i
JOIN 0effects e ON e.itemId = i.itemId
WHERE i.typeId IN ($types) 
  AND i.level BETWEEN $lvlMin AND $lvlMax
  AND e.effectId IN ($effects)
GROUP BY i.itemId
HAVING
$conditions
ORDER BY $orderBy i.level DESC LIMIT 50;";
new debug($query);
json(sqldb::safesql($query));

function build_sql($stat,$eq=false,$must=false){
    global $effectsId;
    global $effects;
    global $conditions;
    global $select;
    global $orderBy;
    $statInfo = $effectsId[$stat];
    $effects = $effects.",$stat";
    $sum = "WHEN $stat THEN e.max";
    if ($statInfo[0]!==false){
        $sum = $sum." WHEN ".$statInfo[0]." THEN -e.max";
        $effects = $effects.",".$statInfo[0];
    }
    $equi = "";
    if ($eq===true){
        $equi = "(Eq)";
        if ($statInfo[1]!==false&&$statInfo[3]!==false){
            $ratio = $statInfo[3];
            foreach ($statInfo[1] as $statX){
                $sum = $sum." WHEN $statX THEN $ratio*e.max";
                $effects = $effects.",$statX";
            }
            if ($statInfo[2]!==false){
                foreach ($statInfo[2] as $statX){
                    $sum = $sum." WHEN $statX THEN -$ratio*e.max";
                    $effects = $effects.",$statX";
                }
            }
        }
    }
    $totalSum = "SUM(CASE e.effectId $sum END)";
    if ($must===true){
        $conditions = $conditions . " AND ". $totalSum. ">0";
        $orderBy = $orderBy." $sum";
    }
    $select = $select.", $totalSum AS `".$statInfo[4].$equi."`";
}