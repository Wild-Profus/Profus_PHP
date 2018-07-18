<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
if (isset($_POST['spell'])){
    isset($_POST['level'])? $array['lvl'] = intval(htmlspecialchars($_POST['level'])) : $array['lvl'] = 6 ;
    $spell_query = "SELECT * FROM `".$version."spells` WHERE `spell_name`=:spell AND `spell_level`=:lvl ORDER BY `spell_level` ASC";
    $array['spell']=htmlspecialchars($_POST['spell']);
    $spell = sqldb::safesql($spell_query,$array);
    $name = $spell[0]['spell_name'];
    $desc = $spell[0]['description'];
    if (stripos($desc,'augmente les dommages')){
        $boost = "
        <div class='form-group'>
                <select class='form-control' style='width:60px'>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6' selected='selected'>6</option>
                </select>
            </div>";
    }else{$boost="";}
    $html = "
            <form class='form-inline text-center'>
            <div style='display: flex;justify-content:space-around;align-items:center'>
                <div style='order: 1'>
                    <h4 class='text-center'>$name</h4>
                </div>                
                <div style='order: 2;margin-bottom: 0.2em;'>
                    <i class='btn btn-xs btn-danger glyphicon glyphicon-remove'></i>
                </div>
             </div>
            <div class='form-group'>
                <img class='img-rounded' src=\"/images/icons/spells/$name.png\" alt='$name' data-toggle='tooltip' data-placement='top' data-original-title=\"$name : ".$desc."\"/>
                <select class='form-control' style='width:60px'>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6' selected='selected'>6</option>
                </select>
            </div>
            $boost
            </form>
            ";
    $img = new img($name);
    foreach ($spell as $row=>$array){
        foreach ($array as $stat=>$value){
            if (is_null($value)){
                unset($spell[$row][$stat]);
            }elseif (is_numeric($value)){
                $spell[$row][$stat]=intval($value);
            }
        }
    }
    json($html);
}