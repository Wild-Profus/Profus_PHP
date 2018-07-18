<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
    <link rel="stylesheet" href="https://openlayers.org/en/v3.19.1/css/ol.css" type="text/css">
    <style>
        #map {
            position: relative;
        }
        #info {
            position: absolute;
            height: 1px;
            width: 1px;
            z-index: -1;
        }
        .tooltip.in {
            opacity: 0.7;
        }
        .tooltip-inner {
            white-space:pre-wrap;
        }
        .reset {
            top: 36px;
            right: .5em;
        }
        #export {
            top: 65px;
            right: .5em;
        }
        #position{
         width: 68px;
         height: 30px;
         padding: 5px;
         top : 5px;
         left: 47%;
         margin:auto;
         z-index: 40;
        }
    </style>
    <title>Carte des ressources Dofus 2 - Profus</title>
    <meta name="title" content="Carte des ressources Dofus 2 - Profus">
    <meta name="description" content="Liste des ressources récoltables par métier dans Dofus 2.0">
</head>
<body style="height:100%;">
<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
    <div class="container-fluid" style="height:100%;">
        <div class="row" style="height:100%;">
            <div id="check" class="map col-sm-2 preload" style="padding-right:0px;">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <span role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Alchimiste
                                </span>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <input class="ckb" type="checkbox" value="Ortie">Ortie</input></br>
                                <input class="ckb" type="checkbox" value="Sauge">Sauge</input></br>
                                <input class="ckb" type="checkbox" value="Treflea5feuilles">Trèfle à 5 feuilles</input></br>
                                <input class="ckb" type="checkbox" value="MentheSauvage">Menthe Sauvage</input></br>
                                <input class="ckb" type="checkbox" value="OrchideeFreyesque">Orchidée Freyesque</input></br>
                                <input class="ckb" type="checkbox" value="Edelweiss">Edelweiss</input></br>
                                <input class="ckb" type="checkbox" value="GrainedePandouille">Pandouille</input></br>
                                <input class="ckb" type="checkbox" value="Ginseng">Ginseng</input></br>
                                <input class="ckb" type="checkbox" value="Belladone">Belladone</input></br>
                                <input class="ckb" type="checkbox" value="Mandragore">Mandragore</input></br>
                                <input class="ckb" type="checkbox" value="Salikrone">Salikrone</input></br>
                                <input class="ckb" type="checkbox" value="PerceNeige">Perce-neige</input></br>
                           </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <span class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Bûcheron
                                </span>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <input class="ckb" type="checkbox" value="Frene">Frêne</input></br>
                                <input class="ckb" type="checkbox" value="Chataignier">Châtaignier</input></br>
                                <input class="ckb" type="checkbox" value="Noyer">Noyer</input></br>
                                <input class="ckb" type="checkbox" value="Chene">Chêne</input></br>
                                <input class="ckb" type="checkbox" value="Bombu">Bombu</input></br>
                                <input class="ckb" type="checkbox" value="Erable">Erable</input></br>
                                <input class="ckb" type="checkbox" value="Oliviolet">Oliviolet</input></br>
                                <input class="ckb" type="checkbox" value="If">If</input></br>
                                <input class="ckb" type="checkbox" value="Bambou">Bambou</input></br>
                                <input class="ckb" type="checkbox" value="Merisier">Merisier</input></br>
                                <input class="ckb" type="checkbox" value="Noisetier">Noisetier</input></br>
                                <input class="ckb" type="checkbox" value="Ebene">Ebène</input></br>
                                <input class="ckb" type="checkbox" value="Kaliptus">Kaliptus</input></br>
                                <input class="ckb" type="checkbox" value="Charme">Charme</input></br>
                                <input class="ckb" type="checkbox" value="BambouSombre">Bambou sombre</input></br>
                                <input class="ckb" type="checkbox" value="Orme">Orme</input></br>
                                <input class="ckb" type="checkbox" value="BambouSacre">Bambou sacré</input></br>
                                <input class="ckb" type="checkbox" value="Tremble">Tremble</input></br>
                                <input class="ckb" type="checkbox" value="Aquajou">Aquajou</input></br>
                           </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                                <span class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Paysan
                                </span>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
                                <input class="ckb" type="checkbox" value="Ble">Blé</input></br>
                                <input class="ckb" type="checkbox" value="Orge">Orge</input></br>
                                <input class="ckb" type="checkbox" value="Avoine">Avoine</input></br>
                                <input class="ckb" type="checkbox" value="Houblon">Houblon</input></br>
                                <input class="ckb" type="checkbox" value="Lin">Lin</input></br>
                                <input class="ckb" type="checkbox" value="Seigle">Seigle</input></br>
                                <input class="ckb" type="checkbox" value="Riz">Riz</input></br>
                                <input class="ckb" type="checkbox" value="Malt">Malt</input></br>
                                <input class="ckb" type="checkbox" value="Chanvre">Chanvre</input></br>
                                <input class="ckb" type="checkbox" value="Mais">Maïs</input></br>
                                <input class="ckb" type="checkbox" value="Millet">Millet</input></br>
                                <input class="ckb" type="checkbox" value="Quisnoa">Quisnoa</input></br>
                                <input class="ckb" type="checkbox" value="Frostiz">Frostiz</input></br>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFour">
                            <h4 class="panel-title">
                                <span class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
                                    Mineur
                                </span>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
                                <input class="ckb" type="checkbox" value="Fer">Fer</input></br>
                                <input class="ckb" type="checkbox" value="Cuivre">Pierre Cuivrée</input></br>
                                <input class="ckb" type="checkbox" value="Bronze">Bronze</input></br>
                                <input class="ckb" type="checkbox" value="Kobalte">Pierre de Kobalte</input></br>
                                <input class="ckb" type="checkbox" value="Manganese">Manganèse</input></br>
                                <input class="ckb" type="checkbox" value="Etain">Etain</input></br>
                                <input class="ckb" type="checkbox" value="Silicate">Silicate</input></br>
                                <input class="ckb" type="checkbox" value="Argent">Argent</input></br>
                                <input class="ckb" type="checkbox" value="Bauxite">Pierre de Bauxite</input></br>
                                <input class="ckb" type="checkbox" value="Or">Or</input></br>
                                <input class="ckb" type="checkbox" value="Dolomite">Dolomite</input></br>
                                <input class="ckb" type="checkbox" value="Obsidienne">Obsidienne</input></br>
                                <input class="ckb" type="checkbox" value="Ecumedemer">Ecume de mer</input></br>
                                <input class="ckb" type="checkbox" value="EauPotable">Eau Potable</input></br>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingFive">
                            <h4 class="panel-title">
                                <span class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Pêcheur
                                </span>
                            </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                            <div class="panel-body">
                                <input class="ckb" type="checkbox" value="Goujon">Goujon</input></br>
                                <input class="ckb" type="checkbox" value="Greuvette">Greuvette</input></br>
                                <input class="ckb" type="checkbox" value="Truite">Truite</input></br>
                                <input class="ckb" type="checkbox" value="CrabeSourimi">Crabe</input></br>
                                <input class="ckb" type="checkbox" value="PoissonChaton">Poisson-Chaton</input></br>
                                <input class="ckb" type="checkbox" value="PoissonPane">Poisson Pané</input></br>
                                <input class="ckb" type="checkbox" value="CarpedIem">Carpe d'Iem</input></br>
                                <input class="ckb" type="checkbox" value="SardineBrillante">Sardine Brillante</input></br>
                                <input class="ckb" type="checkbox" value="Brochet">Brochet</input></br>
                                <input class="ckb" type="checkbox" value="Kralamoure">Kralamoure</input></br>
                                <input class="ckb" type="checkbox" value="Anguille">Anguille</input></br>
                                <input class="ckb" type="checkbox" value="DoradeGrise">Dorade Grise</input></br>
                                <input class="ckb" type="checkbox" value="Perche">Perche</input></br>
                                <input class="ckb" type="checkbox" value="RaieBleue">Raie</input></br>
                                <input class="ckb" type="checkbox" value="Lotte">Lotte</input></br>
                                <input class="ckb" type="checkbox" value="RequinMarteauFaucille">Requin Marteau-Faucille</input></br>
                                <input class="ckb" type="checkbox" value="BarRikain">Bar Rikain</input></br>
                                <input class="ckb" type="checkbox" value="Morue">Morue</input></br>
                                <input class="ckb" type="checkbox" value="Tanche">Tanche</input></br>
                                <input class="ckb" type="checkbox" value="Espadon">Espadon</input></br>
                                <input class="ckb" type="checkbox" value="Patelle">Patelle</input></br>
                                <input class="ckb" type="checkbox" value="Poisskaille">Poisskaille</input></br>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
            <div id="loadingbar" style="height: 100%">
                <div class="progress" style="position: absolute;top: 50%;left:25%; width: 50%;">
                    <div class="progress-bar loading-color progress-bar-striped active" role="progressbar"
                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        Chargement en cours ...
                    </div>
                </div>
            </div>
            <div id="map" class="map col-sm-10 preload" style="height:100%;">
                <div id="info"></div>
            </div>
        </div>
        </div>
    </div>
<?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="https://openlayers.org/en/v3.19.1/build/ol.js"></script>
<script src="js/map.js"></script>
</body>
</html>
