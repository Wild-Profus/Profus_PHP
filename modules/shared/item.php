<?php
class item{
    public $name;
    protected static $stats_column;
    protected static $stats_column_bind;
    protected static $dom_doc;
    protected static $main_xpath;
    protected static $main_content;
    protected static $main_panel;
    protected static $effets_area;
    const version = "b_";

    public function __construct($name=''){
        if ($name!==''){
            $this->name = self::format($name);
        }
    }

    public function __set($name,$value){
        $this->{$name} = $value;
    }

    public function dom_loader($type,$url){
        libxml_use_internal_errors(true);
        $this->dofus_url = $url;
        $this->global_type = $type;
        $this->main_id = explode('-',$this->dofus_url)[0];
        $this->dofus_url = urlencode($this->dofus_url);
        //Domdoc
        $domdoc = new DOMDocument();
        $domdoc->preserveWhiteSpace = false;
        $s =  0;
        //Page loading
        do{
            $loadpage = file_get_contents("http://www.dofus.com/fr/mmorpg/encyclopedie/$type/$url");
            if($s>9){
                $callers = debug_backtrace();
                $error_log = "=====================================================================================================================================================".PHP_EOL
                    .date('Y-m-d H:i:s').PHP_EOL;
                foreach ($callers as $level=>$caller){
                    $error_log = $error_log."Function {$caller['function']} (class {$caller['class']}) in file {$caller['file']} (line {$caller['line']})".PHP_EOL;

                }
                file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/items.log",$error_log, FILE_APPEND | LOCK_EX);
                exit();
            }
            $s++;
        }while(!$loadpage);
        //Page format to read some special chars otherwise ignored
        $webpage = preg_replace('/\s+/', ' ', str_replace(' < ',' &lt; ',$loadpage));
        $domdoc->loadHTML($webpage);
        //Xpath
        self::$dom_doc = $domdoc;
        self::$main_xpath = new DomXPath($domdoc);
        self::$main_content = self::$main_xpath->query("//div[contains(@class,'ak-container ak-panel-stack ak-glue')]")[0];
        self::$main_panel = self::$main_xpath->query(".//div[text()[contains(.,'Description')]]/../../../..",self::$main_content)[0];
        //Name
        $this->name = self::format(self::$main_xpath->query("//h1[contains(@class,'ak-return-link')]")[0]->textContent);
        //Image
        $img = new img($this->name);
        $img_url = self::$main_xpath->query(".//img/@src", self::$main_content)[0]->textContent;
        if(stripos($img_url,'renderer')===false){
            $img_array = explode('/',$img_url);
            $this->img_id = explode('.',$img_array[count($img_array)-1])[0];
            $img->id_save($this->img_id);
        }else{
            $this->img_id = "";
            $img->url_save($img_url,' 200');
        }
        $img = null;
        //Level
        $this->level = intval(str_replace("Niveau : ","",self::$main_xpath->query(".//div[contains(@class,'ak-encyclo-detail-level')]", self::$main_panel)[0]->textContent));
        //Type
        $this->type = trim(self::$main_xpath->query(".//div[contains(@class,'ak-encyclo-detail-type')]/span", self::$main_panel)[0]->textContent);
        //Description
        $this->description = trim(self::$main_xpath->query(".//div[text()[contains(.,'Description')]]/following-sibling::div", self::$main_panel)[0]->textContent);
        //Stats
        $effects_area = self::$main_xpath->query(".//div[text()[contains(.,'Effets')]]/following-sibling::div",self::$main_panel)[0];
        if ($effects_area!==null){
            $this->{"stats_id"} = $this->main_id;
            $effects_list = self::$main_xpath->query(".//*[contains(@class,'ak-title') and not (contains(@class,'ak-title-info'))]",$effects_area);
            $k = 0;
            $pa = false;
            $s = 0;
            $o = 0;
            foreach ($effects_list as $key=>$effect){
                new debug($effects_list[$key]->nodeValue);
                if (($key>0)&&($effects_list[$key]->nodeValue==$effects_list[$key-1]->nodeValue)){
                    $k++;
                }else{
                    $k=0;
                }
                $values = trim($effect->nodeValue);
                if(preg_match_all('/\-?\d+/', $values, $numbers)){
                    $last = end($numbers[0]);
                    $stat = explode($last,$values)[1];
                    $replaces_pairs = ['(dommages'=>'Degats','%'=>'per','é'=>'e','è'=>'e','('=>'',')'=>'',"'"=>''];
                    $stat = trim(strtr($stat,$replaces_pairs));
                    $size = count(explode(' ',$values));
                    if ($size>5){
                        $this->{"other".$o} = $values ;
                        self::column("other".$o);
                        $o++;
                    }else{
                        $stat = trim(str_replace(' ', '_', $stat));
                        if ($this->global_type==='armes'&&$pa===false){
                            if (stripos($values,'(')!==false){
                                $pa=false;
                            }elseif(stripos($values,'pa')){
                                $stat = 'pa_cible';
                                $pa=true;
                            }else{
                                $pa=true;
                            }
                        }
                        if (count($numbers[0])>1){
                            $this->{$stat."_min_".$k}= $numbers[0][0];
                            $this->{$stat."_max_".$k}= $last;
                            self::column($stat."_min_".$k);
                            self::column($stat."_max_".$k);
                        }else{
                            $this->{$stat."_max_".$k}= $last;
                            self::column($stat."_max_".$k);
                        }
                    }
                }elseif(stripos(':',$values)){
                    $stat = trim(explode(':',$values)[0]);
                    $this->{$stat} = trim(explode(':',$values)[1]);
                    self::column($stat);
                }else{
                    $this->{'special'.$s} = $values;
                    self::column('special'.$s);
                }
            }
        }
        //Caracs armes
        $features_area = self::$main_xpath->query(".//div[text()[contains(.,'Caractéristiques')]]/following-sibling::div",self::$main_panel)[0];
        if ($features_area!==null&&$this->global_type==="armes"){
            $this->{"armes_id"} = $this->main_id;
            $features__list = self::$main_xpath->query(".//*[contains(@class,'ak-title') and not (contains(@class,'ak-title-info'))]",$features_area);
            foreach ($features__list as $key=>$feature) {
                $value = trim($feature->getElementsByTagName('span')[0]->nodeValue);
                if (strpos($value,'à')){
                    $range = explode('à',$value);
                    $this->{str_replace('é','e',trim(explode(':',$feature->nodeValue)[0]).'_maxi')} = trim($range[1]);
                    $this->{str_replace('é','e',trim(explode(':',$feature->nodeValue)[0]).'_mini')} = trim($range[0]);
                }elseif (strpos($value,'utilisation')){
                    $uses = explode('(', $value);
                    $this->{"cost"} = trim($uses[0]);
                    $this->{"uses"} = trim(explode(' ', $uses[1])[0]);
                }elseif (strpos($value,'/')){
                    $cc = explode('(',$value);
                    $this->{"CC"} = trim(str_replace('1/','',$cc[0]));
                    $this->{"CC_bonus"} = trim(str_replace('+','',str_replace(')','',$cc[1])));
                }else{
                    $this->{str_replace('é','e',trim(explode(':',$feature->nodeValue)[0]).'_mini')} = null;
                    $this->{str_replace('é','e',trim(explode(':',$feature->nodeValue)[0]).'_maxi')} = $value;
                }
            }
        }elseif ($features_area!==null&&$this->global_type==="montures") {
            $this->{"rides_id"} = 20000+$this->main_id;
            $this->main_id = $this->rides_id;
            $this->stats_id = $this->rides_id;
            $features__list = self::$main_xpath->query(".//*[contains(@class,'ak-title') and not (contains(@class,'ak-title-info'))]", $features_area);
            foreach ($features__list as $key => $feature) {
                $value = trim($feature->getElementsByTagName('span')[0]->nodeValue);
                $this->{str_replace(' ','_',str_replace("d'","",str_replace('é','e',trim(explode(':',$feature->nodeValue)[0]))))}=$value;
            }
            $ride_array = ['Generation','Nombre_de_pods','Temps_de_gestation','Maturite','Energie','Vitesse','Vitesse_de_deplacement','Taux_apprentisage','Capturable'];
            foreach ($ride_array as $ride){
                if (!isset($this->{$ride})){
                    $this->{$ride}=null;
                }
            }
        }
        //Conditions
        $conditions_area = self::$main_xpath->query(".//div[text()[contains(.,'Conditions')]]/following-sibling::div",self::$main_panel)[0];
        if ($conditions_area!==null){
            $this->{"stats_id"} = $this->main_id;
            $condition_list = str_replace('de base','',self::$main_xpath->query(".//*[contains(@class,'ak-title')]", $conditions_area)[0]->textContent);
            $condition = explode('et', $condition_list);
            foreach ($condition as $key=>$value){
                $this->{'condition'.$key} = trim($value);
                self::column('condition'.$key);
            }
        }
        //Recette
        $craft_panel = self::$main_xpath->query(".//div[text()[contains(.,'Recette')]]/following-sibling::div",self::$main_content)[0];
        if ($craft_panel!==null){
            $this->{"craftable"} = true;
            $this->{"craft_id"} = $this->main_id;
            $this->{"metier"} = explode(' ',trim(self::$main_xpath->query(".//div[contains(@class,'ak-panel-intro')]",$craft_panel)[0]->textContent))[0];
            $this->{"xp"} = 0;
            $components_array = self::$main_xpath->query(".//div[contains(@class,'ak-list-element')]",$craft_panel);
            foreach ($components_array as $key=>$component){
                $this->{"item".$key} = self::format($component->getElementsByTagName('a')[1]->getElementsByTagName('span')[0]->nodeValue);
                $this->{"item".$key."_id"} = explode('-',end(explode('/',$component->getElementsByTagName('a')[1]->getAttribute('href'))))[0];
                $img = new img($this->{"item".$key});
                $img->id_save(explode('.',end(explode('/',$component->getElementsByTagName('img')[0]->getAttribute('src'))))[0]);
                $this->{"item".$key."_qty"} = str_replace(' x','',trim($component->getElementsByTagName('div')[0]->textContent));
            }
            for ($k=$components_array->length;$k<8;$k++){
                $this->{"item".$k} = null;
                $this->{"item".$k."_id"} = null;
                $this->{"item".$k."_qty"} = null;
            }
        }else{$this->{"craftable"} = false;}
        //Panoplie
        $set_panel = self::$main_xpath->query(".//a[text()[contains(.,'Panoplie')]]",self::$main_content)[0];
        if ($set_panel!==null){
            $this->{"set_url"}=end(explode('/',$set_panel->getAttribute('href')));
            $this->{"set_id"}=explode('-',$this->set_url)[0];
            $this->{"set_name"}=$set_panel->textContent;
        }else{
            $this->{"set_url"} = null;
            $this->{"set_id"} = null;
            $this->{"set_name"} = null;
        }
    }

