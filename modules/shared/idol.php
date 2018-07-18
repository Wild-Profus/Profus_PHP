<?php
class idol{
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

    public function dom_loader($url){
        libxml_use_internal_errors(true);
        $this->dofus_url = $url;
        $this->idol_id = intval(explode('-',$this->dofus_url)[0]);
        $this->dofus_url = urlencode($this->dofus_url);
        //Domdoc
        $domdoc = new DOMDocument();
        $domdoc->preserveWhiteSpace = false;
        $s =  0;
        //Page loading
        do{
            $loadpage = file_get_contents("http://www.dofus.com/fr/mmorpg/encyclopedie/idoles/$url");
            if($s>9){
                $callers = debug_backtrace();
                $error_log = "=====================================================================================================================================================".PHP_EOL
                    .date('Y-m-d H:i:s').PHP_EOL;
                foreach ($callers as $level=>$caller){
                    $error_log = $error_log."Function {$caller['function']} (class {$caller['class']}) in file {$caller['file']} (line {$caller['line']})".PHP_EOL;

                }
                file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/brisage.log",$error_log, FILE_APPEND | LOCK_EX);
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
        $img_url = self::$main_xpath->query(".//img/@src", self::$main_content)[0]->textContent;
        //Level
        $this->level = intval(str_replace("Niveau : ","",self::$main_xpath->query(".//div[contains(@class,'ak-encyclo-detail-level')]", self::$main_panel)[0]->textContent));
        //Description
        $this->description = trim(self::$main_xpath->query(".//div[text()[contains(.,'Description')]]/following-sibling::div", self::$main_panel)[0]->textContent);
        //Bonus
        $this->bonus = trim(self::$main_xpath->query(".//div[text()[contains(.,'Bonus')]]/following-sibling::div//*[contains(@class,'ak-title-info')]", self::$main_panel)[0]->textContent);
        //Sort
        $this->sort = trim(self::$main_xpath->query(".//div[text()[contains(.,'Sort')]]/following-sibling::div", self::$main_panel)[0]->textContent);
        $needle = explode(' ',$this->name)[0];
        $this->class = $needle;
        $group = ["Binar","Boble","Domo","Hoskar","Hulhu","Hoskar","Kyoub","Nahualt","Pikmi","Pého","Pétunia","Teleb","Ultram","Vaude"];
        in_array($needle, $group)?$g=true:$g=false;
        $this->create_image($this->name,$this->level,$g);
        $this->save();
        $this->column();
    }

    public function save(){
        $query = "INSERT INTO `".self::version."idols` (`idol_id`,`dofus_url`,`name`,`class`,`level`,`description`,`bonus`,`sort`)
                    VALUES(:idol_id,:dofus_url,:name,:class,:level,:description,:bonus,:sort);";
        return sqldb::safesql($query,$this->associative_array());
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

    protected function associative_array(){
        return get_object_vars($this);
    }

    protected function column(){
        sqldb::safesql("ALTER TABLE `".self::version."idols` ADD `".$this->name."` DOUBLE DEFAULT 1;",false,false);
    }

    public function update(){
        $ratio = [
            "Aroumb" => [
                "Muta" => 1.82
            ],
            "Bihilète" => [
                "Bihilète magistrale" => 0.71,
                "Bihilète majeure" => 0.74,
                "Bihilète mineure" => 0.9,
                "Dynamo" => 1.17,
                "Dynamo magistrale" => 1.24,
                "Dynamo majeure" => 1.21,
                "Dynamo mineure" => 1.11,
                "Hulhu" => 1.14,
                "Hulhu magistrale" => 1.16,
                "Hulhu majeure" => 1.15,
                "Hulhu mineure" =>1.13,
                "Leukide" => 1.14,
                "Nékinéko" => 1.14,
                "Pého" => 1.13,
                "Pého magistrale" => 1.15,
                "Pého majeure" => 1.14,
                "Pého mineure" => 1.11
            ],
            "Bihilète magistrale" => [
                "Bihilète majeure" => 0.65,
                "Bihilète mineure" => 0.9,
                "Dynamo" => 1.11,
                "Dynamo magistrale" => 1.17,
                "Dynamo majeure" => 1.14,
                "Dynamo mineure" => 1.06,
                "Hulhu" => 1.09,
                "Hulhu magistrale" => 1.11,
                "Hulhu majeure" => 1.1,
                "Hulhu mineure" =>1.07,
                "Leukide" => 1.09,
                "Nékinéko" => 1.09,
                "Pého" => 1.08,
                "Pého magistrale" => 1.1,
                "Pého majeure" => 1.09,
                "Pého mineure" => 1.06
            ],
            "Bihilète majeure" => [
                "Bihilète mineure" => 0.9,
                "Dynamo" => 1.13,
                "Dynamo magistrale" => 1.2,
                "Dynamo majeure" => 1.17,
                "Dynamo mineure" => 1.08,
                "Hulhu" => 1.11,
                "Hulhu magistrale" => 1.13,
                "Hulhu majeure" => 1.12,
                "Hulhu mineure" =>1.09,
                "Leukide" => 1.11,
                "Nékinéko" => 1.11,
                "Pého" => 1.1,
                "Pého magistrale" => 1.12,
                "Pého majeure" => 1.12,
                "Pého mineure" => 1.08
            ],
            "Bihilète mineure" => [
                "Dynamo" => 1.24,
                "Dynamo magistrale" => 1.3,
                "Dynamo majeure" => 1.27,
                "Dynamo mineure" => 1.17,
                "Hulhu" => 1.21,
                "Hulhu magistrale" => 1.21,
                "Hulhu majeure" => 1.21,
                "Hulhu mineure" =>1.22,
                "Leukide" => 1.18,
                "Nékinéko" => 1.18,
                "Pého" => 1.18,
                "Pého magistrale" => 1.19,
                "Pého majeure" => 1.18,
                "Pého mineure" => 1.17
            ],
            "Binar" => [
                "Muta" => 1.36
            ],
            "Boble" => [
                "Boble magistrale" => 0.8,
                "Boble majeure" => 0.72,
                "Boble mineure" => 0.9,
                "Hulhu" => 1.29,
                "Hulhu magistrale" => 1.26,
                "Hulhu majeure" => 1.27,
                "Hulhu mineure" => 1.33,
                "Nahuatl" => 1.2
            ],
            "Boble magistrale" => [
                "Boble majeure" => 0.74,
                "Boble mineure" => 0.9,
                "Hulhu" => 1.26,
                "Hulhu magistrale" => 1.27,
                "Hulhu majeure" => 1.28,
                "Hulhu mineure" => 1.27,
                "Nahuatl" => 1.5
            ],
            "Boble majeure" => [
                "Boble mineure" => 0.9,
                "Hulhu" => 1.27,
                "Hulhu magistrale" => 1.25,
                "Hulhu majeure" => 1.26,
                "Hulhu mineure" => 1.29,
                "Nahuatl" => 1.29
            ],
            "Boble mineure" => [
                "Hulhu" => 1.33,
                "Hulhu magistrale" => 1.27,
                "Hulhu majeure" => 1.29,
                "Hulhu mineure" => 1.43,
                "Nahuatl" => 1.2
            ],
            "Butor" => [
                "Butor magistrale" => 0.75,
                "Butor majeure" => 0.7,
                "Butor mineure" => 0.83,
                "Dynamo" => 1.22,
                "Dynamo magistrale" => 1.29,
                "Dynamo majeure" => 1.26,
                "Dynamo mineure" => 1.15,
                "Horize" => 0.78,
                "Horize magistrale" => 0.86,
                "Horize majeure" => 0.83,
                "Horize mineure" => 0.69,
                "Hulhu" => 1.2,
                "Hulhu magistrale" => 1.2,
                "Hulhu majeure" => 1.2,
                "Hulhu mineure" => 1.2,
                "Kyoub" => 1.17,
                "Kyoub magistrale" => 1.25,
                "Kyoub majeure" => 1.25,
                "Kyoub mineure" => 1.1,
                "Leukide" => 1.17,
                "Muta" => 1.2,
                "Nékinéko" => 1.17,
                "Ougah" => 0.86,
                "Pého" => 1.17,
                "Pého magistrale" => 1.18,
                "Pého majeure" => 1.17,
                "Pého mineure" => 1.15
            ],
            "Butor magistrale" => [
                "Butor majeure" => 0.71,
                "Butor mineure" => 0.9,
                "Dynamo" => 1.15,
                "Dynamo magistrale" => 1.22,
                "Dynamo majeure" => 1.19,
                "Dynamo mineure" => 1.1,
                "Horize" => 0.69,
                "Horize magistrale" => 0.78,
                "Horize majeure" => 0.74,
                "Horize mineure" => 0.62,
                "Hulhu" => 1.13,
                "Hulhu magistrale" => 1.15,
                "Hulhu majeure" => 1.14,
                "Hulhu mineure" => 1.11,
                "Kyoub" => 1.1,
                "Kyoub magistrale" => 1.19,
                "Kyoub majeure" => 1.17,
                "Kyoub mineure" => 1.06,
                "Leukide" => 1.13,
                "Muta" => 1.29,
                "Nékinéko" => 1.13,
                "Ougah" => 0.91,
                "Pého" => 1.12,
                "Pého magistrale" => 1.14,
                "Pého majeure" => 1.13,
                "Pého mineure" => 1.11
            ],
            "Butor majeure" => [
                "Butor mineure" => 0.88,
                "Dynamo" => 1.18,
                "Dynamo magistrale" => 1.25,
                "Dynamo majeure" => 1.22,
                "Dynamo mineure" => 1.12,
                "Horize" => 0.73,
                "Horize magistrale" => 0.81,
                "Horize majeure" => 0.78,
                "Horize mineure" => 0.65,
                "Hulhu" => 1.16,
                "Hulhu magistrale" => 1.17,
                "Hulhu majeure" => 1.17,
                "Hulhu mineure" => 1.14,
                "Kyoub" => 1.13,
                "Kyoub magistrale" => 1.21,
                "Kyoub majeure" => 1.2,
                "Kyoub mineure" => 1.07,
                "Leukide" => 1.15,
                "Muta" => 1.25,
                "Nékinéko" => 1.15,
                "Ougah" => 0.89,
                "Pého" => 1.14,
                "Pého magistrale" => 1.16,
                "Pého majeure" => 1.15,
                "Pého mineure" => 1.12
            ],
            "Butor mineure" => [
                "Dynamo" => 1.29,
                "Dynamo magistrale" => 1.33,
                "Dynamo majeure" => 1.32,
                "Dynamo mineure" => 1.29,
                "Horize" => 0.86,
                "Horize magistrale" => 0.92,
                "Horize majeure" => 0.89,
                "Horize mineure" => 0.78,
                "Hulhu" => 1.27,
                "Hulhu magistrale" => 1.24,
                "Hulhu majeure" => 1.25,
                "Hulhu mineure" => 1.33,
                "Kyoub" => 1.27,
                "Kyoub magistrale" => 1.3,
                "Kyoub majeure" => 1.33,
                "Kyoub mineure" => 1.17,
                "Leukide" => 1.21,
                "Muta" => 1.13,
                "Nékinéko" => 1.21,
                "Ougah" => 0.8,
                "Pého" => 1.21,
                "Pého magistrale" => 1.21,
                "Pého majeure" => 1.21,
                "Pého mineure" => 1.22
            ],
            "Corrode" => [
                "Nyoro" => 1.24,
                "Nyoro magistrale" => 1.22,
                "Nyoro majeure" => 1.23,
                "Nyoro mineure" => 1.25,
                "Pénitent" => 0.69
            ],
            "Dagob" => [
                "Dagob magistrale" => 0.79,
                "Dagob majeure" => 0.73,
                "Dagob mineure" => 1,
                "Dynamo" => 1.22,
                "Dynamo magistrale" => 1.29,
                "Dynamo majeure" => 1.26,
                "Dynamo mineure" => 1.15,
                "Hulhu" => 1.2,
                "Hulhu magistrale" => 1.2,
                "Hulhu majeure" => 1.2,
                "Hulhu mineure" => 1.2,
                "Leukide" => 1.17,
                "Nékinéko" => 1.17,
                "Pého" => 1.17,
                "Pého magistrale" => 1.18,
                "Pého majeure" => 1.17,
                "Pého mineure" => 1.15
            ],
            "Dagob magistrale" => [
                "Dagob majeure" => 0.71,
                "Dagob mineure" => 1,
                "Dynamo" => 1.13,
                "Dynamo magistrale" => 1.2,
                "Dynamo majeure" => 1.17,
                "Dynamo mineure" => 1.08,
                "Hulhu" => 1.11,
                "Hulhu magistrale" => 1.14,
                "Hulhu majeure" => 1.13,
                "Hulhu mineure" => 1.09,
                "Leukide" => 1.11,
                "Nékinéko" => 1.11,
                "Pého" => 1.11,
                "Pého magistrale" => 1.13,
                "Pého majeure" => 1.11,
                "Pého mineure" => 1.08
            ],
            "Dagob majeure" => [
                "Dagob mineure" => 1,
                "Dynamo" => 1.17,
                "Dynamo magistrale" => 1.24,
                "Dynamo majeure" => 1.21,
                "Dynamo mineure" => 1.11,
                "Hulhu" => 1.14,
                "Hulhu magistrale" => 1.16,
                "Hulhu majeure" => 1.15,
                "Hulhu mineure" => 1.13,
                "Leukide" => 1.14,
                "Nékinéko" => 1.14,
                "Pého" => 1.13,
                "Pého magistrale" => 1.15,
                "Pého majeure" => 1.14,
                "Pého mineure" => 1.11
            ],
            "Dagob mineure" => [
                "Dynamo" => 1.33,
                "Dynamo magistrale" => 1.36,
                "Dynamo majeure" => 1.35,
                "Dynamo mineure" => 1.29,
                "Hulhu" => 1.33,
                "Hulhu magistrale" => 1.26,
                "Hulhu majeure" => 1.29,
                "Hulhu mineure" => 1.5,
                "Leukide" => 1.24,
                "Nékinéko" => 1.24,
                "Pého" => 1.25,
                "Pého magistrale" => 1.23,
                "Pého majeure" => 1.24,
                "Pého mineure" => 1.29
            ],
            "Dynamo" => [
                "Dynamo magistrale" => 0.9,
                "Dynamo majeure" => 0.9,
                "Dynamo mineure" => 0.9,
                "Horize" => 1.2,
                "Horize magistrale" => 1.13,
                "Horize majeure" => 1.16,
                "Horize mineure" => 1.27,
                "Hulhu" => 0.82,
                "Hulhu magistrale" => 0.89,
                "Hulhu majeure" => 0.86,
                "Hulhu mineure" => 0.9,
                "Leukide" => 0.8,
                "Nékinéko" => 0.7,
                "Ougah" => 1.19,
                "Pénitent" => 1.19,
                "Sak" => 1.2,
                "Sak magistrale" => 1.13,
                "Sak majeure" => 1.16,
                "Sak mineure" => 1.27,
                "Teleb" => 1.22,
                "Teleb magistrale" => 1.15,
                "Teleb majeure" => 1.18,
                "Teleb mineure" => 1.2,
                "Ultram" => 1.29,
                "Yoche" => 1.1,
                "Yoche magistrale" => 1.13,
                "Yoche majeure" => 1.12,
                "Yoche mineure" => 1.07
            ],
            "Dynamo magistrale" => [
                "Dynamo majeure" => 0.9,
                "Dynamo mineure" => 0.9,
                "Horize" => 1.27,
                "Horize magistrale" => 1.2,
                "Horize majeure" => 1.23,
                "Horize mineure" => 1.32,
                "Hulhu" => 0.74,
                "Hulhu magistrale" => 0.81,
                "Hulhu majeure" => 0.78,
                "Hulhu mineure" => 0.9,
                "Leukide" => 0.8,
                "Nékinéko" => 0.7,
                "Ougah" => 1.12,
                "Pénitent" => 1.19,
                "Sak" => 1.27,
                "Sak magistrale" => 1.2,
                "Sak majeure" => 1.23,
                "Sak mineure" => 1.32,
                "Teleb" => 1.21,
                "Teleb magistrale" => 1.17,
                "Teleb majeure" => 1.19,
                "Teleb mineure" => 1.2,
                "Ultram" => 1.25,
                "Yoche" => 1.07,
                "Yoche magistrale" => 1.1,
                "Yoche majeure" => 1.08,
                "Yoche mineure" => 1.04
            ],
            "Dynamo majeure" => [
                "Dynamo mineure" => 0.9,
                "Horize" => 1.24,
                "Horize magistrale" => 1.17,
                "Horize majeure" => 1.2,
                "Horize mineure" => 1.3,
                "Hulhu" => 0.77,
                "Hulhu magistrale" => 0.84,
                "Hulhu majeure" => 0.81,
                "Hulhu mineure" => 0.9,
                "Leukide" => 0.8,
                "Nékinéko" => 0.7,
                "Ougah" => 1.14,
                "Pénitent" => 1.19,
                "Sak" => 1.24,
                "Sak magistrale" => 1.17,
                "Sak majeure" => 1.2,
                "Sak mineure" => 1.3,
                "Teleb" => 1.22,
                "Teleb magistrale" => 1.16,
                "Teleb majeure" => 1.19,
                "Teleb mineure" => 1.2,
                "Ultram" => 1.26,
                "Yoche" => 1.08,
                "Yoche magistrale" => 1.11,
                "Yoche majeure" => 1.1,
                "Yoche mineure" => 1.05
            ],
            "Dynamo mineure" => [
                "Horize" => 1.13,
                "Horize magistrale" => 1.09,
                "Horize majeure" => 1.1,
                "Horize mineure" => 1.2,
                "Hulhu" => 0.92,
                "Hulhu magistrale" => 0.95,
                "Hulhu majeure" => 0.94,
                "Hulhu mineure" => 0.9,
                "Leukide" => 0.8,
                "Nékinéko" => 0.7,
                "Ougah" => 1.27,
                "Pénitent" => 1.18,
                "Sak" => 1.13,
                "Sak magistrale" => 1.08,
                "Sak majeure" => 1.1,
                "Sak mineure" => 1.2,
                "Teleb" => 1.23,
                "Teleb magistrale" => 1.14,
                "Teleb majeure" => 1.18,
                "Teleb mineure" => 1.33,
                "Ultram" => 1.2,
                "Yoche" => 1.13,
                "Yoche magistrale" => 1.15,
                "Yoche majeure" => 1.14,
                "Yoche mineure" => 1.11
            ],
            "Horize" => [
                "Horize magistrale" => 0.77,
                "Horize majeure" => 0.72,
                "Horize mineure" => 0.8,
                "Hulhu" => 1.18,
                "Hulhu magistrale" => 1.19,
                "Hulhu majeure" => 1.18,
                "Hulhu mineure" => 1.17,
                "Leukide" => 1.16,
                "Muta" => 0.82,
                "Nékinéko" => 1.16,
                "Ougah" => 1.13,
                "Pého" => 1.15,
                "Pého magistrale" => 1.17,
                "Pého majeure" => 1.16,
                "Pého mineure" => 1.13
            ],
            "Horize magistrale" => [
                "Horize majeure" => 0.69,
                "Horize mineure" => 0.88,
                "Hulhu" => 1.11,
                "Hulhu magistrale" => 1.14,
                "Hulhu majeure" => 1.13,
                "Hulhu mineure" => 1.09,
                "Leukide" => 1.11,
                "Muta" => 0.75,
                "Nékinéko" => 1.11,
                "Ougah" => 1.08,
                "Pého" => 1.1,
                "Pého magistrale" => 1.13,
                "Pého majeure" => 1.11,
                "Pého mineure" => 1.08
            ],
            "Horize majeure" => [
                "Horize mineure" => 0.85,
                "Hulhu" => 1.14,
                "Hulhu magistrale" => 1.16,
                "Hulhu majeure" => 1.15,
                "Hulhu mineure" => 1.12,
                "Leukide" => 1.13,
                "Muta" => 0.78,
                "Nékinéko" => 1.13,
                "Ougah" => 1.1,
                "Pého" => 1.12,
                "Pého magistrale" => 1.14,
                "Pého majeure" => 1.13,
                "Pého mineure" => 1.1
            ],
            "Horize mineure" => [
                "Hulhu" => 1.25,
                "Hulhu magistrale" => 1.23,
                "Hulhu majeure" => 1.24,
                "Hulhu mineure" => 1.29,
                "Leukide" => 1.2,
                "Muta" => 0.88,
                "Nékinéko" => 1.2,
                "Ougah" => 1.18,
                "Pého" => 1.2,
                "Pého magistrale" => 1.2,
                "Pého majeure" => 1.2,
                "Pého mineure" => 1.2
            ],
            "Hoskar" => [
                "Muta" => 1.5
            ],
            "Hulhu" => [
                "Hulhu magistrale" => 0.92,
                "Hulhu majeure" => 0.89,
                "Hulhu mineure" => 1,
                "Nékinéko" => 0.91,
                "Pénitent" => 1.15,
                "Sak" => 1.18,
                "Sak magistrale" => 1.11,
                "Sak majeure" => 1.14,
                "Sak mineure" => 1.25,
                "Teleb" => 1.27,
                "Teleb magistrale" => 1.17,
                "Teleb majeure" => 1.21,
                "Teleb mineure" => 1.36,
                "Ultram" => 1.18,
                "Yoche" => 1.12,
                "Yoche magistrale" => 1.14,
                "Yoche majeure" => 1.13,
                "Yoche mineure" => 1.09
            ],
            "Hulhu magistrale" => [
                "Hulhu majeure" => 0.83,
                "Hulhu mineure" => 1,
                "Nékinéko" => 0.88,
                "Pénitent" => 1.09,
                "Sak" => 1.19,
                "Sak magistrale" => 1.14,
                "Sak majeure" => 1.16,
                "Sak mineure" => 1.23,
                "Teleb" => 1.24,
                "Teleb magistrale" => 1.18,
                "Teleb majeure" => 1.21,
                "Teleb mineure" => 1.29,
                "Ultram" => 1.11,
                "Yoche" => 1.07,
                "Yoche magistrale" => 1.1,
                "Yoche majeure" => 1.09,
                "Yoche mineure" => 1.05
            ],
            "Hulhu majeure" => [
                "Hulhu mineure" => 1,
                "Nékinéko" => 0.89,
                "Pénitent" => 1.11,
                "Sak" => 1.18,
                "Sak magistrale" => 1.13,
                "Sak majeure" => 1.15,
                "Sak mineure" => 1.24,
                "Teleb" => 1.25,
                "Teleb magistrale" => 1.18,
                "Teleb majeure" => 1.21,
                "Teleb mineure" => 1.31,
                "Ultram" => 1.05,
                "Yoche" => 1.09,
                "Yoche magistrale" => 1.12,
                "Yoche majeure" => 1.11,
                "Yoche mineure" => 1.06
            ],
            "Hulhu mineure" => [
                "Nékinéko" => 0.94,
                "Pénitent" => 1.25,
                "Sak" => 1.17,
                "Sak magistrale" => 1.09,
                "Sak majeure" => 1.12,
                "Sak mineure" => 1.29,
                "Teleb" => 1.3,
                "Teleb magistrale" => 1.17,
                "Teleb majeure" => 1.21,
                "Teleb mineure" => 1.5,
                "Ultram" => 1.25,
                "Yoche" => 1.17,
                "Yoche magistrale" => 1.17,
                "Yoche majeure" => 1.17,
                "Yoche mineure" => 1.17
            ],
            "Kyoub" => [
                "Kyoub magistrale" => 0.85,
                "Kyoub majeure" => 0.83,
                "Kyoub mineure" => 0.95,
                "Teleb" => 1.33,
                "Teleb magistrale" => 1.2,
                "Teleb majeure" => 1.25,
                "Teleb mineure" => 1.5,
                "Ultram" => 1.29,
                "Yoche" => 0.86,
                "Yoche magistrale" => 0.77,
                "Yoche majeure" => 0.8,
                "Yoche mineure" => 1
            ],
            "Kyoub magistrale" => [
                "Kyoub majeure" => 0.75,
                "Kyoub mineure" => 0.95,
                "Teleb" => 1.25,
                "Teleb magistrale" => 1.19,
                "Teleb majeure" => 1.21,
                "Teleb mineure" => 1.3,
                "Ultram" => 1.15,
                "Yoche" => 0.92,
                "Yoche magistrale" => 0.84,
                "Yoche majeure" => 0.88,
                "Yoche mineure" => 1
            ],
            "Kyoub majeure" => [
                "Kyoub mineure" => 0.95,
                "Teleb" => 1.31,
                "Teleb magistrale" => 1.21,
                "Teleb majeure" => 1.25,
                "Teleb mineure" => 1.42,
                "Ultram" => 1.22,
                "Yoche" => 0.89,
                "Yoche magistrale" => 0.8,
                "Yoche majeure" => 0.83,
                "Yoche mineure" => 1
            ],
            "Kyoub mineure" => [
                "Teleb" => 1.3,
                "Teleb magistrale" => 1.17,
                "Teleb majeure" => 1.21,
                "Teleb mineure" => 1.5,
                "Ultram" => 1.33,
                "Yoche" => 0.95,
                "Yoche magistrale" => 0.95,
                "Yoche majeure" => 0.95,
                "Yoche mineure" => 1
            ],
            "Leukide" => [
                "Muta" => 1.26,
                "Nékinéko" => 0.9,
                "Proxima" => 1.1,
                "Sak" => 1.16,
                "Sak magistrale" => 1.11,
                "Sak majeure" => 1.13,
                "Sak mineure" => 1.2
            ],
            "Muta" => [
                "Ougah" => 1.67,
                "Pikmi" => 1.3,
                "Pikmi magistrale" => 1.21,
                "Pikmi majeure" => 1.25,
                "Pikmi mineure" => 1.38,
                "Proxima" => 1.44,
                "Pénitent" => 1.33,
                "Ultram" => 1.82
            ],
            "Nyoro" => [
                "Nyoro magistrale" => 0.83,
                "Nyoro majeure" => 0.8,
                "Nyoro mineure" => 0.9,
                "Pénitent" => 1.25
            ],
            "Nyoro magistrale" => [
                "Nyoro majeure" => 0.86,
                "Nyoro mineure" => 0.9,
                "Pénitent" => 1.23
            ],
            "Nyoro majeure" => [
                "Nyoro mineure" => 0.9,
                "Pénitent" => 1.24
            ],
            "Nyoro mineure" => [
                "Pénitent" => 1.27
            ],
            "Nékinéko" => [
                "Pénitent" => 1.19,
                "Sak" => 1.16,
                "Sak magistrale" => 1.11,
                "Sak majeure" => 1.13,
                "Sak mineure" => 1.2,
                "Teleb" => 1.13,
                "Teleb magistrale" => 1.16,
                "Teleb majeure" => 1.11,
                "Teleb mineure" => 1.11,
                "Ultram" => 1.2,
                "Yoche" => 1.08,
                "Yoche magistrale" => 1.11,
                "Yoche majeure" => 1.1,
                "Yoche mineure" => 1.05
            ],
            "Ougah" => [
                "Proxima" => 0.67
            ],
            "Pého" => [
                "Pénitent" => 1.13,
                "Sak" => 1.15,
                "Sak magistrale" => 1.1,
                "Sak majeure" => 1.12,
                "Sak mineure" => 1.2,
                "Ultram" => 1.2
            ],
            "Pého magistrale" => [
                "Pénitent" => 1.13,
                "Sak" => 1.17,
                "Sak magistrale" => 1.13,
                "Sak majeure" => 1.14,
                "Sak mineure" => 1.2,
                "Ultram" => 1.2
            ],
            "Pého majeure" => [
                "Pénitent" => 1.14,
                "Sak" => 1.16,
                "Sak magistrale" => 1.1,
                "Sak majeure" => 1.13,
                "Sak mineure" => 1.2,
                "Ultram" => 1.2
            ],
            "Pého mineure" => [
                "Pénitent" => 1.18,
                "Sak" => 1.13,
                "Sak magistrale" => 1.08,
                "Sak majeure" => 1.1,
                "Sak mineure" => 1.2,
                "Ultram" => 1.2
            ],
            "Sak" => [
                "Sak magistrale" => 0.77,
                "Sak majeure" => 0.72,
                "Sak mineure" => 0.9
            ],
            "Sak magistrale" => [
                "Sak majeure" => 0.71,
                "Sak mineure" => 0.9
            ],
            "Sak majeure" => [
                "Sak mineure" => 0.9
            ],
            "Yoche" => [
                "Yoche magistrale" => 0.81,
                "Yoche majeure" => 0.73,
                "Yoche mineure" => 0.93
            ],
            "Yoche magistrale" => [
                "Yoche majeure" => 0.74,
                "Yoche mineure" => 0.92
            ],
            "Yoche majeure" => [
                "Yoche mineure" => 0.9
            ]
        ];
        foreach ($ratio as $idol1=>$value){
            foreach ($value as $idol2=>$syn){
                $array = ['idol'=>$idol1,'syn'=>$syn];
                sqldb::safesql("UPDATE `".self::version."idols` SET `$idol2` = :syn WHERE `name` = :idol;",$array,false,false);
                $array = ['idol'=>$idol2,'syn'=>$syn];
                sqldb::safesql("UPDATE `".self::version."idols` SET `$idol1` = :syn WHERE `name` = :idol;",$array,false,false);
            }
        }
    }

    public function print_pars(){
        foreach ($this->associative_array() as $key=>$element){
            echo $key.' : '.$element.';<br/>';
        }
    }

    protected function create_image($filename,$lvl,$group=false){
        /********************   Création d'une image vide de base *************************/
        $width = 180;
        $height = 265;
        $im = imagecreatetruecolor($width,$height);
        imagealphablending( $im, true );
        imagesavealpha( $im, true );
        $trans_colour = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im, 0, 0, $trans_colour);
        /********************   On y insert l'image de l'idole *************************/
        $mini = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/any2/".$filename." 200.png");
        imagealphablending($mini, true);
        imagesavealpha($mini, true);
        //imagecopy($im,$mini,-10,0,0,0,200,200);
        $resize_factor = 0.9;
        $start = round(200*(1-$resize_factor));
        imagecopyresized ($im,$mini,$start/2-5,$start,0,0,200*$resize_factor,200*$resize_factor,200,200);
        imagedestroy($mini);
        /****************** Initialisation des paramètres d'écritures ***********************/
        $black = imagecolorallocate($im, 0, 0, 0);
        $police = $_SERVER['DOCUMENT_ROOT']."/css/"."HARLOWSI.TTF";
        $fontsize = 25;
        $heighttest = "PJGL";
        $mothaut = imagettfbbox($fontsize,0,$police,$heighttest);
        $hauteur = $mothaut[7]-$mothaut[1];
        $heighttextlimit = $height+round($hauteur/2);
        /*************************** On écrit le niveau **********************************/
        $lvl_word = imagettfbbox($fontsize,0,$police,$lvl);
        imagettftext($im,$fontsize,0,170-($lvl_word[2]-$lvl_word[0]),-10-$hauteur,$black,$police,$lvl);
        /*********************** On écrit le nom en bas et centré sur l'image  *************************/
        $fontsize = 30;
        $heighttest = "PJGL";
        $mothaut = imagettfbbox($fontsize,0,$police,$heighttest);
        $hauteur = $mothaut[7]-$mothaut[1];
        $heighttextlimit = $height+round($hauteur/2);
        $names=explode(' ',$filename);
        if (count($names)>1){
            $mot1 = imagettfbbox($fontsize,0,$police,$names[0]);
            $larheurmot1 = $mot1[2]-$mot1[0];
            $mot2 = imagettfbbox($fontsize,0,$police,$names[1]);
            $larheurmot2 = $mot2[2]-$mot2[0];
            imagettftext($im,$fontsize,0,round(($width-$larheurmot1)/2),$heighttextlimit+$hauteur,$black,$police,$names[0]);
            imagettftext($im,$fontsize,0,round(($width-$larheurmot2)/2),$heighttextlimit,$black,$police,$names[1]);
        }else{
            $mot1 = imagettfbbox($fontsize,0,$police,$names[0]);
            $larheurmot1 = $mot1[2]-$mot1[0];
            imagettftext($im,$fontsize,0,round(($width-$larheurmot1)/2),$heighttextlimit+round($hauteur*2/4),$black,$police,$names[0]);
        }
        /*********************** On réduit la taille par un facteur *************************/
        $resizefactor = 0.5;
        $newwidth = $resizefactor * $width;
        $newheight = $resizefactor * $height;
        $imf = imagecreatetruecolor($newwidth,$newheight);
        imagealphablending( $imf, true );
        imagesavealpha( $imf, true );
        $trans_colour = imagecolorallocatealpha($imf, 0, 0, 0, 127);
        imagefill($imf, 0, 0, $trans_colour);
        imagecopyresampled($imf,$im,0,0,0,0,$newwidth,$newheight,$width,$height);
        imagedestroy($im);
        /*********************** On sauvegarde l'image finale *************************/
        imagepng($imf, $_SERVER['DOCUMENT_ROOT']."/images/adols/".$filename.".png");
        imagedestroy($imf);
    }
}