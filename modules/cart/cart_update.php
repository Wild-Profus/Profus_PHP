<?php
ini_set('session.cookie_lifetime', 86400 * 7);
session_set_cookie_params(86400 * 7);
session_start();
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
if (isset($_POST['action'])&&isset($_POST['item'])&&isset($_POST['qty'])&&isset($_POST['from'])){
    $action = htmlspecialchars($_POST['action']);
    $item=htmlspecialchars($_POST['item']);
    if (strpos($item,"dofp")||strpos($item,'dofusplanner')||strpos($item,'dofusbook')){
        if(strpos($item,'dofusbook')){
            $dfbId = explode("-",endLessInt(explode("/",$item),1))[0];
            $json = get_html("https://www.dofusbook.net/api/stuffs/$dfbId/public");
            $items = json_decode($json)->items;
            foreach ($items as $arr){                
                $dofbItem = $arr->name;           
                if ($dofbItem!==""&&gettype($dofbItem)==="string") {
                    $query = "SELECT `itemId`,`itemName` as `name`,`craftable`,`level`,`typeName`,`iconId` FROM `0items` WHERE `itemName` = :item";
                    $result = sqldb::safesql($query, ['item' => $dofbItem]);
                    $itemId = $result[0]['itemId'];
                    if (isset($_SESSION['cart'][$itemId]['quantity']['self'])) {
                        $value = $_SESSION['cart'][$itemId]['quantity']['self'];
                        $_SESSION['cart'][$itemId]['quantity']['self'] = $value + 1;
                    } else {
                        $_SESSION['cart'][$itemId]['quantity']['self'] = 1;
                        $_SESSION['cart'][$itemId]['info']['from'] = "Dofusbook";
                        $_SESSION['cart'][$itemId]['info']['level'] = $result[0]["level"];
                        $_SESSION['cart'][$itemId]['info']['name'] = $result[0]["name"];
                        $_SESSION['cart'][$itemId]['info']['type'] = $result[0]["typeName"];
                        $_SESSION['cart'][$itemId]['info']['iconId'] = $result[0]["iconId"];
                        $_SESSION['cart'][$itemId]['info']["hdv"] = get_hdv($result[0]["typeName"]);
                        if ($result[0]['craftable']==1) {
                            $query = "SELECT `itemName`,`itemIconId`,`jobName`,`ingId0`,`ingName0`,`ingQty0`,`ingIconId0`,`ingId1`,`ingName1`,`ingQty1`,`ingIconId1`,`ingId2`,`ingName2`,`ingQty2`,`ingIconId2`,`ingId3`,`ingName3`,`ingQty3`,`ingIconId3`,`ingId4`,`ingName4`,`ingQty4`,`ingIconId4`,`ingId5`,`ingName5`,`ingQty5`,`ingIconId5`,`ingId6`,`ingName6`,`ingQty6`,`ingIconId6`,`ingId7`,`ingName7`,`ingQty7`,`ingIconId7` FROM `0recipes` WHERE `itemId`=:itemId";
                            $list = sqldb::safesql($query, ['itemId' => $itemId]);
                            if ($list[0]["jobName"]!==null){
                                $_SESSION['cart'][$itemId]['info']['metier'] = $list[0]["jobName"];
                                $recipe = "<div>";
                                for ($i = 0; $i < 8; $i++) {
                                    if ($list[0]["ingId$i"] !== null) {
                                        $componentId = $list[0]["ingId$i"];
                                        $base_qty = $list[0]["ingQty$i"];
                                        $query_compo = "SELECT `level`,`typeName` FROM `0items` WHERE `itemId`=:itemId";
                                        $comp_info = sqldb::safesql($query_compo, ["itemId" => $componentId]);
                                        $_SESSION['cart'][$itemId]['info']['craft_list'][$i] = $componentId;
                                        $recipe = $recipe . '<span class="itm"><img src="/images/all/' . $list[0]["ingIconId" . $i] . '.png" alt="' . $list[0]["ingName" . $i] . '" title="' . $list[0]["ingQty" . $i] . ' ' . $list[0]["ingName" . $i] . '"><p class="itm-qty">' . $list[0]["ingQty" . $i] . '</p></span>';
                                        $_SESSION['cart'][$componentId]['quantity'][$itemId] = intval($base_qty);
                                        $_SESSION['cart'][$componentId]['info']["level"] = $comp_info[0]['level'];
                                        $_SESSION['cart'][$componentId]['info']["name"] = $list[0]["ingName$i"];
                                        $_SESSION['cart'][$componentId]['info']["iconId"] = $list[0]["ingIconId$i"];
                                        $_SESSION['cart'][$componentId]['info']["hdv"] = get_hdv($comp_info[0]["typeName"]);;
                                    }
                                }
                                $_SESSION['cart'][$itemId]['info']['recipe'] = $recipe . "</div>";
                            }
                        }
                    }
                }
            }
        }else{
            if (substr($item, -1)==='/'){
                $url = rtrim($item,"/ ");
            }
            $id = end(explode('/',$url));
            $planner = get_html("https://www.dofusplanner.com/api/get/set/?pub_key=$id&intent=view");
            $p = json_decode($planner,true);
            foreach ($p as $key=>$array){
                $dofpItem = $array["name"];
                if ($key!='char_class'&&$key!="extra_stats"&&$dofpItem!==""&&gettype($dofpItem)==="string") {
                    $query = "SELECT `itemId`,`itemName` as `name`,`craftable`,`level`,`typeName`,`iconId` FROM `0items` WHERE `itemName` = :item";
                    $result = sqldb::safesql($query, ['item' => $dofpItem]);
                    $itemId = $result[0]['itemId'];
                    if (isset($_SESSION['cart'][$itemId]['quantity']['self'])) {
                        $value = $_SESSION['cart'][$itemId]['quantity']['self'];
                        $_SESSION['cart'][$itemId]['quantity']['self'] = $value + 1;
                    } else {
                        $_SESSION['cart'][$itemId]['quantity']['self'] = 1;
                        $_SESSION['cart'][$itemId]['info']['from'] = "DofusPlanner";
                        $_SESSION['cart'][$itemId]['info']['level'] = $result[0]["level"];
                        $_SESSION['cart'][$itemId]['info']['name'] = $result[0]["name"];
                        $_SESSION['cart'][$itemId]['info']['type'] = $result[0]["typeName"];
                        $_SESSION['cart'][$itemId]['info']['iconId'] = $result[0]["iconId"];
                        $_SESSION['cart'][$itemId]['info']["hdv"] = get_hdv($result[0]["typeName"]);
                        if ($result[0]['craftable']==1) {
                            $query = "SELECT `itemName`,`itemIconId`,`jobName`,`ingId0`,`ingName0`,`ingQty0`,`ingIconId0`,`ingId1`,`ingName1`,`ingQty1`,`ingIconId1`,`ingId2`,`ingName2`,`ingQty2`,`ingIconId2`,`ingId3`,`ingName3`,`ingQty3`,`ingIconId3`,`ingId4`,`ingName4`,`ingQty4`,`ingIconId4`,`ingId5`,`ingName5`,`ingQty5`,`ingIconId5`,`ingId6`,`ingName6`,`ingQty6`,`ingIconId6`,`ingId7`,`ingName7`,`ingQty7`,`ingIconId7` FROM `0recipes` WHERE `itemId`=:itemId";
                            $list = sqldb::safesql($query, ['itemId' => $itemId]);
                            if ($list[0]["jobName"]!==null){
                                $_SESSION['cart'][$itemId]['info']['metier'] = $list[0]["jobName"];
                                $recipe = "<div>";
                                for ($i = 0; $i < 8; $i++) {
                                    if ($list[0]["ingId$i"] !== null) {
                                        $componentId = $list[0]["ingId$i"];
                                        $base_qty = $list[0]["ingQty$i"];
                                        $query_compo = "SELECT `level`,`typeName` FROM `0items` WHERE `itemId`=:itemId";
                                        $comp_info = sqldb::safesql($query_compo, ["itemId" => $componentId]);
                                        $_SESSION['cart'][$itemId]['info']['craft_list'][$i] = $componentId;
                                        $recipe = $recipe . '<span class="itm"><img src="/images/all/' . $list[0]["ingIconId" . $i] . '.png" alt="' . $list[0]["ingName" . $i] . '" title="' . $list[0]["ingQty" . $i] . ' ' . $list[0]["ingName" . $i] . '"><p class="itm-qty">' . $list[0]["ingQty" . $i] . '</p></span>';
                                        $_SESSION['cart'][$componentId]['quantity'][$itemId] = intval($base_qty);
                                        $_SESSION['cart'][$componentId]['info']["level"] = $comp_info[0]['level'];
                                        $_SESSION['cart'][$componentId]['info']["name"] = $list[0]["ingName$i"];
                                        $_SESSION['cart'][$componentId]['info']["iconId"] = $list[0]["ingIconId$i"];
                                        $_SESSION['cart'][$componentId]['info']["hdv"] = get_hdv($comp_info[0]["typeName"]);;
                                    }
                                }
                                $_SESSION['cart'][$itemId]['info']['recipe'] = $recipe . "</div>";
                            }
                        }
                    }
                }
            }
        }
    }else{
        $query = "SELECT `itemId`, `itemName` as `name`,`craftable`,`level`,`typeName`,`iconId` FROM `0items` WHERE `itemName` = :item";
        $result = sqldb::safesql($query, ['item' => $item]);
        if (count($result) > 0) {
            $itemId = $result[0]['itemId'];
            if ($action === 'add') {
                if (isset($_SESSION['cart'][$itemId]['quantity']['self'])) {
                    $value = $_SESSION['cart'][$itemId]['quantity']['self'];
                    $_SESSION['cart'][$itemId]['quantity']['self'] = $value + intval(htmlspecialchars($_POST['qty']));
                }else{
                    $_SESSION['cart'][$itemId]['quantity']['self'] = intval(htmlspecialchars($_POST['qty']));
                    if (htmlspecialchars($_POST['from']) !== "") {
                        $_SESSION['cart'][$itemId]['info']['from'] = htmlspecialchars($_POST['from']);
                    }
                    $_SESSION['cart'][$itemId]['info']['level'] = $result[0]["level"];
                    $_SESSION['cart'][$itemId]['info']['name'] = $result[0]["name"];
                    $_SESSION['cart'][$itemId]['info']['type'] = $result[0]["typeName"];
                    $_SESSION['cart'][$itemId]['info']['iconId'] = $result[0]["iconId"];
                    $_SESSION['cart'][$itemId]['info']["hdv"] = get_hdv($result[0]["typeName"]);
                    if ($result[0]['craftable']==1) {
                        $query = "SELECT `itemName`,`itemIconId`,`jobName`,`ingId0`,`ingName0`,`ingQty0`,`ingIconId0`,`ingId1`,`ingName1`,`ingQty1`,`ingIconId1`,`ingId2`,`ingName2`,`ingQty2`,`ingIconId2`,`ingId3`,`ingName3`,`ingQty3`,`ingIconId3`,`ingId4`,`ingName4`,`ingQty4`,`ingIconId4`,`ingId5`,`ingName5`,`ingQty5`,`ingIconId5`,`ingId6`,`ingName6`,`ingQty6`,`ingIconId6`,`ingId7`,`ingName7`,`ingQty7`,`ingIconId7` FROM `0recipes` WHERE `itemId`=:itemId";
                        $list = sqldb::safesql($query, ['itemId' => $itemId]);
                        if ($list[0]["jobName"]!==null){
                            $_SESSION['cart'][$itemId]['info']['metier'] = $list[0]["jobName"];
                            $recipe = "<div>";
                            for ($i = 0; $i < 8; $i++) {
                                if ($list[0]["ingId$i"] !== null) {
                                    $componentId = $list[0]["ingId$i"];
                                    $base_qty = $list[0]["ingQty$i"];
                                    $query_compo = "SELECT `level`,`typeName` FROM `0items` WHERE `itemId`=:itemId";
                                    $comp_info = sqldb::safesql($query_compo, ["itemId" => $componentId]);
                                    $_SESSION['cart'][$itemId]['info']['craft_list'][$i] = $componentId;
                                    $recipe = $recipe . '<span class="itm"><img src="/images/all/' . $list[0]["ingIconId" . $i] . '.png" alt="' . $list[0]["ingName" . $i] . '" title="' . $list[0]["ingQty" . $i] . ' ' . $list[0]["ingName" . $i] . '"><p class="itm-qty">' . $list[0]["ingQty" . $i] . '</p></span>';
                                    $_SESSION['cart'][$componentId]['quantity'][$itemId] = intval($base_qty);
                                    $_SESSION['cart'][$componentId]['info']["level"] = $comp_info[0]['level'];
                                    $_SESSION['cart'][$componentId]['info']["name"] = $list[0]["ingName$i"];
                                    $_SESSION['cart'][$componentId]['info']["iconId"] = $list[0]["ingIconId$i"];
                                    $_SESSION['cart'][$componentId]['info']["hdv"] = get_hdv($comp_info[0]["typeName"]);;
                                }
                            }
                            $_SESSION['cart'][$itemId]['info']['recipe'] = $recipe . "</div>";
                        }
                    }
                }
            }elseif ($action === 'update') {
                $_SESSION['cart'][$itemId]['quantity']['self'] = intval(htmlspecialchars($_POST['qty']));
            }elseif ($action === 'remove') {
                if (isset($_SESSION['cart'][$itemId]['info']['craft_list'])) {
                    foreach ($_SESSION['cart'][$itemId]['info']['craft_list'] as $comp) {
                        unset($_SESSION['cart'][$comp]['quantity'][$itemId]);
                        $temp = $_SESSION['cart'][$comp]['quantity'];
                        if (count($temp) === 0) {
                            unset($_SESSION['cart'][$comp]);
                        }
                    }
                    unset ($_SESSION['cart'][$itemId]);
                } else {
                    unset($_SESSION['cart'][$itemId]['quantity']['self']);
                    $temp2 = $_SESSION['cart'][$itemId]['quantity'];
                    if (count($temp2) === 0) {
                        unset($_SESSION['cart'][$itemId]);
                    }
                }
            }
        }
    }
}