    protected static function format($string){
        $replace_pairs = [
            "’"=>"'",
            "/"=>"-",
            '"'=>'',
            ':'=>'',
            'Œ'=>'Oe',
            'œ'=>'oe',
            'Ō'=>'O',
            'ō'=>'o'];
        return trim(str_replace(['   ','  '], ' ', strtr($string,$replace_pairs)));
    }

    protected function column($column,$table=self::version.'stats'){
        $query = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'profusovlw2459' AND TABLE_NAME = '$table' AND COLUMN_NAME = '$column'";
        if(count(sqldb::safesql($query,false,true))==0){
            sqldb::safesql("ALTER TABLE `$table` ADD `$column` INT",false,false);
        };
        self::$stats_column = self::$stats_column.",`$column`";
        self::$stats_column_bind = self::$stats_column_bind.",:$column";
    }

    public function save(){
        if (isset($this->{"armes_id"})){
            $armes = "REPLACE INTO `".self::version."armes` (`armes_id`,`cost`,`uses`,`Portee_mini`,`Portee_maxi`,`CC`,`CC_bonus`)
                        VALUES(:armes_id,:cost,:uses,:Portee_mini,:Portee_maxi,:CC,:CC_bonus);";
        }else{$armes="";}
        if ($this->{"craftable"}===true){
            $craft = "REPLACE INTO `".self::version."craft` (`craft_id`,`metier`,`xp`,`item0`,`item0_id`,`item0_qty`,`item1`,`item1_id`,`item1_qty`,`item2`,`item2_id`,`item2_qty`,`item3`,`item3_id`,`item3_qty`,`item4`,`item4_id`,`item4_qty`,`item5`,`item5_id`,`item5_qty`,`item6`,`item6_id`,`item6_qty`,`item7`,`item7_id`,`item7_qty`)
                        VALUES (:craft_id,:metier,:xp,:item0,:item0_id,:item0_qty,:item1,:item1_id,:item1_qty,:item2,:item2_id,:item2_qty,:item3,:item3_id,:item3_qty,:item4,:item4_id,:item4_qty,:item5,:item5_id,:item5_qty,:item6,:item6_id,:item6_qty,:item7,:item7_id,:item7_qty);";
        }else{$craft="";}
        if (isset($this->{"stats_id"})){
            $columns = self::$stats_column;
            $bind = self::$stats_column_bind;
            $stats = "REPLACE INTO `".self::version."stats` (`stats_id`".$columns.")
                        VALUES (:stats_id".$bind.");";
        }else{$stats="";}
        if (isset($this->{"rides_id"})){
            $rides = "REPLACE INTO `".self::version."rides` (`rides_id`,`Generation`,`Nombre_de_pods`,`Temps_de_gestation`,`Maturite`,`Energie`,`Vitesse`,`Vitesse_de_deplacement`,`Taux_apprentisage`,`Capturable`)
                        VALUES (:rides_id,:Generation,:Nombre_de_pods,:Temps_de_gestation,:Maturite,:Energie,:Vitesse,:Vitesse_de_deplacement,:Taux_apprentisage,:Capturable);";
        }else{$rides="";}
        $query = "  BEGIN;
                    REPLACE INTO `".self::version."main` (`main_id`,`dofus_url`,`global_type`,`name`,`level`,`img_id`,`type`,`description`,`set_name`,`set_id`,`set_url`,`craftable`)
                        VALUES(:main_id,:dofus_url,:global_type,:name,:level,:img_id,:type,:description,:set_name,:set_id,:set_url,:craftable);
                    $stats
                    $craft
                    $armes
                    $rides
                    COMMIT;";
        return sqldb::safesql($query,$this->associative_array());
    }

    protected function display_node($DOMElement){
        if ($DOMElement!==NULL){
            echo $DOMElement->ownerDocument->saveHTML($DOMElement);
        }
    }

    public function sql_loader($query){

    }

    protected function associative_array(){
        return get_object_vars($this);
    }

    public function print_pars(){
        foreach ($this->associative_array() as $key=>$element){
            echo $key.' : '.$element.';<br/>';
        }
    }

    public function get_craft_items($array){
        for($i=0;$i<8;$i++){
            if (isset($array["item$i"])){
                $this->{"item$i"} = $array["item$i"];
                $this->{"item".$i."_qty"} = $array["item".$i."_qty"];
            }
        }
    }

    public function recipe(){
        $recipe = "<div class='recipe'>";
        for ($i=0;$i<8;$i++){
            if (isset($this->{"item".$i})){
                $img = new img($this->{"item" . $i});
                $recipe = $recipe . $img->image_n_qty($this->{"item" . $i . "_qty"},50);
            }
        }
        $recipe = $recipe."</div>";
        return $recipe;
    }
}