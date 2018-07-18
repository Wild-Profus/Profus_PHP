<?php
require_once dirname(__FILE__, 2) . '/BDD/sqlserver.php';
function itemgetinfo($globaltype,$itemid){
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->preserveWhiteSpace = false;
    $results = array();
    $results['dofusid'] = urlencode($itemid);
    $results['dofusurl'] = dofusurl($globaltype, $results['dofusid']);
    $s =  0;
    do{
        $loadpage = file_get_contents($results['dofusurl']);
        if($s>9){
            trace($itemid);
            exit();
        }
        $s++;
    }while(!$loadpage);
    $webpage = str_replace(' < ',' &lt; ',$loadpage);
    $doc->loadHTML($webpage);
    $doc->validateOnParse = true;
    $xpath = new DomXPath($doc);
    $page_content_main = $xpath->query("//div[contains(@class,'ak-container ak-panel-stack ak-glue')]")[0];
    $results['name'] = format(classfinder('ak-return-link',$page_content_main,$xpath,0));
    $page_content_panel = $xpath->query("./div[contains(@class,'ak-container ak-panel') and not(contains(@class,'ak-crafts'))]",$page_content_main);
    $img= $xpath->query(".//img/@src",$page_content_panel[0])[0]->textContent;
    getimage($img,$results['name']);
    $results['img']='<img src="/dofustestplateform/images/all/'.$results['name'].'.png" alt="'.$results['name'].'" title="'.$results['name'].'"/>';
    $results['mini']='<img src="/dofustestplateform/images/all/'.$results['name'].' 40.png" alt="'.$results['name'].'" title="'.$results['name'].'"/>';
    $results['level'] = intval(str_replace("Niveau : ","",classfinder('ak-encyclo-detail-level',$page_content_panel[0],$xpath,0)));
    $results['type'] = trim(str_replace("Type : ","",classfinder('ak-encyclo-detail-type',$page_content_panel[0],$xpath,0)));
    if ($globaltype!=='panoplies'){
        $results['description'] = format(trim(classfinder('ak-panel-content',$page_content_panel[0],$xpath,1)));
        if ($page_content_panel[2]!==NULL){
            $set = $xpath->query(".//div[contains(@class,'ak-panel-title')]/a",$page_content_panel[1])[0];
            if ($set!==null){
                $results['set'] = format($set->textContent);
                $results['set_url'] = urlencode("http://www.dofus.com".$set->getAttribute('href'));
            }
        };
        $page_craft = $xpath->query(".//div[contains(@class,'ak-container ak-panel ak-crafts')]",$page_content_main)[0];
        if ($page_craft!==NULL) {
            $results['craftable'] = true;
            $metier = $xpath->query(".//div[contains(@class,'ak-panel-intro')]", $page_craft)[0]->textContent;
            $results['metier'] = explode(' ', trim($metier))[0];
            $craft_array = buildcraftarray();
            if (array_key_exists($globaltype,$craft_array)){
                $results['craftxp']=$craft_array[$globaltype]*$results['level'];
            }elseif (array_key_exists($results['type'],$craft_array)){
                $results['craftxp']=$craft_array[$results['type']]*$results['level'];
            }elseif (array_key_exists($results['metier'],$craft_array)){
                $results['craftxp']=$craft_array[$results['metier']]*$results['level'];
    }       else{
                $results['craftxp']=0;
            }
            $results['craftbtn']="<i class='tocraft glyphicon glyphicon-plus btn btn-success' value='".$results['name']."'></i>";
            $results['shopbtn']="<i class='toshop glyphicon glyphicon-shopping-cart btn btn-success' value='".$results['name']."'></i>";
            $craft_area = $xpath->query(".//div[contains(@class,'row ak-container')]", $page_craft)[0];
            $items_lst = $xpath->query(".//*[contains(@class,'ak-title')]//*[contains(@class,'ak-linker')]", $craft_area);
            $items_qty = $xpath->query(".//*[contains(@class,'ak-front')]", $craft_area);
            $items_url = $xpath->query(".//img", $craft_area);
            $results['recette']="";
            for ($i = 0; $i < $items_lst->length; $i++) {
                $results['item' . $i] = format($items_lst[$i]->textContent);
                $results['qitem' . $i] = intval($items_qty[$i]->textContent);
                getimage($items_url[$i]->getAttribute('src'), $results['item' . $i]);
                $results['recette'] = $results['recette'].'<img src="/dofustestplateform/images/all/'.$results['item'.$i].' 40.png" alt="'.$results['item'.$i].'" title="'.$results['item'.$i].'"/><span class=\'qty\'>x'.$results['qitem'.$i].'</span>';
            }
        }
    }
    $end = 2;
    do {
        if ($globaltype === 'panoplies') {
            $pano_bonus = $xpath->query(".//div[contains(@class,'set-bonus-list set-bonus-" . $end . "')]", $page_content_panel[0]);
            if ($pano_bonus[0] !== NULL) {
                $results['setbonus'] = $end;
                $sub_area = $pano_bonus;
                $end++;
            } else {
                break;
            }
        }else{
            $sub_area = $xpath->query("//div[contains(@class,'ak-container ak-content-list ak-displaymode-col') or contains(@class,'ak-container ak-content-list ak-displaymode-image-col')]",$page_content_panel[0]);
            $end = true;
        }
        $stats_array = buildarray($results['type'],$globaltype);
        $bonus_list = $xpath->query(".//*[contains(@class,'ak-title')]", $sub_area[0]);
        $r = 0;
        foreach ($bonus_list as $key=>$value) {
            $var = trim($value->textContent);
            $var_split = explode(' ',$var);
            $var_size = count($var_split);
            if (strstr($var,'%')!==false){
                $needle = "%".explode('%',$var)[1];
            }elseif ($var_size>2&&intval($var_split[$var_size-2])===0){
                $needle = $var_split[$var_size-2].' '.$var_split[count($var_split)-1];
            }else{
                $needle = $var_split[$var_size-1];
            }
            if (array_key_exists($needle, $stats_array)){
                $value = $stats_array[$needle];
                if (strstr($var,'à')){
                    if(isset($results[$value . '_max'])&&$globaltype=="armes"){
                        for ($i=1;$i<5;$i++){
                            if(!isset($results[$value . '_max_'.$i])) {
                                $results[$value . '_max_'.$i] = intval($var_split[2]);
                                break;
                            }
                        }
                    }else{
                        $results[$value . '_max'] = intval($var_split[2]);
                    }
                }
                if(isset($results[$value . '_min'])&&$globaltype=="armes"){
                    for ($i=1;$i<5;$i++){
                        if(!isset($results[$value . '_min_'.$i])) {
                            $results[$value . '_min_'.$i] = intval($var_split[0]);
                            break;
                        }
                    }
                }else{
                    $results[$value . '_min'] = intval($var_split[0]);
                }
            }else{
                if ($var_split[0]=='Titre'){
                    $results['Titre'] = str_replace('Titre : ','',$var);
                }elseif ($var_split[0]=='Attitude'){
                    $results['Attitude'] = str_replace('Attitude ','',$var);
                }elseif ($var_split[0]=='Incarnation'){
                    $results['Incarnation']= true;
                }elseif ($var_split[0]=='Arme'){
                    $results['Chasse']= true;
                }else{
                    $results['random'.$r] = $var;
                    $r++;
                }
            }
        }
        if ($globaltype==="armes"){
            $attack_list = $xpath->query(".//*[contains(@class,'ak-title-info')]", $sub_area[1]);
            $pa_uses = explode('(',$attack_list[0]->textContent);//pa cost & uses per turn
            $results['uses'] = intval(explode(' ',$pa_uses[1])[0]);
            $results['PA_cost'] = intval(trim($pa_uses[0]));
            $po = $attack_list[1]->textContent;//pa_cac_min po_cac_max
            $po_split = explode(' ',$po);
            if (strstr($po,'à')){$results['PO_cac_max'] = intval($po_split[2]);}
            $results['PO_cac_min'] = intval($po_split[0]);
            $cc = explode(' ',$attack_list[2]->textContent);//cc and cc bonus
            $results['CC_odd'] = intval(str_replace('1/','',$cc[0]));
            if ($results['CC_odd']>0){
                $results['CC_bonus'] = intval(str_replace(')','',str_replace('(+','',$cc[1])));
            }
            $c=2;
        }else{
            $c=1;
        }
        if ($sub_area[$c]!==NULL&&$globaltype!=='ressources') {
            $condition_list = str_replace('de base','',$xpath->query(".//*[contains(@class,'ak-title')]", $sub_area[$c])[0]->textContent);
            $condition = explode('et', $condition_list);
            foreach ($condition as $key=>$value){
                $results['condition'.$key] = trim($value);
            }
        }
        $sqlpar = sqlfromarray($results);
        return sqlquery($results['name'], $sqlpar[0], $sqlpar[1]);
    }while($end!==true);
}

