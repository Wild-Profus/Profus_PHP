<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
    <title>Simulateur de score Idoles Dofus 2 - Profus</title>
    <meta name="title" content="Simulateur de score Idoles Dofus 2 - Profus">
    <meta name="description" content="Cet outil permet de calculer les scores en simulant une composition d'idoles.">
</head>
<body>
<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
<div class="container-fluid"  style="position: fixed;width: 100%; z-index: 10;background-color:white;top:50px;">
    <div class="row" style="height: 105px;padding: 15px;">
        <span style="position:absolute;left:1px;top:6px;z-index:15;">
            <button id="reset" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-refresh"></i></button><br/>
            <button id="sort" class="btn btn-lg btn-info" style="margin-top:1px"><i class="glyphicon glyphicon-sort"></i></button>
        </span>
        <div class="col-xs-4">
            <h4 class="text-center">Composition</h4>
            <div id="compo" class="text-center" style="vertical-align: middle;"></div>
        </div>
        <div class="col-xs-4">
            <h1 class="col-xs-12 text-center">Score : <span id="score">0</span></h1>
        </div>
        <div class="col-xs-4">
            <h4 class="text-center" style="margin-bottom: 0px;">Interdits</h4>
            <div id="restriction" class="text-center"></div>
        </div>
    </div>
</div>
<div class="container-fluid" style="position: relative;top:105px;">
    <div id="loadingbar" class="col-xs-6 col-xs-offset-3">
        <br><br><br><br><br><br><br>
        <div class="progress">
            <div class="progress-bar loading-color progress-bar-striped active" role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                Chargement en cours...
            </div>
        </div>
    </div>
    <div class="row preload">
        <div id="idoles" class="col-xs-12 text-center" oncontextmenu="return false;">
            <?php
            /****************************  Generation de la page   ***********************************/
            $req = "SELECT `idolId` AS `id`,`idolName`,`spell`,`score` from `0idols` ORDER BY `class`,`score`";
            $i = 0;
			foreach(sqldb::safesql($req) as $idol){
                echo '<span id="'.$idol['id'].'" class="idol"><img class="img-thumbnail" data-bonus="'.$idol['score'].'" data-order="'.$i.'" data-toggle="tooltip" data-placement="top" src="/images/idols/'.$idol['idolName'].'.png" alt="'.$idol['idolName'].'" title="'.$idol['spell'].'"/><p class="idol-txt">'.$idol['score'].'</p></span>';
                $i++;
            };
            /***************************  Ebauche recherche de score  **********************************
    <div class="row">
        <div class="col-xs-4 col-xs-offset-4 text-center"><btn class="btn btn-info" id="best">Best Score</btn></div>
        <div class="col-xs-10 col-xs-offset-1 text-center" id="bestscores"></div>
    </div>*/?>
        </div>
    </div>
</div>
<?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="js/idoles.js"></script>
</body>
</html>