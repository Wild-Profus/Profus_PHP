<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
    <title>Accueil - Profus</title>
    <meta name="title" content="Accueil - Profus">
    <meta name="description" content="Profus est un site sur Dofus 2.0 fait par des passionnés souhaitant aider les joueurs en fournissant divers outils et simulateurs.">
</head>
<body>
    <?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
            <h4 class="title text-center">Inscription</h4>
            <form id="insForm" class="row">
                <div class="form-group">
                <label for="entryname">Pseudo</label>
                <input type="text" class="form-control" id="entryname" autocomplete="off"/>
                <small id="entrynamehelp" class="form-text text-muted">Ne pas utiliser son identifiant Dofus et/ou Ankama.</small>
                </div>
                <div class="form-group">
                <label for="entrypassword">Mot de passe</label>
                <input type="password" class="form-control" id="entrypassword" autocomplete="off">
                <small id="entrypasswordhelp" class="form-text text-muted">Un mot de passe différent aussi, sinon votre compte sera le mien.</small>                                         
                </div>
                <div class="form-group">
                <label for="entrypasswordcheck">Vérification du mot de passe</label>
                <input type="password" class="form-control" id="entrypasswordcheck" autocomplete="off"/>
                <small class="form-text text-muted"></small> 
                </div>
                <div class="form-group">
                <label for="entrymail">Adresse email</label>
                <input type="email" class="form-control" id="entrymail" aria-describedby="emailHelp" placeholder="Adresse email" autocomplete="off"/>
                <small id="entrymailhelp" class="form-text text-muted">Facultatif, sert à la récupération du compte.</small>
                </div>
                <div class="form-check text-center">
                    <label class="form-check-label">
                      <input id="checkform" type="checkbox" class="form-check-input" required/>
                      Je ne suis pas un robot.
                    </label>
                </div>
                <div class="text-center">
                    <button id="entryform" type="button" class="btn btn-primary">Envoyer</button>
                    <small id="insHelp"></small>
                </div>
                </form>
            </div>
        </div>
    </div>
    <?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
    <script src="../js/inscription.js"> </script>
</body>
</html>