<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
    <title>Accueil - Profus</title>
    <meta name="title" content="Inventaire - Profus">
    <meta name="description" content="Organise ton inventaire et fais des listes de drop !">
</head>
<body>
    <?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
    <div class="row">
        <div class="col-xs-offset-1 col-xs-10">
        </div>
    </div>
	<div class="row">
        <div class="col-xs-offset-1 col-xs-3">
            <div class="row">
                <div class="col-xs-12">
                    <label class="text-center">Ajouter une ressource :</label><br />
                    <div class="input-group">
                        <input type="text" class="form-control" id="invItem" placeholder="Objet"> 
                        <input type="number" class="form-control" id="itemQty" placeholder="Quantité">    
                        <span class="input-group-addon btn btn-success" id="addInvItem" style="color: white;font-size: large;padding:4px 12px 4px 12px">+</span>
                    </div>                
                </div>
            </div>
            <div class="row" id="invStatus" style="margin: 5px 0 5px 0">
            
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <button id="invLoad" class="btn btn-default btn-lg">Voir mon inventaire</button>
                    <div id="myInv">
                    
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="row">
                <table class="table table-striped">
                    <thead>
                        <th class="col-xs-8 text-center">Mes listes de ressources :</th>
                        <th class="col-xs-4 text-center">Propriétés</th>
                    </thead>
                    <tbody>
                        <td><button class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open" title="Liste active"></i></button> Liste de test</td>
                        <td class="text-center">
                            <button class="btn btn-success btn-xs"><i class="glyphicon glyphicon-ok-circle" title="Liste active"></i></button>
                            <button class="btn btn-success btn-xs"><i class="glyphicon glyphicon-copyright-mark" title="Propriétaire"></i></button>
                            <button class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-share-alt" title="Liste partagée"></i></button>
                            <button class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash" title="Supprimer la liste"></i></button>
                        </td>
                        <?php

                        ?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div id="showList">
                    
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="row text-center">
                <h4 class="col-xs-7">Objectif actuel : </h4>
                <div class="col-xs-5"><i class=" glyphicon glyphicon-shopping-cart btn btn-success"></i>  <a target="_blank" href="printer.php" class="btn btn-danger glyphicon glyphicon-print"></a></div>
            </div>     
        </div>    
	</div>
<?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
</body>
</html>