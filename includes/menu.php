<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__FILE__,2).'/modules/shared/common.php';
$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME LIKE 'runes' AND COLUMN_NAME NOT IN ('id','stat','rune','level','db_name','effectId','weight','base','pa','ra') ORDER BY COLUMN_NAME";
$servers = sqldb::safesql($query);
if ($_SESSION['servor']!==""){
    $server = htmlspecialchars($_SESSION['servor']);
    if ($server==""){
        $server="Agride";
    }
    $prices = sqldb::safesql("SELECT `rune`,`$server` AS `srv` FROM `runes` ORDER BY `level` ASC, `rune` ASC");
}else{
    $prices = sqldb::safesql("SELECT `rune` FROM `runes`");
}
$server_selector = "";
foreach($servers as $srv){
    $server = $srv['COLUMN_NAME'];
    if ($server!==$_SESSION['servor']){
        $server_selector = $server_selector."<option value='$server'>$server</option>";
    }else{
        $server_selector = $server_selector."<option selected='selected' value='$server'>$server</option>";
    }
}
?>
<div id ="navbararea" class="container-fluid">
    <div class="row">
        <div id='navigation_bar'  class="navbar navbar-inverse navbar-fixed-top">
            <div class="row">
                <div class="navbar-header col-sm-1">
                    <a class="navbar-brand col-sm-12" href="index.php">Profus<small>&alpha;</small></a>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#Menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php if ($_SESSION['login']){
                        echo "<i class='logout buttonpiti btn btn-danger glyphicon glyphicon-off pull-right'></i>";
                        echo '<i class="buttonpiti btn btn-default glyphicon glyphicon-user pull-right white" data-html="true" role="button" data-placement="bottom" data-toggle="popover" title="'.$_SESSION['servor'].'" data-content="';
                        echo "<select class='userserv form-control'>";
                        echo $server_selector;
                        echo "</select><a class='btn text-center' href='/forums/usercp.php?action=password'>Changer mot de passe</a>\">";
                        echo '</i>';
                    }
                    else {
                        echo '
                            <i class="buttonpiti btn btn-default glyphicon glyphicon-off pull-right white" data-toggle="collapse" data-target="#login"></i>
                            <i class="buttonpiti btn btn-default glyphicon glyphicon-edit pull-right white"></i>
                        ';
                    }
                    ?>
                    <i class='buttonpiti btn btn-default glyphicon glyphicon-shopping-cart pull-right' data-toggle="collapse" data-target="#cart"></i>
                </div>
                <div class="navbar-inner col-sm-11">
                    <ul id="Menu" class="nav navbar-nav collapse navbar-collapse">
                        <!--<li class="<?php if (basename($_SERVER['PHP_SELF'])=='build.php'){echo "active";}?> nav-button"><a href="build.php" data-toggle="tooltip" data-placement='bottom' title="Optimise ton perso">Builds</a></li>-->
                        <li class="<?php if (basename($_SERVER['PHP_SELF'])=='news.php'){echo "active";}?> nav-button"><a href="../news.php" data-toggle="tooltip" data-placement='bottom' title="Les dernière dépêches"><img class="nav-img" id="nav-news" src="../images/icons/navigation/news.png" style="padding: 3px 7px 3px 7px"></a></li>
                        <li class="<?php if (basename($_SERVER['PHP_SELF'])=='map.php'){echo "active";}?> nav-button"><a href="../map.php" data-toggle="tooltip" data-placement='bottom' title="Trouves toutes les ressources"><img class="nav-img" id="nav-map" src="../images/icons/navigation/map.png"></a></li>
                        <!--<li class="<?php if (basename($_SERVER['PHP_SELF'])=='trade.php'){echo "active";}?> nav-button"><a href="trade.php" data-toggle="tooltip" data-placement='bottom' title="Suivi des ventes !">TradeHelper</a></li>-->
                        <li class="<?php if (basename($_SERVER['PHP_SELF'])=='almanax.php'){echo "active";}?> nav-button"><a href="../almanax.php" data-toggle="tooltip" data-placement='bottom' title="Les offrandes sur un mois ou plus"><img class="nav-img" id="nav-almanax" src="../images/icons/navigation/almanax.png"></a></li>
                        <li class="<?php if (basename($_SERVER['PHP_SELF'])=='idoles.php'){echo "active";}?> nav-button"><a href="../idoles.php" data-toggle="tooltip" data-placement='bottom' title="Trouve LA composition"><img class="nav-img" id="nav-idols" src="../images/icons/navigation/idoles.png"></a></li>
                        <!--li class="<?php if (basename($_SERVER['PHP_SELF'])=='reproduction.php'){echo "active";}?> nav-button"><a href="reproduction.php" data-toggle="tooltip" data-placement='bottom' title="Faite l'amour pas la guerre"><img class="nav-img" id="nav-dd" src="../images/icons/navigation/dragodindes.png"></a></li-->
                        <li class="<?php if (basename($_SERVER['PHP_SELF'])=='metiers.php'){echo "active";}?> nav-button"><a href="../metiers.php" data-toggle="tooltip" data-placement='bottom' title="Les meilleures recettes pour les monter"><img class="nav-img" id="nav-jobs" src="../images/icons/navigation/metiers.png"></a></li>
                        <li class="<?php if (basename($_SERVER['PHP_SELF'])=='brisage.php'){echo "active";}?> nav-button"><a href="../brisage.php" data-toggle="tooltip" data-placement='bottom' title="Trouve les brisages rentables"><img class="nav-img" id="nav-break" src="../images/icons/navigation/brisage.png"></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right col-sm-1 collapse navbar-collapse" style="margin: 8px;">
                        <li id="btnarea">
                            <i class='btn btn-default glyphicon glyphicon-shopping-cart white' data-toggle="collapse" data-target="#cart"></i>
                    <?php if ($_SESSION['login']){
                    echo '<i class="btn btn-default glyphicon glyphicon-user white" data-html="true" role="button" data-placement="bottom" data-toggle="popover" title="'.$_SESSION['login'].'" data-content="';
                    echo "<select class='userserv form-control'>";
                    echo $server_selector;
                    echo "</select><a class='btn text-center' href='/forums/usercp.php?action=password'>Changer mot de passe</a>\">";
                    echo '</i>
                            <i class="logout btn btn-danger glyphicon glyphicon-off"></i>"';
                    }
                    else {
                        echo '          
                            <i class="btn btn-default glyphicon glyphicon-edit white"></i>
                            <i class="btn btn-default glyphicon glyphicon-off white" data-toggle="collapse" data-target="#login"></i>
                        ';
                    }
                    ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
            <?php
            if (!$_SESSION['login']){
                echo
                '<div class="row">
                    <form id="login" class="col-xs-6 col-xs-offset-2 col-sm-4 col-sm-offset-7 form-inline collapse navbar-fixed-top" role="form">
                        <div class="pull-right">
                            <div class="input-group col-xs-10 col-sm-5">
                                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="username" type="text" class="form-control" placeholder="Pseudo" autocomplete="off"/>
                            </div>
                            <div class="input-group col-xs-10 col-sm-6">
                                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="password" type="password" class="form-control" placeholder="Mot de Passe" autocomplete="off"/>
                                <span id ="logingo" class="btn input-group-addon">
                                    <i class="glyphicon glyphicon glyphicon-log-in"></i>
                                </span>
                            </div>
                        </div>
                    </form>   
                </div>';
            }?>
	</div>
