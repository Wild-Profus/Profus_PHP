<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
    <title>Simulateur de métiers Dofus 2 - Profus</title>
    <meta name="title" content="Simulateur de métiers Dofus 2 - Profus">
    <meta name="description" content="Cet outil permet de trouver les recettes et le nombre de ressources nécessaire pour monter vos métiers de craft dans Dofus 2.0">
</head>
<body>
<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div id="start" class="col-xs-4 col-xs-offset-1 text-center">
                    <label>Renseigner un niveau de départ</label><br />
                    <div class="input-group col-xs-6 pull-left">
                        <input type="number" class="form-control" id="experience" placeholder="Exp">
                    </div>
                    <div class="input-group col-xs-6 ">
                        <input type="number" class="form-control" id="niveau" placeholder="Niv">
                    </div>
                </div>
                <div class="col-xs-4 col-xs-offset-1 text-center">
                    <label>Sélectionner un métier</label><br />
                    <div class="input-group">
                        <select id="skill" class="form-control">
                            <option value="26">Alchimiste</option>
                            <option value="16">Bijoutier</option>
                            <option value="65">Bricoleur</option>
                            <option value="2">Bûcheron</option>
                            <option value="41">Chasseur</option>
                            <option value="15">Cordonnier</option>
                            <option value="60">Façonneur</option>
                            <option value="11">Forgeron</option>
                            <option value="24">Mineur</option>
                            <option value="28">Paysan</option>
                            <option value="36">Pêcheur</option>
                            <option value="13">Sculpteur</option>
                            <option value="27">Tailleur</option>
                        </select>
                        <span class="input-group-addon btn btn-success" id="goskill" style="color: white;font-size: large;padding:4px 12px 4px 12px">Go</span>
                    </div>
                </div>
                <div class="col-xs-2 text-center">
                    <label>Bonus Xp</label><br/>
                    <select id="bonus" class="form-control">
                        <option value="1">Normal</option>
                        <option value="1.25">+25%</option>
                        <option value="1.5">+50%</option>
                        <option value="2">x2</option>
                        <option value="3">x3</option>
                        <option value="6">x6</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <table id="lvlplan" class="table table-striped table-hover table-responsive text-center" style="margin-bottom: 0px;">
            <thead>
                <tr>
                    <th class="col-xs-2 text-center">Départ</th>
                    <th class="col-xs-2 text-center">Objet</th>
                    <th class="col-xs-1 text-center">Niveau</th>
                    <th class="col-xs-1 text-center">Xp de base</th>
                    <th id="head_target" class="col-xs-2 text-center" data-toggle="tooltip" data-placement="auto left" title="" data-container="body" data-original-title="Veuillez choisir si vous voulez fabriquer une quantité fixe de la recette ou si vous souhaitez atteindre un certain niveau (la quantité se mettra à jour automatiquement). Pour annuler une quantité, effacez là simplement.">Cible</th>
                    <th class="col-xs-3 text-center">Résultat</th>
                    <th class="col-xs-1 text-center"><i id='rmlastrow' class="btn btn-danger glyphicon glyphicon-remove"></i> </th>
                </tr>
            </thead>
            <tbody id="lvlplanbody">

            </tbody>
        </table>
    </div>
</div>
<div class="container">
    <div class="row">
        <table id="mytable" class="table table-striped table-hover table-responsive text-center" cellspacing="0">
            <thead>
            <tr>
                <th class="col-xs-1 text-center">Niveau</th>
                <th class="col-xs-2 text-center">Objet</th>
                <th class="col-xs-7 text-center">Recette</th>
                <th class="col-xs-1 text-center">Xp</th>
                <th class="col-xs-1 text-center"></th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="js\metier.js"> </script>
</body>
</html>