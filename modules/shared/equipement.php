<?php
class equipement extends item{
    public function url_loader($url){
        parent::url_loader($url);
        $this->dom_loader($url);
        $bonus_list = self::nodefinder(".//div[contains(@class,'ak-title')]", self::$effets_area);
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
        $r=0;
        foreach ($bonus_list as $key=>$value) {
            $var = trim($value->textContent);
            $var_split = explode(' ', $var);
            $var_size = count($var_split);
            if (strstr($var, '%') !== false) {
                $needle = "%" . explode('%', $var)[1];
            } elseif ($var_size > 2 && intval($var_split[$var_size - 2]) === 0) {
                $needle = $var_split[$var_size - 2] . ' ' . $var_split[count($var_split) - 1];
            } else {
                $needle = $var_split[$var_size - 1];
            }
            if (array_key_exists($needle, $stats_array)) {
                $value = $stats_array[$needle];
                if (strstr($var, 'à')) {
                        $this->{$value . '_max'} = intval($var_split[2]);
                }
                $this->{$value . '_min'} = intval($var_split[0]);
            } else {
                switch ($var_split[0]){
                    case ('Titre'):
                        $this->{$Titre} = str_replace('Titre : ', '', $var);
                        break;
                    case ('Attitude'):
                        $this->{$Attitude} = str_replace('Attitude ', '', $var);
                        break;
                    default:
                        $this->{'random' . $r} = $var;
                        $r++;
                }
            }
        }
    }
}