function get_hdv($type){
    $hdv = [
        //Alchimistes
        "Essence de gardien de donjon"=>"Alchimistes",
        "Fleur"=>"Alchimistes",
        "Matériel d'alchimie"=>"Alchimistes",
        "Metaria"=>"Alchimistes",
        "Plante"=>"Alchimistes",
        "Potion"=>"Alchimistes",
        "Potion d'oubli Percepteur"=>"Alchimistes",
        "Potion de conquête"=>"Alchimistes",
        "Potion de forgemagie"=>"Alchimistes",
        "Potion de téléportation"=>"Alchimistes",
        "Préparation"=>"Alchimistes",
        "Teinture"=>"Alchimistes",
        //Âmes
        "Pierre d'âme"=>"Âmes",
        "Pierre d'âme pleine"=>"Âmes",
        //Animaux
        "Caution" => "Animaux",
        "Certificat de Dragodinde" => "Animaux",
        "Certificat de Muldo" => "Animaux",
        "Certificat de familier" => "Animaux",
        "Certificat de montilier" => "Animaux",
        "Familier" => "Animaux",
        "Fantôme de Familier" => "Animaux",
        "Fantôme de Montilier" => "Animaux",
        "Harnachement" => "Animaux",
        "Mimibiote" => "Animaux",
        "Montilier" => "Animaux",
        "Objet vivant" => "Animaux",
        "Oeuf de familier" => "Animaux",
        "Potion de familier" => "Animaux",
        "Potion de montilier" => "Animaux",
        //Bijoutiers
        "Amulette" => "Bijoutiers",
        "Anneau" => "Bijoutiers",
        //Bricoleurs
        "Arme magique" => "Bricoleurs",
        "Clef" => "Bricoleurs",
        "Filet de capture" => "Bricoleurs",
        "Objet d'élevage" => "Bricoleurs",
        "Prisme" => "Bricoleurs",
        //Bûcherons
        "Bois" => "Bûcherons",
        "Bourgeon" => "Bûcherons",
        "Planche" => "Bûcherons",
        "Racine" => "Bûcherons",
        "Substrat" => "Bûcherons",
        "Sève" => "Bûcherons",
        "Écorce" => "Bûcherons",
        //Chasseur
        "Viande" => "Chasseurs",
        "Viande comestible" => "Chasseurs",
        //Cordonnier
        "Bottes" => "Cordonniers",
        "Ceinture" => "Cordonniers",
        //Documents
        "Document" => "Documents",
        "Parchemins d'attitude" => "Documents",
        "Parchemins d'expérience" => "Documents",
        "Parchemins d'émôticones" => "Documents",
        "Parchemins de caractéristique" => "Documents",
        "Parchemins de sortilège" => "Documents",
        //Façonneurs
        "Bouclier" => "Façonneurs",
        "Idole" => "Façonneurs",
        "Galet" => "Façonneurs",
        "Trophée" => "Façonneurs",
        //Forgerons
        "Dague" => "Forgerons",
        "Faux" => "Forgerons",
        "Hache" => "Forgerons",
        "Marteau" => "Forgerons",
        "Pelle" => "Forgerons",
        "Pioche" => "Forgerons",
        "Épée" => "Forgerons",
        //Mineurs
        "Alliage" => "Mineurs",
        "Minerai" => "Mineurs",
        "Pierre brute" => "Mineurs",
        "Pierre magique" => "Mineurs",
        "Pierre précieuse" => "Mineurs",
        //Parchemins liées
        "Parchemin de recherche" => "Parchemins liées",
        //Paysans
        "Céréale" => "Paysans",
        "Friandise" => "Paysans",
        "Huile" => "Paysans",
        "Pain" => "Paysans",
        //Pêcheurs
        "Jus de Poisson" => "Pecheurs",
        "Poisson" => "Pecheurs",
        "Poisson comestible" => "Pecheurs",
        //Ressources
        "Aile" => "Ressources",
        "Carapace" => "Ressources",
        "Champignon" => "Ressources",
        "Conteneur" => "Ressources",
        "Coquille" => "Ressources",
        "Cuir" => "Ressources",
        "Etoffe" => "Ressources",
        "Fruit" => "Ressources",
        "Gelée" => "Ressources",
        "Graine" => "Ressources",
        "Laine" => "Ressources",
        "Légume" => "Ressources",
        "Matériel d'exploration" => "Ressources",
        "Nowel" => "Ressources",
        "Oeil" => "Ressources",
        "Oeuf" => "Ressources",
        "Oreille" => "Ressources",
        "Os" => "Ressources",
        "Patte" => "Ressources",
        "Peau" => "Ressources",
        "Plume" => "Ressources",
        "Poil" => "Ressources",
        "Poudre" => "Ressources",
        "Queue" => "Ressources",
        "Ressources diverses" => "Ressources",
        "Vêtement" => "Ressources",
        //Runes
        "Compagnon" => "Runes",
        "Orbe de forgemagie" => "Runes",
        "Rune de forgemagie" => "Runes",
        //Sculpteurs
        "Arc" => "Sculpteurs",
        "Baguette" => "Sculpteurs",
        "Bâton" => "Sculpteurs",
        //Tailleurs
        "Cape" => "Tailleurs",
        "Chapeau" => "Tailleurs",
        "Costume" => "Tailleurs",
        "Sac à dos" => "Tailleurs"
    ];
    if (array_key_exists($type,$hdv)){
        $result = $hdv[$type];
    }else{
        $result = 'Indéfini';
    }
    return $result;
}


function endLessInt( $array, $int) {
    $size = count ($array)-1;
    return $array[$size-$int];
}

function get_html($url) {
    $ch = curl_init();
    $timeout = 10;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept-Language: fr']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = trim(curl_exec($ch));
    curl_close($ch);
    return $data;
}