//Fonctions
//Retourne l'url dofus l'objet
function dofusurl($typeof,$dofusid){
    return "http://www.dofus.com/fr/mmorpg/encyclopedie/".$typeof."/$dofusid";
}
//Retourne un domnodelist contenant les éléments répondant à la recherche
function classfinder($classname,$indomnode,$xpath,$key){
    return trim($xpath->query(".//*[contains(@class,'".$classname."')]",$indomnode)[$key]->textContent);
}
// Enlève certains caractères problématiques (/,’) du nom
function format($filename){
    $tochange=["’"=>"'","/"=>"-",'Œ'=>'Oe','œ'=>'oe','ō'=>'o','"'=>''];
    $name = noblanc(strtr($filename,$tochange));
    return $name;
}
function noblanc($var){
    return trim(preg_replace('/\s+/', ' ', $var));
}

//Save img if not exist
function getimage($img,$filename){
    $name =  iconv("UTF-8", "ISO-8859-1//TRANSLIT",trim($filename));
    $imgpath = dirname(__FILE__, 3) . "\\images\\all\\";
    if (stristr($img,'/200/')!==false){
        if (!file_exists($imgpath.$name.".png")){
            copy($img,$imgpath.$name.".png");
        }
        if (!file_exists($imgpath.$name." 40.png")){
            $min = str_replace("/200/","/52/",str_replace(".png",".w40h40.png",$img));
            copy($min,$imgpath.$name." 40.png");
        }
    }elseif(stristr($img,'/52/')!==false){
        if (!file_exists($imgpath.$name." 40.png")){
            copy($img,$imgpath.$name." 40.png");
        }
    }else{
        if (!file_exists($imgpath.$name.".png")){
            copy($img,$imgpath.$name.".png");
        }
    }
}

