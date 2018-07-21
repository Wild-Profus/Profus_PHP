<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
    <title>Almanax - Profus</title>
    <meta name="title" content="Almanax - Profus">
    <meta name="description" content="Liste des offrandes et des récompenses Dofus 2 sur un mois ou plus !">
</head>
<body>
<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
    <div class="container preload">
        <div class="row text-center">
            <button class="pars btn btn-lg btn-danger" value="fabri">Artisanat</button>
            <button class="pars btn btn-lg btn-danger" value="réc">Récolte</button>
            <button class="pars btn btn-lg btn-danger" value="dragodinde">Elevage</button>
            <button class="pars btn btn-lg btn-danger" value="chall">Challenges</button>
            <button class="pars btn btn-lg btn-danger" value="quête">Quête</button>
            <button class="pars btn btn-lg btn-danger" value="onjon">Donjon</button>
            <i id="ics_export" class="btn btn-lg btn-primary glyphicon glyphicon-export" data-toggle="tooltip" data-placement="bottom" title="Exporte les données filtrées au format ical" style="margin: 0 0 2px 0"></i>
        </div>
        <div class="row">
            <form class="col-xs-6 col-xs-offset-3 form-inline text-center" style="margin-top: 10px;">
                <div class="row">
                    <div class="form-group has-feedback">
                        <input type="text" id='query' class="form-control pars" placeholder="Rechercher" autocomplete="off"/>
                        <i class="form-control-feedback glyphicon glyphicon-search"></i>
                    </div>
                    <select class="pars form-control" id="table_len">
                      <option value="8">par semaine</option>
                      <option value="15">par quinzaine</option>
                      <option value="31">par mois</option>
                      <option value="366">par année</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="container preload">
        <div class="row">
            <div class ='col-xs-12'>
                <table id="mytable" class="table table-striped table-hover table-responsive" style="margin-top: 6px;">
                    <thead>
                    <tr>
                        <th class="col-sm-2 text-center">Date</th>
                        <th class="col-sm-7 text-center">Bonus</th>
                        <th class="col-sm-3 text-center">Offrande</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
<?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="js/almanax.js"></script>
</body>
</html>