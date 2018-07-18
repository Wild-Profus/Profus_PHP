<?php

include "dbId.php";

class sqldb{

    private $pdo = NULL;



    private function __construct(){

        $this->pdo =  new PDO("mysql:host=".HOST.";dbname=".DB_NAME.";charset=UTF8", USER, PWD, array(

            PDO::ATTR_EMULATE_PREPARES=>true,

            PDO::MYSQL_ATTR_DIRECT_QUERY=>true,

            PDO::ATTR_ERRMODE=>PDO::ERRMODE_SILENT//EXCEPTION//SILENT

        ));

    }



    public static function safesql($query_string,$array=false,$return=true){

        $bdd = new sqldb();

        $req = $bdd->pdo->prepare($query_string, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $array ? $success = $req->execute($array) : $success = $req->execute();

        if ($success){

            $return ? $results = $req->fetchAll(PDO::FETCH_ASSOC) : $results = true;

        }else{

            $results = false;

            $error = $req->errorInfo();

            $callers = debug_backtrace();

            $error_log = "=====================================================================================================================================================".PHP_EOL

                         .date('Y-m-d H:i:s')." - SQL STATE : ".$error[0]." - ERROR CODE : ".$error[1]." - ERROR MSG : ".$error[2].PHP_EOL."SQL QUERY : $query_string".PHP_EOL;

            if($array!==false){

                $array_log="";

                foreach($array as $index=>$value){

                    $array_log = $array_log."[$index]=$value";

                }

                $error_log = $error_log.$array_log.PHP_EOL;

            }

            foreach ($callers as $level=>$caller){

                    $error_log = $error_log."Function {$caller['function']} (class {$caller['class']}) in file {$caller['file']} (line {$caller['line']})".PHP_EOL;



            }

            file_put_contents($_SERVER['DOCUMENT_ROOT']."/logs/sql.log",$error_log, FILE_APPEND | LOCK_EX);

        }

        $bdd=null;

        $req=null;

        return $results;

    }



    public static function table_exist($table){

        $exist = count(sqldb::safesql("SELECT * FROM information_schema.tables WHERE table_schema = '".self::db_name."' AND table_name = '$table' LIMIT 1"));

        if ($exist > 0) {return true;}else{return false;}

    }



    public static function add_column($column,$table,$type="VARCHAR(255)"){

        $query = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'profusovlw2459' AND TABLE_NAME = '$table' AND COLUMN_NAME = '$column'";

        if(count(sqldb::safesql($query,false,true))==0){

            sqldb::safesql("ALTER TABLE `$table` ADD `$column` $type DEFAULT NULL",false,false);

        };

    }



    public static function safesql_verbose($query,$array=false){

        $sql = strval($query);

        if($array!==false){

            foreach ($array as $key=>$value){

                if(stripos($key,':')!==false){

                    $sql = str_replace($key,$value,$sql);

                }else{

                    $sql = str_replace(":".$key.",","".$value.",",$sql);

                    $sql = str_replace(":".$key.")","".$value.")",$sql);

                }

                unset($array[$key]);

            }

        }

        return (["Requete SQL : ".$sql.PHP_EOL,$array]);

    }

}