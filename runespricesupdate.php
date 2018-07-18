<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
    <title>Actualisation du prix des runes - Profus</title>
    <meta name="title" content="Runes prices - Profus">
    <meta name="description" content="Merci beaucoup pour votre participation à l'actualisation des prix des runes !">
</head>

<body>

<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>

<?php if ($mybb->user['runespricesupdate']&&$mybb->user['uid']){

    $query = "SELECT COLUMN_NAME as `serv` FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME LIKE 'runes' AND COLUMN_NAME NOT IN ('id','stat','rune','level','db_name','effectId','weight','base','pa','ra') ORDER BY COLUMN_NAME";

    $servers = sqldb::safesql($query);

    if ($mybb->user['servor']!==""){

        $server = htmlspecialchars($mybb->user['servor']);

        if (!in_array($server)){
            $server=$servers[0]["serv"];
        }

        $prices = sqldb::safesql("SELECT `rune`,`$server` AS `srv` FROM `runes` ORDER BY `level` ASC, `rune` ASC");

    }else{

        $prices = sqldb::safesql("SELECT `rune` FROM `runes`");

    }

    echo '<div class="container">

            <div class="row">

                <form id="parea" class="form-inline">

                <div class="row">

                <div class="col-xs-5 col-xs-offset-3 form-group text-center">

                    <label for="Servor">Choisir un serveur :</label>

                    <select id="Servor" class="form-control">';

    foreach($servers as $srv){

        $server = $srv['serv'];

        if ($server!==$mybb->user['servor']){

            echo "<option value='$server'>$server</option>";

        }else{

            echo "<option selected='selected' value='$server'>$server</option>";

        }

    }

    echo '</select></div></div><div class="row text-center">';

    foreach ($prices as $key=>$row){

        $stat = $prices[$key]['rune'];

        $price = intval($prices[$key]['srv']);

        echo '<div class="form-group" style="margin:5px auto auto 15px;width: 249px">

                <img src="images/items/Rune '.$stat.'.png" style="width:34px">

                <label class="text-left" for="'.$stat.'" style="width:133px">Rune '.$stat.'</label>

                <input class="form-control" id="'.$stat.'" type="text" value="'.$price.'" style="width:74px">

              </div>';

    }

    echo '      

            </div>

            </form>

            <div class="row text-center" style="display: flex;align-items:center">

            <div class="col-xs-2 col-xs-offset-5">

                <button id="saverunesprices" class="btn btn-lg btn-primary" style="margin: 10px;">

                    <i class="glyphicon glyphicon-floppy-disk"></i>

                </button>

            </div>

            <div id="operation_result" class="col-xs-5">

            </div>

            </div>

        </div>';

    include(dirname(__FILE__,1).'/includes/bodyjs.php');

    echo '

<script src="js/runespricesupdate.js"></script>';}?>

</body>

</html>