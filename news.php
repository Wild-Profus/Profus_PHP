<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>

    <title>News Dofus 2 - Profus</title>

    <meta name="title" content="News Dofus 2 - Profus">

    <meta name="description" content="Simple page regroupant le devtracker et les flux rss Dofus 2.0">

    <link rel="stylesheet" href="css/index.css">

</head>

<body>

<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>

            <div class="container-fluid">
                
                <h3 class="text-center"><a href="https://www.ankama.com/gamakna#project/3f0bfabc-7bfc-495a-a6e8-4a62ebde4c44/view/home/view/gamakna-fr">Lecture GAMAKNA sur PC depuis le site officiel ANKAMA</a></h1>

                <div class="row">
                    <div class ='col-xs-12'>                        
                        <?php @include(dirname(__FILE__,1).'/modules/news/announce.html'); ?>
                    </div>
                </div>

                <h1 class="text-center">Dernières nouvelles de Dofus</h1>

                <div class="row">

                    <div class="col-xs-4 col-xs-offset-1">

                        <label>News</label>

                        <?php include(dirname(__FILE__,1).'/modules/news/news.html'); ?>

                    </div>

                    <div class="col-xs-3">

                        <label>Mises à jour</label>

                        <?php include(dirname(__FILE__,1).'/modules/news/changelog.html'); ?>

                    </div>

                    <div class="col-xs-4">

                        <label>Devblog</label>

                        <?php include(dirname(__FILE__,1).'/modules/news/devblog.html'); ?>
                       
                    </div>

                </div>

            </div>

            <div class="container-fluid">

                <h1 class="text-center">AnkaTracker du Forum Dofus !</h1>

                <div class="row">
                    <div class ='col-xs-12'>
                        <table id="newsTable" class="table table-striped table-hover table-responsive" style="margin-top: 6px;">

                        <?php
                        $news = sqldb::safesql("SELECT `posted`,`author`,`title`,`post` FROM `1news` WHERE usefull<>0 and announce=0 ORDER BY `posted` DESC LIMIT 20");

                        foreach ($news as $key=>$new){
        
                            echo "<div class='row-fluid topic'>
        
                                    <div class='col-xs-12 info'>
        
                                        <div class='author'>".$new["author"]."</div>
        
                                        <div class='date'>".$new["posted"]."</div>
        
                                        <div class='post_title'>".$new["title"]."</div>
        
                                    </div>
        
                                    <div class='col-xs-12 post'>".$new["post"]."                            
                                    </div>
        
                                </div>";
        
                        }
                        ?>
                        </table>
                    </div>
                </div>    
            </div>

<?php

include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="js/index.js"></script>
<script src="js/news.js"></script>

</body>

</html>