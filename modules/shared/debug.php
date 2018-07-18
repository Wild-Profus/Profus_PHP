<?php
class debug{
    private $name;
    private $type;
    private $path;

    public function __construct($var,$title='debug.txt')    {
        $dir = $_SERVER['DOCUMENT_ROOT']."/logs/";
        $this->name = $title;
        $this->path = $dir.$title;
        $this->type = gettype($var);
        $this->save($this->setup($var));
    }

    private function save($text){
        file_put_contents($this->path, $text, FILE_APPEND | LOCK_EX);
    }

    private function setup($var){
        $separator = "=====================================================================";
        $head = "Variable : ".$this->name." - Date : ".date('Y-m-d H:i:s')." - Type : ".$this->type;
        $body = $this->traversable($var);
        return $separator.PHP_EOL.$head.PHP_EOL.$body.PHP_EOL;
    }

    private function traversable($var){
        if($var instanceof \Traversable) {
            $body = 'Traversable'.PHP_EOL;
            foreach ($var as $key => $value) {
                $body = $body . "[$key] : [".$this->traversable($value)."] - ".gettype($value).PHP_EOL;
            }
        }elseif (is_array($var)){
            $body = 'Array'.PHP_EOL;
            foreach ($var as $key => $value) {
                $body = $body . "[$key] : [".$this->traversable($value)."] - ".gettype($value).PHP_EOL;
            }
        }else{
            $body = "'$var'";
        }
        return $body;
    }

    public static function debug($var){
        static $t = 0;
        if ($t===0){
            file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/debug.txt", "");
        }
        $t++;
        $type = gettype($var);
        file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/debug.txt", "\nCall $t - Type : $type", FILE_APPEND | LOCK_EX);
        switch ($type){
            case 'object':
                $class = get_class($var);
                file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/debug.txt", "\n".$class, FILE_APPEND | LOCK_EX);
                switch ($class){
                    case 'DOMDocument':
                        file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/debug.txt","\n".$var->saveHTML(), FILE_APPEND | LOCK_EX);
                        break;
                    case 'DOMNodeList':
                        $temp_doc = new DOMDocument();
                        foreach($var as $n) $temp_doc->appendChild($temp_doc->importNode($n,true));
                        file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/debug.txt","\n".$temp_doc->saveHTML(), FILE_APPEND | LOCK_EX);
                        break;
                    case 'DOMElement':
                        $newdoc = new DOMDocument;
                        $node = $newdoc->importNode($var, true);
                        $newdoc->appendChild($node);
                        file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/debug.txt", "\n".$newdoc->saveHTML(), FILE_APPEND | LOCK_EX);
                        break;
                }
                break;
            case 'array':
                foreach ($var as $key=>$value){
                    if (gettype($value)=='array'){
                        self::debug($value);
                    }else{
                        file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/debug.txt", "\n[$key] : '$value';", FILE_APPEND | LOCK_EX);
                    }
                }
                break;
            case 'integer':
            case 'double':
            case 'string':
                file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/debug.txt", "\n".strval($var), FILE_APPEND | LOCK_EX);
                break;
        }
    }
}