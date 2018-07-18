<?php
class img{
    private $name;
    private $img_path;
    const dofus = "http://staticns.ankama.com/dofus/www/game/items/200/";
    const img = "/images/any2/";
    const icons = '/images/icons/';

    public function __construct($item_name){
        $this->name = iconv("utf-8", "UTF-8//IGNORE", $item_name);
        $this->img_path = $_SERVER['DOCUMENT_ROOT'].self::img;
        $this->filename = $this->img_path.$this->name;
    }

    public function id_save($img_id){
        if (!file_exists($this->filename." 200.png")) {
            $urls[200] = self::dofus."$img_id.png";
            //$urls[100] = self::dofus."$img_id.w100h100.png";
            //$urls[50] = self::dofus."$img_id.w50h50.png";
            $check = $this->url_exists($urls[200]);
            if ($check === FALSE) {
                $callers = debug_backtrace();
                $error_log = "=====================================================================================================================================================" . PHP_EOL
                    . date('Y-m-d H:i:s') . " - ITEM : " . $this->name . " - ID : " . $img_id . PHP_EOL;
                foreach ($callers as $level => $caller) {
                    $error_log = $error_log . "Function {$caller['function']} (class {$caller['class']}) in file {$caller['file']} (line {$caller['line']})" . PHP_EOL;

                }
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/img.log", $error_log, FILE_APPEND | LOCK_EX);
            } else {
                foreach ($urls as $size => $url) {
                    if (!file_exists($this->filename . " $size.png")) {
                        copy($url, $this->filename . " $size.png");
                    }
                }
            }
        }
    }

    public function url_save($url,$size=" 200"){
        $check = $this->url_exists($url);
        if ($check === FALSE) {
            $callers = debug_backtrace();
            $error_log = "=====================================================================================================================================================" . PHP_EOL
                . date('Y-m-d H:i:s') . " - ITEM : " . $this->name . " - URL : " . $url . PHP_EOL;
            foreach ($callers as $level => $caller) {
                $error_log = $error_log . "Function {$caller['function']} (class {$caller['class']}) in file {$caller['file']} (line {$caller['line']})" . PHP_EOL;

            }
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/img.log", $error_log, FILE_APPEND | LOCK_EX);
        } else {
            if (!file_exists($this->filename."$size.png")){
                copy($url,$this->filename."$size.png");
            }
        }

    }

    public function url_exists($url){
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_VERBOSE, 1);
        curl_setopt($curl_handle, CURLOPT_HEADER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Profusaplha/1.0');
        curl_exec($curl_handle);
        $header = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        curl_close($curl_handle);
        if ($header==200){
            return true;
        }else{
            return false;
        }
    }

    public function display($size){
        $name = $this->name;
        return '<img class="img-item" src="'.self::img.$name." "."200".'.png" alt="'.$name.'" title="'.$name.'" style="width:'.$size.'px;"/>';
    }

    protected static function number($number,$scale=100){
        return "<img class='img-number' src='".self::icons."numbers/$number.png' style='max-width:$scale%;'/>";
    }

    public function image_n_qty($number,$size=200){
        switch ($size){
            case 100 :
                $scale = 26;
                break;
            case 50 :
                $scale = 47;
                break;
            default :
                $scale = 100;
        }
        $img = "<div class='img-qty'>".$this->display($size).self::number($number,$scale)."</div>";
        return $img;
    }
}