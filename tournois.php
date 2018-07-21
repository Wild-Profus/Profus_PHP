<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
</head>
<body>
<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
<div id="loadingbar" style="height: 100%">
    <div class="progress" style="position: absolute;top: 50%;left:25%; width: 50%;">
        <div class="progress-bar loading-color progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            Chargement en cours ...
        </div>
    </div>
</div>
<div class="container preload">
    <div class="row">
        <h1>Tri des tournois ?</h1>
        <form>
            <label for="srv">Serveur :</label>
            <select id="srv" class='form-control'>
                <option value=""></option>
                <?php
                echo $server_selector;
                ?>
            </select>
            <label for="size">Taille des équipes :</label>
            <select id="size" class="form-control">
                <option value="1">1v1</option>
                <option value="2">2v2</option>
                <option value="3">3v3</option>
                <option value="4">4v4</option>
            </select>
            <label for="points">Système de points :</label>
            <select id="points" class="form-control">
                <option value="1">Aucun</option>
                <option value="2">Many Cup</option>
                <option value="3">Personnalisé</option>
            </select>
        </form>
        <h1>Création d'un tournoi</h1>
        <form>
            <label for="srv">Serveur :</label>
            <select id="srv" class='form-control'>
                <option value=""></option>
                <?php
                echo $server_selector;
                ?>
            </select>
            <label for="size">Taille des équipes :</label>
            <select id="size" class="form-control">
                <option value="1">1v1</option>
                <option value="2">2v2</option>
                <option value="3">3v3</option>
                <option value="4">4v4</option>
            </select>
            <label for="points">Système de points :</label>
            <select id="points" class="form-control">
                <option value="1">Aucun</option>
                <option value="2">Many Cup</option>
                <option value="3">Personnalisé</option>
            </select>
            <label for="matching">Système d'affrontement</label>
            <select id="matching" class="form-control">
                <option value="1">Arbre</option>
                <option value="2">Croisement</option>
                <option value="3">Personnalisé</option>
            </select>
            <label for="teams">Nombre maximum d'équipe : </label>
            <input id="teams" type="number" class="form-control">
            <label for="forbidden">Classes interdites </label>
            <input id="forbidden" type="text" class="form-control">
            <label for="limit">Date limite d'inscription</label>
            <input id="limit" type="date" class="form-control">
            <label for="taxe">Frais d'inscription</label>
            <input id="taxe" type="number" class="form-control">
            <label for="1">Récompenses premiers</label>
            <input id="1" type="text" class="form-control">
            <label for="2">Récompenses seconds</label>
            <input id="2" type="text" class="form-control">
            <label for="3">Récompenses troisièmes</label>
            <input id="3" type="text" class="form-control">
            <label for="round">Nombre de ronde/phase</label>
            <input id="round" type="number" class="form-control">
            <label for="dates">Date des rondes</label>
            <div id="dates"></div>
            <label for="round">Plage horaire privilégiée</label>
            <input id="tstart" type="time" class="form-control">
            <input id="tend" type="time" class="form-control">
            <label for="admin">Nom du personnage qui gère le tournoi sur dofus</label>
            <input id="admin" type="text" class="form-control">
            <label for="second">Personnage(s) qui le seconde(nt)</label>
            <input id="second" type="text" class="form-control">
            <label for="youtube">Nom de la chaine youtube</label>
            <input id="youtube" type="text" class="form-control">
            <label for="misc">Informations à ajouter</label>
            <textarea id="misc" class="form-control"></textarea>
        </form>
    </div>
</div>
<?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="js/tournois.js"></script>
</body>
</html>