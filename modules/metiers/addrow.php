<?php
require $_SERVER['DOCUMENT_ROOT']."/modules/shared/common.php";
require dirname(__FILE__,1).'/maths.php';
$item_level = htmlspecialchars($_POST['itemlevel']);
$base_xp = htmlspecialchars($_POST['basexp']);
$last_lvl = htmlspecialchars($_POST['lastlvl']);
$last_xp = htmlspecialchars($_POST['lastxp']);
$bonus = htmlspecialchars($_POST['bonus']);
$craft_xp = craft_xp($item_level,$base_xp,$last_lvl,$bonus);
$new_xp = intval($craft_xp) + intval($last_xp);
$new_lvl = xp_to_lvl($new_xp);
json([$base_xp,$new_lvl,$new_xp]);