function buildarray($type,$gtype){
    if ($type=="Bouclier"){
        $stats_array = [
            "% Résistance Neutre JCJ" => "RePerNeutreJCJ",
            "% Résistance Terre JCJ" => "RePerTerreJCJ",
            "% Résistance Feu JCJ" => "RePerFeuJCJ",
            "% Résistance Air JCJ" => "RePerAirJCJ",
            "% Résistance Eau JCJ" => "RePerEauJCJ",
            "Résistance Neutre JCJ" => "ReNeutreJCJ",
            "Résistance Terre JCJ" => "ReTerreJCJ",
            "Résistance Feu JCJ" => "ReFeuJCJ",
            "Résistance Air JCJ" => "ReAirJCJ",
            "Résistance Eau JCJ" => "ReEauJCJ"];
    }elseif($gtype=="Consommable"){
        $stats_array = [
            "Vie" => "Vie",
            "points d'énergie" => "Energie",
            "Sagesse" => "Sa",
            "Prospection" => "Pro",
            "Puissance" => "Pui",
            "Force" => "Fo",
            "Intelligence" => "Ine",
            "Agilité" => "Age",
            "Chance" => "Cha",
            "Vitalité" => "Vi",
            "Initiative" => "Ini",];
    }else{
        $stats_array = [
            "PA" => "PA",
            "PM" => "PM",
            "Portée" => "PO",
            "Invocation" => "Invo",
            "Invocations" => "Invo",
            "Dommages" => "Do",
            "Soins" => "So",
            "% Critique" => "Cri",
            "dommages" => "DoRen",
            "Retrait PA" => "RetPa",
            "Retrait PM" => "RetPm",
            "Esquive PA" => "RePa",
            "Esquive PM" => "RePm",
            "% Résistance Neutre" => "RePerNeutre",
            "% Résistance Terre" => "RePerTerre",
            "% Résistance Feu" => "RePerFeu",
            "% Résistance Air" => "RePerAir",
            "% Résistance Eau" => "RePerEau",
            "Dommages Critiques" => "DoCri",
            "Dommages Poussée" => "DoPou",
            "Dommages Pièges" => "Pi",
            "Dommages Neutre" => "DoNeutre",
            "Dommages Terre" => "DoTerre",
            "Dommages Feu" => "DoFeu",
            "Dommages Air" => "DoAir",
            "Dommages Eau" => "DoEau",
            "Tacle" => "Tac",
            "Fuite" => "Fui",
            "Sagesse" => "Sa",
            "Prospection" => "Pro",
            "Résistance Neutre" => "ReNeutre",
            "Résistance Terre" => "ReTerre",
            "Résistance Feu" => "ReFeu",
            "Résistance Air" => "ReAir",
            "Résistance Eau" => "ReEau",
            "Résistance Poussée" => "RePou",
            "Résistance Critiques" => "ReCri",
            "Puissance (pièges)" => "PiPer",
            "Puissance" => "Pui",
            "Force" => "Fo",
            "Intelligence" => "Ine",
            "Agilité" => "Age",
            "Chance" => "Cha",
            "Vitalité" => "Vi",
            "Initiative" => "Ini",
            "Pods" => "Pod",
        ];
        if ($gtype == "armes") {
            $stats_array["(dommages Neutre)"] = "DgtNeutre";
            $stats_array["(dommages Terre)"] = "DgtTerre";
            $stats_array["(dommages Feu)"] = "DgtFeu";
            $stats_array["(dommages Air)"] = "DgtAir";
            $stats_array["(dommages Eau)"] = "DgtEau";
            $stats_array["(vol Neutre)"] = "VolNeutre";
            $stats_array["(vol Terre)"] = "VolTerre";
            $stats_array["(vol Feu)"] = "VolFeu";
            $stats_array["(vol Air)"] = "VolAir";
            $stats_array["(vol Eau)"] = "VolEau";
            $stats_array["(PV rendus)"] = "HP_back";
        }
    }
    return $stats_array;
}