</div>
<div class="container-fluid">
    <div class="row">
        <div id="cart" class="dropdown collapse col-xs-12">
            <div class="row" style="padding: 10px;">
                <div class="col-xs-4">
                    <label style="color: dimgray;font-size: 20px">Shopping List</label>
                    <div id="display_type" class="btn-group pull-right" role="group" aria-label="...">
                        <button id='global_cart' type="button" class="btn btn-default active">
                            <i class="glyphicon glyphicon-th-list"></i>
                        </button>
                        <button id='detailed_cart' type="button" class="btn btn-default">
                            <i class="glyphicon glyphicon-list-alt"></i>
                        </button>
                    </div>
                    <a target="_blank" href="printer.php" class="btn btn-info glyphicon glyphicon-print pull-right" style="margin: -1px 5px 0 15px;"></a>
                    <button id="deleteCart" class="btn btn-danger glyphicon glyphicon-trash pull-right" style="margin: -1px 5px 0 15px;"></button>
                </div>
                <div class="col-xs-7 input-group ui-widget">
                    <input type="text" id='cart_item' class="form-control" placeholder="Rechercher un objet, ou mettre un lien public dofusplanner / dofusbook" autocomplete="off">
                    <span id ="add_item_to_cart" class="input-group-addon btn btn-primary">
                    <i class="glyphicon glyphicon-shopping-cart" style="color:white;"></i>
                    </span>
                </div>
            </div>
            <table id="cart_content" class="table table-striped table-hover table-responsive"></table>
        </div>
    </div>
</div>