<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
if (isset($_POST['class'])){
    $spells_query = "SELECT DISTINCT `spell_name`,`description`,`Par_Tour`,`Par_Tour_Par_Joueur` FROM `".$version."spells` WHERE `class_name`=:class AND `spell_level`=1 ORDER BY `char_level` ASC";
    $array['class']=htmlspecialchars($_POST['class']);
    $spells = sqldb::safesql($spells_query,$array);
    $imgs = "";
    foreach ($spells as $spell_array){
        $spell = iconv("utf-8", "UTF-8//IGNORE", $spell_array['spell_name']);
        $desc = iconv("utf-8", "UTF-8//IGNORE", $spell_array['description']);
        $limit = max($spell_array['Par_Tour'],$spell_array['Par_Tour_Par_Joueur'],1);
        $imgs = $imgs.'<a href="#"><img class="img-rounded" src="/images/icons/spells/'.$spell.'.png" alt="'.$spell.'"  data-limit="'.$limit.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'."$spell : $desc".'"/></a>';
    }
    json($imgs);
}