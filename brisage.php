<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
    <title>Simulateur de brisage Dofus 2 - Profus</title>
    <meta name="title" content="Simulateur de brisage Dofus 2 - Profus">
    <meta name="description" content="Cet outil permet d'estimer la rentabilité d'un brisage dans Dofus 2, et de choisir le focus à faire (ou pas !).">
</head>
<body>
<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
<div class="container">
    <div class="row well well-sm">
        <h3 style="margin-top: 0">Utilisation recommandée :</h3>
        <p>Cet outil permet de savoir quelle rune il faut focus ou non sur un objet pour gagner le plus de kamas.</p>
        <p>Cela dépend du prix des runes de chaque serveur. Ces prix sont remplis bénévolement par certains joueurs qui se sont portés volontaires sur Discord ou sur le forum. S'il vous semble que les données de vos serveurs ne sont pas mis à jour suffisamment souvent, libre à vous de vous de leur donner un coup de main, ça sera très sympa !</p>
        <p>Gardez à l'esprit que le prix des runes peuvent varier très rapidement sur de très courtes périodes, pensez à vérifier avant de briser si les prix du site correspondent à peu près à ce que vous voyez en jeu (en hôtel des ventes ou via les prix moyens).</p>
        <p>Cet outil <strong>ne donne pas le taux de brisage</strong> des objets. Il suppose un taux de base de 100%, mais c'est un pari que vous faites, le taux observé en jeu aura autant de chance d'être plus petit que plus grand. C'est votre habilité à flairer les bons objets qui fera votre richesse !</p>
        <p>Vous ne pourrez avoir une idée du taux de brisage qu'après un premier brisage.</p>
        <p>Le nombre de runes obtenues dépend du niveau de l'objet (connus), et du jet de l'objet (connu mais variable) et du taux de brisage (inconnu). Comme rares sont ceux qui remplissent ligne par ligne le tableau, j'ai rajouté des boutons pour voir rapidement ce que donne un jet nul, un jet moyen et un jet parfait. Néanmoins pour être le plus précis possible, il faudrait dans l'idéal remplir ligne par ligne tous les jets.</p>
        <p>Je précise enfin qu'il est possible de modifier le taux de brisage, le Jet de chaque stat, le prix de la rune, et d'ajouter un exo. Le nombre de rune obtenus ainsi que la valeur en kamas seront automatiquement actualisés.</p>
        <p><strong>Nouveau : aidez nous à maintenir les prix à jour en sauvegardant les prix que vous renseignez !</strong></p>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="row">
                <div class="input-group ui-widget">
                    <input type="text" id='item' class="form-control" placeholder="Rechercher un objet" autocomplete="off">
                    <div id ="goquery" class="input-group-addon btn btn-primary">
                            <i class="glyphicon glyphicon-search" style="color:white;"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <h1 id="itbb_name" class="col-xs-12 text-center"></h1>
                <h4 class="col-xs-6 text-center" id="itbb_type"></h4>
                <h4 class="col-xs-6 text-center" id="itbb_level"></h4>
                <h4 class="col-xs-12 text-center" id="itbb_roll_selector"></h4>
                <h4 class="col-xs-12 text-center" id="itbb_craftable"></h4>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="col-xs-12 text-right" id="itbb_img" style="margin:auto;">
            </div>
        </div>
        <div class="col-xs-2 text-center">
            <div>
                <select id="itbb_srv" class='form-control' data-toggle="tooltip" data-placement="auto left" title="" data-container="body" data-original-title="Tu peux définir un serveur par défaut en cliquant sur le bonhomme juste au dessus ! (inscription requise)">
                <?php
                    echo $server_selector;
                ?>
                </select>
            </div>
            <div id="itbb_shopbtn" style="margin-top:55px"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12" id="itbb">
            <div class="table-responsive" id="tableanchor">
                <table id="broke_simulation" class="table table-striped table-bordered table-hover text-center">
                </table>
            </div>
        </div>
    </div>
</div>
<?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="js/brisage.js"></script>
</body>
</html>