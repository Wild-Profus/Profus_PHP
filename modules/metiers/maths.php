<?php
function xp_to_lvl($base_xp){
    $lvl = intval(floor((sqrt(1+$base_xp*4/10)+1)/2));
    if ($lvl>200){
        $lvl = 200;
    }
    return $lvl;
}

function lvl_to_xp($lvl){
    return $lvl*($lvl-1)*10;
}

function craft_xp($craft_lvl, $craft_base_xp, $skill_lvl, $bonus=1){
    if ($skill_lvl>$craft_lvl){
        if (abs($skill_lvl-$craft_lvl)>60){
            $craft_xp = 0;
        }else{
            $craft_xp = floor(($craft_base_xp/(1+0.1*($skill_lvl-$craft_lvl)**1.1))*$bonus);
        }
    }elseif($skill_lvl===$craft_lvl){
        $craft_xp=$craft_base_xp*$bonus;
    }else{
        $craft_xp=0;
    }
    return intval($craft_xp);
}

function lvl_and_lvlgoal($exp, $lvl_goal, $craft_lvl, $craft_base_xp, $bonus=1){
    $xp_goal=lvl_to_xp($lvl_goal);
    $xp = $exp;
    $qty = 0;
    if ($xp_goal>$xp){
        while($xp<$xp_goal){
            $craft_xp = craft_xp($craft_lvl, $craft_base_xp, xp_to_lvl($xp), $bonus);
            if ($craft_xp>0){
                $xp = $xp + $craft_xp;
                $qty++;
            }else{
                break;
            }
        }
        return array($xp,xp_to_lvl($xp),$qty);
    }else{
        return array($xp,xp_to_lvl($xp),0);
    }
}

function lvl_and_qty($exp,$craft_qty,$craft_lvl,$craft_base_xp, $bonus=1){
    $xp = $exp;
    if ($craft_qty>0){
        for($i=0; $i < $craft_qty; $i++){
            $xp = $xp + craft_xp($craft_lvl, $craft_base_xp, xp_to_lvl($xp), $bonus);
        }
        if ($xp>lvl_to_xp(200)){
            $xp=lvl_to_xp(200);
        }
        return array($xp,xp_to_lvl($xp));
    }else{
        if ($xp>lvl_to_xp(200)){
            $xp=lvl_to_xp(200);
        }
        return array($xp,xp_to_lvl($xp));
    }
}