function buildcraftarray(){
    $craft_array = [
        "Essence de gardien de donjon" => 4,
        "Matériel d'alchimie"=>4,
        "Potion" => 1,
        "Potion d'oubli Percepteur" => 20,
        "Potion de conquête" => 2,
        "Potion de familier" => 20,
        "Potion de forgemagie" => 2,
        "Potion de montilier" => 20,
        "Potion de téléportation" => 4,
        "Poudre" => 5,
        "Préparation" => 1,
        "Teinture" => 1,
        'Bûcheron'=> 4,
        'Bijoutier'=> 20,
        'Cordonnier'=> 20,
        'Forgeron'=> 20,
        'Sculpteur'=> 20,
        'Tailleur'=> 20,
        'Chasseur' => 2,
        "Paysan" => 1,
        "Poisson comestible" => 20,
        "Jus de Poisson" => 4,
        "Idoles" => 40,
        "Trophée" => 60,
        "Bouclier" => 20,
        "Matériel d'exploration" => 20,
        "Clef" => 4,
        "Filet de capture" => 4,
        "Harnachement" => 20,
        "Objet d'élevage" => 20,
        "Ressources diverses" => 0,
        "Pierre précieuse" => 2,
        "Pierre d'âme" => 2,
        "Orbe de forgemagie" => 5,
        "Alliage" => 6];
    return $craft_array;
}

function sqlfromarray($array){
    $column='';
    $values='';
    foreach ($array as $key => $value){
        $column = $column.',`'.$key.'`';
        if (is_bool($value)){
            $values = $values.",".$value;
        }
        elseif (is_int($value)){
            $values = $values.",".$value;
        }
        elseif (is_string($value)){
            $values = $values.",'".preg_replace("/'/","\'",$value)."'";
        }
        else{
            $values = $values.",'".$value."'";
        }
    }
    $columns=preg_replace('/^,/','',$column);
    $valuess=preg_replace('/^,/','',$values);
    return array($columns,$valuess);
}

function sqlquery($name,$column,$values,$table='items'){
    try {
        $bdd = sqlserverconnect();
        try {
            $bdd->exec("REPLACE INTO $table ($column) VALUES ($values)");
            $bdd=null;
            $data = "Requête pour $name correctement exécutée.";
            return array($data,true);
        } catch (PDOException $e) {
            $data = "Erreur SQL pour $name : " . $e->getMessage();
            return $data;
        }
    } catch (PDOException $e) {
        $data = "Erreur lors de la connection SQL pour $name : " . $e->getMessage();
        return $data;
    }
}

function sqlupdate($name,$column,$value,$table='items',$selector='name'){
    try {
        $bdd = sqlserverconnect();
        try {
            $bdd->exec("UPDATE `$table` SET `$column`=$value WHERE `$selector` LIKE '$name'");
            $bdd=null;
            $data = "Requête pour $name correctement exécutée.";
            return array($data,true);
        } catch (PDOException $e) {
            $data = "Erreur SQL pour $name : " . $e->getMessage();
            return $data;
        }
    } catch (PDOException $e) {
        $data = "Erreur lors de la connection SQL pour $name : " . $e->getMessage();
        return $data;
    }
}

function sqlidcoladd($name){
    $table = 'idoles';
    try {
        $bdd = sqlserverconnect();
        try {
            $bdd->exec("ALTER TABLE $table ADD `$name` DOUBLE  DEFAULT 1");
            $bdd=null;
            $data = "Requête pour $name correctement exécutée.";
            return array($data,true);
        } catch (PDOException $e) {
            $data = "Erreur SQL pour $name : " . $e->getMessage();
            return $data;
        }
    } catch (PDOException $e) {
        $data = "Erreur lors de la connection SQL pour $name : " . $e->getMessage();
        return $data;
    }
}