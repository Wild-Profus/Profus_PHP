<?php
class arme extends item{
    public function url_loader($url)    {
        parent::url_loader($url);
        $this->dom_loader($url);
        $page_main_content_panel = self::nodefinder("./div[contains(@class,'ak-container ak-panel') and not(contains(@class,'ak-crafts'))]")[0];
        $page_stats_panel = self::nodefinder("//div[contains(@class,'ak-container ak-content-list ak-displaymode-col') or contains(@class,'ak-container ak-content-list ak-displaymode-image-col')]",$page_main_content_panel)[0];
        $bonus_list = self::nodefinder(".//div[contains(@class,'ak-title')]", $page_stats_panel);
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
        $r=0;
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
                    if(isset($results[$value . '_max'])){
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
                if(isset($results[$value . '_min'])){
                    for ($i=1;$i<5;$i++){
                        if(!isset($results[$value . '_min_'.$i])) {
                            $results[$value . '_min_'.$i] = intval($var_split[0]);
                            break;
                        }
                    }
                }else{
                    $results[$value . '_min'] = intval($var_split[0]);
                }
            } else {
                switch ($var_split[0]){
                    case ('Titre'):
                        $this->{$Titre} = str_replace('Titre : ', '', $var);
                        break;
                    case ('Attitude'):
                        $this->{$Attitude} = str_replace('Attitude ', '', $var);
                        break;
                    case ('Incarnation'):
                        $this->{$Incarnation} = true;
                        break;
                    case ('Arme'):
                        $this->{$Chasse} = true;
                        break;
                    default:
                        $this->{'$random' . $r} = $var;
                        $r++;
                }
            }
        }
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
    }
}