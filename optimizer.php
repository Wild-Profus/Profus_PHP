<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
<style>
    #selector > img{
        cursor: pointer;
    }

    #selector > div > div > label{
        width: 76px;
    }

    .must{
        background-color: #2fff42;
    }

    .mustEq{
        background-color: red;
    }

    .see{
        background-color: dodgerblue;
    }

    .seeEq{
        background-color: #cb31ff;
    }

    #selector {
        display: flex;
        flex-flow: row;
        flex-wrap: wrap;
    }

    #selector > div {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-flow: column;
    }

    .popover.left{
        margin-left: 230px !important;
    }

    .popover-content{
        padding: 0 10px 0 10px;
    }

    .popover-content>table{
        margin: 0;
    }
</style>
</head>
<body>
<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
<div class="container">
    <!--div class="row" style="display:flex;margin-bottom: -20px;">
        <div id="class" class="col-xs-4 text-center well well-sm" style="padding-top:15px;order:1">
                <div style="max-width: 323px;margin:auto">
                    <?php
                    $class_query = "SELECT DISTINCT `class_name` FROM `".$version."spells` WHERE `class_name`!='' ORDER BY `class_name` ASC";
                    $classes = sqldb::safesql($class_query);
                    foreach($classes as $class_array){
                        $classl = lcfirst($class_array['class_name']);
                        $class = ucfirst($class_array['class_name']);
                        echo "<a href='#'><img class='img-rounded' src='/images/icons/characters/$classl.png' alt='$class' data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title='$class' style='width: 50px;border:1px solid transparent;'/></a>";
                    }
                    ?>
            </div>
        </div>
        <div id="spells" class="col-xs-8 well well-sm text-center" style="order: 2">
            <div id="class_spells" style="max-width: 626px;min-height: 110px;margin-left: auto;margin-right:auto"></div>
            <div class="text-center">
                <?php
                $class_query = "SELECT DISTINCT `spell_name`,`description` FROM `".$version."spells` WHERE `class_name`='' ORDER BY `spell_name` ASC";
                $classes = sqldb::safesql($class_query);
                foreach ($classes as $class_array){
                    $spell1 = lcfirst($class_array['spell_name']);
                    $spell = ucfirst($class_array['spell_name']);
                    echo '<a href="#"><img class="img-rounded" src="/images/icons/spells/'.$spell.'.png" alt="'.$spell1.'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$spell.' : '.$class_array['description'].'"/></a>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row well well-sm" style="margin-bottom: 0;">
        <div class="col-xs-3">
            <div id="symbol" class="row">
                <img id="symbol_img" class="img-responsive" src="images/icons/symbols/none.png" style="margin: auto;">
            </div>
            <div class="row">
                <h4 class="text-center">Bonus actifs :</h4>
                <form class="form-inline text-center">
                    <div class="form-group">
                        <label for="power" style="width:71px;">Puissance</label>
                        <input id="power" class="form-control" value="0" style="text-align:right;width:50px;">
                    </div>
                    <div class="form-group">
                        <label for="damage" style="width:71px;">Dommage</label>
                        <input id="damage" class="form-control" value="0" style="text-align:right;width:50px;">
                    </div>
                </form>
            </div>
        </div>
        <div id='tour_de_jeu' class="col-xs-9">

        </div>
    </div!-->
    <div id="instructions" class="row well well-sm text-center">
        <p>Bienvenue sur l'outil de recherche d'objets.</p>
        <p>Après avoir choisi le ou les types d'objets recherchés, cliquer sur les effets qui vous intéressent.</p>
        <p>Il existe différents types de conditions, accessibles par un clic gauche ou droit : </p>
        <ul>
            <li><strong class="must">Obligatoire :</strong> Les objets doivent avoir cet effet sous forme primaire. Inclus dans le 'score'.</li>
            <li><strong class="mustEq">Obligatoire Eq :</strong> Les objets doivent avoir cet effet ou un effet équivalent (ou les deux). Inclus dans le 'score'.</li>
            <li><strong class="see">A afficher :</strong> Cet effet vous intéresse sous sa forme primaire mais n'est pas obligatoire. Non inclus dans le 'score'.</li>
            <li><strong class="seeEq">A afficher Eq :</strong> Cet effet ou son équivalent (ou les deux) seront affichés. Non inclus dans le 'score'.</li>
        </ul>
        <p>Note : si l'option Eq n'est pas proposée, c'est qu'il n'existe à priori pas d'équivalent pour cet effet (PA,PM,PO,etc.).</p>
    </div>
    <div id ='selector' class="row well well-sm text-center">
        <div class="col-xs-4">
            <div class="row" style="display: flex;align-items: baseline;margin: 3px;">
                <label style="order:1;">Sort&nbsp;&nbsp;</label>
                <input style="order:2" id='element' class="form-control text-center" type="number" value="118">
                <input style="order:3" id='dgtMin' class="form-control text-center" type="number" value="10">
                <input style="order:4" id='dgtMax' class="form-control text-center" type="number" value="20">
            </div>
            <div class="row" style="display: flex;align-items: baseline;margin: 3px;">
                <label style="order:1;">Niveau&nbsp;&nbsp;</label>
                <input style="order:2" id='lvlMin' class="form-control text-center" type="number" value="150">
                <input style="order:3" id='lvlMax' class="form-control text-center" type="number" value="200">
            </div>
            <div id='itemSelect' class="row">
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Amulette.png" data-type="1" alt="Amulette" title="Amulette"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Coiffe.png" data-type="16" alt="Chapeau" title="Coiffe"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Ceinture.png" data-type="10" alt="Ceinture" title="Ceinture"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Dofus.png" data-type="23-151" alt="Dofus/Trophée" title="Dofus/Trophée"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Anneau.png" data-type="9" alt="Anneau" title="Anneau"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="2-3-4-5-6-7-8-19-21-22" alt="Toutes" title="Arme"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Cape.png" data-type="17" alt="Cape" title="Cape"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Botte.png" data-type="11" alt="Bottes" title="Botte"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Bouclier.png" data-type="82" alt="Bouclier" title="Bouclier"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Familier.png" data-type="18" alt="Familier" title="Familier"/></button>
            </div>
            <!--div id='weaponSelect' class="row">
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="2-3-4-5-6-7-8-19-21-22" alt="Toutes" title="Toutes"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="2" alt="Arc" title="Arc"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="3" alt="Baguette" title="Baguette"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="4" alt="Bâton" title="Bâton"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="5" alt="Dague" title="Dague"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="6" alt="Epée" title="Epée"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="21-22" alt="Faux/Pioche" title="Faux/Pioche"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="19" alt="Hache" title="Hache"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="7" alt="Marteau" title="Marteau"/></button>
                <button class="btn btn-xs btn-default" style="margin: 2px"><img class="img-rounded" src="/images/icons/inventory/Arme.png" data-type="8" alt="Pelle" title="Pelle"/></button>
            </div!-->
        </div>
        <div class="col-xs-4">
            <label>Caractéristiques Principales</label>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/7.png" data-effectId="125" data-oppositeEffectId="153" alt="Vitalite" title="Vitalité"/>
                <img class="img-rounded" src="/images/icons/characteristics/27.png" data-effectId="111" data-oppositeEffectId="126" alt="PA" title="PA"/>
                <img class="img-rounded" src="/images/icons/characteristics/95.png" data-effectId="128" data-oppositeEffectId="169" alt="PM" title="PM"/>
                <img class="img-rounded" src="/images/icons/characteristics/3.png" data-effectId="117" data-oppositeEffectId="116" alt="Portee" title="PO"/>
                <img class="img-rounded" src="/images/icons/characteristics/107.png" data-effectId="182" alt="Invocations" title="Invocations"/>
            </div>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/91.png" data-effectId="174" data-oppositeEffectId="175" data-eq="118-126-123-119" data-opEq="157-155-152-154" data-ratio="1" alt="Initiative" title="Initiative"/>
                <img class="img-rounded" src="/images/icons/characteristics/99.png" data-effectId="176" data-oppositeEffectId="177" data-eq="123" data-opEq="152" data-ratio="0.1" alt="Prospection" title="Prospection"/>
                <img class="img-rounded" src="/images/icons/characteristics/47.png" data-effectId="178" data-oppositeEffectId="179" alt="Soins" title="Soins"/>
                <img class="img-rounded" src="/images/icons/characteristics/79.png" data-effectId="158" data-oppositeEffectId="159" data-eq="118" data-opEq="157" data-ratio="5" alt="Pods" title="Pods"/>
                <img class="img-rounded" src="/images/icons/characteristics/15.png" data-effectId="220" alt="Renvoi" title="Renvoi"/>
            </div>
            <label>Caractéristiques Offensives</label>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/45.png" data-effectId="138" data-eq="118-126-123-119" data-opEq="157-155-152-154" data-ratio="0.25" alt="Puissance" title="Puissance"/>
                <img class="img-rounded" src="/images/icons/characteristics/105.png" data-effectId="118" data-oppositeEffectId="157" data-eq="138" data-ratio="1" alt="Force" title="Force"/>
                <img class="img-rounded" src="/images/icons/characteristics/93.png" data-effectId="126" data-oppositeEffectId="155" data-eq="138" data-ratio="1" alt="Intelligence" title="Intelligence"/>
                <img class="img-rounded" src="/images/icons/characteristics/89.png" data-effectId="123" data-oppositeEffectId="152" data-eq="138" data-ratio="1" alt="Chance" title="Chance"/>
                <img class="img-rounded" src="/images/icons/characteristics/29.png" data-effectId="119" data-oppositeEffectId="154" data-eq="138" data-ratio="1" alt="Agilite" title="Agilité"/>
            </div>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/97.png" data-effectId="430" data-oppositeEffectId="431" data-eq="112" data-ratio="1" alt="Dommages Neutre" title="Dommages Neutre"/>
                <img class="img-rounded" src="/images/icons/characteristics/105.png" data-effectId="422" data-oppositeEffectId="423" data-eq="112" data-ratio="1" alt="Dommages Terre" title="Dommages Terre"/>
                <img class="img-rounded" src="/images/icons/characteristics/93.png" data-effectId="424" data-oppositeEffectId="425" data-eq="112" data-ratio="1" alt="Dommages Feu" title="Dommages Feu"/>
                <img class="img-rounded" src="/images/icons/characteristics/89.png" data-effectId="426" data-oppositeEffectId="427" data-eq="112" data-ratio="1" alt="Dommages Eau" title="Dommages Eau"/>
                <img class="img-rounded" src="/images/icons/characteristics/29.png" data-effectId="428" data-oppositeEffectId="429" data-eq="112" data-ratio="1" alt="Dommages Air" title="Dommages Air"/>
            </div>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/39.png" data-effectId="112" data-eq="430-422-424-426-428" data-opEq="431-423-525-427-429" data-ratio="0.25" alt="Dommages" title="Dommages"/>
                <img class="img-rounded" src="/images/icons/characteristics/77.png" data-effectId="2800" data-oppositeEffectId="2801" alt="% Dommages mêlée" title="% Dommages mêlée"/>
                <img class="img-rounded" src="/images/icons/characteristics/69.png" data-effectId="2804" data-oppositeEffectId="2805" alt="% Dommages distance" title="% Dommages distance"/>
                <img class="img-rounded" src="/images/icons/characteristics/83.png" data-effectId="2812" data-oppositeEffectId="2813" alt="% Dommages aux sorts" title="% Dommages aux sorts"/>
                <img class="img-rounded" src="/images/icons/characteristics/11.png" data-effectId="2808" data-oppositeEffectId="2809" alt="% Dommages d'armes" title="% Dommages d'armes"/>
            </div>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/5.png" data-effectId="115" data-oppositeEffectId="171" alt="% Critique" title="% Critique"/>
                <img class="img-rounded" src="/images/icons/characteristics/37.png" data-effectId="418" data-oppositeEffectId="419" alt="Dommages Critiques" title="Dommages Critiques"/>
                <img class="img-rounded" src="/images/icons/characteristics/13.png" data-effectId="414" data-oppositeEffectId="415" alt="Dommages Poussee" title="Dommages Poussée"/>
                <img class="img-rounded" src="/images/icons/characteristics/41.png" data-effectId="225" alt="Dommages Pièges" title="Dommages Pièges"/>
                <img class="img-rounded" src="/images/icons/characteristics/43.png" data-effectId="226" alt="Puissance Pièges" title="Puissance Pièges"/>
            </div>
            <div class="row text-center">
                <button id="opti_go" class="btn btn-info btn-lg glyphicon glyphicon-search"></button>
            </div>
        </div>
        <div class="col-xs-4">
            <label>Armes</label>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/35.png" data-effectId="101" alt="PA(cible)" title="PA(cible)"/>
                <img class="img-rounded" src="/images/icons/characteristics/97.png" data-effectId="100" alt="Dégats Neutre" title="Dégats Neutre"/>
                <img class="img-rounded" src="/images/icons/characteristics/105.png" data-effectId="97" alt="Dégats Terre" title="Dégats Terre"/>
                <img class="img-rounded" src="/images/icons/characteristics/93.png" data-effectId="99" alt="Dégats Feu" title="Dégats Feu"/>
                <img class="img-rounded" src="/images/icons/characteristics/89.png" data-effectId="96" alt="Dégats Eau" title="Dégats Eau"/>
                <img class="img-rounded" src="/images/icons/characteristics/29.png" data-effectId="98" alt="Dégats Air" title="Dégats Air"/>
                <img class="img-rounded" src="/images/icons/characteristics/47.png" data-effectId="108" alt="PdV rendus" title="PdV rendus"/>
                <img class="img-rounded" src="/images/icons/characteristics/87.png" data-effectId="795" alt="Arme de chasse" title="Arme de chasse"/>
            </div>
            <label>Retrait, Esquive, Fuite et Tacle</label>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/111.png" data-effectId="124" data-oppositeEffectId="156" alt="Sagesse" title="Sagesse"/>
                <img class="img-rounded" src="/images/icons/characteristics/35.png" data-eq="124" data-opEq="156" data-ratio="0.1" data-effectId="410" data-oppositeEffectId="411" alt="Retrait PA" title="Retrait PA"/>
                <img class="img-rounded" src="/images/icons/characteristics/21.png" data-eq="124" data-opEq="156" data-ratio="0.1" data-effectId="412" data-oppositeEffectId="413" alt="Retrait PM" title="Retrait PM"/>
                <img class="img-rounded" src="/images/icons/characteristics/31.png" data-eq="124" data-opEq="156" data-ratio="0.1" data-effectId="160" data-oppositeEffectId="162" alt="Esquive PA" title="Esquive PA"/>
                <img class="img-rounded" src="/images/icons/characteristics/19.png" data-eq="124" data-opEq="156" data-ratio="0.1" data-effectId="161" data-oppositeEffectId="163" alt="Esquive PM" title="Esquive PM"/>
                <img class="img-rounded" src="/images/icons/characteristics/17.png" data-eq="119" data-opEq="154" data-ratio="0.1" data-effectId="752" data-oppositeEffectId="754" alt="Fuite" title="Fuite"/>
                <img class="img-rounded" src="/images/icons/characteristics/23.png" data-eq="119" data-opEq="154" data-ratio="0.1" data-effectId="753" data-oppositeEffectId="755" alt="Tacle" title="Tacle"/>
            </div>
            <label>Caractéristiques Défensives</label>
            <div class="row">
                <input type="checkbox" data-effectId="1" data-eq="214-210-213-211-212" data-opEq="219-215-218-216-217" data-width="86" data-toggle="toggle" data-on="Ré % " data-off="Ré % " data-onstyle="success" data-offstyle="danger">
                <input type="checkbox" data-effectId="2" data-eq="214-210-213-211-212" data-opEq="219-215-218-216-217" data-width="86" data-toggle="toggle" data-on="Ré Fixe " data-off="Ré Fixe " data-onstyle="success" data-offstyle="danger">
            </div>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/65.png" data-effectId="214" data-oppositeEffectId="219" alt="% Resistance Neutre" title="% Résistance Neutre"/>
                <img class="img-rounded" src="/images/icons/characteristics/1.png" data-effectId="210" data-oppositeEffectId="215" alt="% Resistance Terre" title="% Résistance Terre"/>
                <img class="img-rounded" src="/images/icons/characteristics/63.png" data-effectId="213" data-oppositeEffectId="218" alt="% Resistance Feu" title="% Résistance Feu"/>
                <img class="img-rounded" src="/images/icons/characteristics/59.png" data-effectId="211" data-oppositeEffectId="216" alt="% Resistance Eau" title="% Résistance Eau"/>
                <img class="img-rounded" src="/images/icons/characteristics/61.png" data-effectId="212" data-oppositeEffectId="217" alt="% Resistance Air" title="% Résistance Air"/>
            </div>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/65.png" data-effectId="244" alt="Resistance Neutre" title="Résistance Neutre"/>
                <img class="img-rounded" src="/images/icons/characteristics/1.png" data-effectId="240" alt="Resistance Terre" title="Résistance Terre"/>
                <img class="img-rounded" src="/images/icons/characteristics/63.png" data-effectId="243" alt="Resistance Feu" title="Résistance Feu"/>
                <img class="img-rounded" src="/images/icons/characteristics/59.png" data-effectId="241" alt="Resistance Eau" title="Résistance Eau"/>
                <img class="img-rounded" src="/images/icons/characteristics/61.png" data-effectId="242" alt="Resistance Air" title="Résistance Air"/>
            </div>
            <div class="row">
                <img class="img-rounded" src="/images/icons/characteristics/25.png" data-effectId="2803" data-oppositeEffectId="2802" alt="% Résistance mêlée" title="% Résistance mêlée"/>
                <img class="img-rounded" src="/images/icons/characteristics/33.png" data-effectId="2807" data-oppositeEffectId="2806" alt="% Résistance distance" title="% Résistance distance"/>
                <img class="img-rounded" src="/images/icons/characteristics/51.png" data-effectId="420" data-oppositeEffectId="421" alt="Résistance Critiques" title="Résistance Critiques"/>
                <img class="img-rounded" src="/images/icons/characteristics/9.png" data-effectId="416" data-oppositeEffectId="417" alt="Résistance Poussée" title="Résistance Poussée"/>
            </div>
        </div>
    </div>
    <table id="results" class="table">

    </table>
</div>
<?php
include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="js/optimizer.js"></script>
</body>
</html>
