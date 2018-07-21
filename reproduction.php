<?php include(dirname(__FILE__,1).'/includes/inhead.php'); ?>
</head>
<body>
<?php include(dirname(__FILE__,1).'/includes/menu.php'); ?>
<div id="loadingbar" style="height: 100%">
        <div class="progress" style="position: absolute;top: 50%;left:25%; width: 50%;">
            <div class="progress-bar loading-color progress-bar-striped active" role="progressbar"
                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                Chargement en cours ...
            </div>
    </div>
</div>
<div class="container preload">
    <div class="row">
        <div id="source" class="well well-sm text-center" style="min-width: 970px;">
            <button class="btn btn-large btn-danger glyphicon glyphicon-repeat" data-toggle='tooltip' data-placement='bottom' data-original-title="Reset" style="height: 55px;width: 55px;margin: 2px 2px 4px 2px;"></button>
            <span><img class='img-rounded' src='images/icons/mounts/None.png' alt="None" data-toggle='tooltip' data-placement='bottom' data-original-title="Pas de parent :-(" style='max-width: 55px'></span>
            <?php
            $query = "SELECT `name`,`Generation`,`Taux_apprentisage` from `arides` WHERE `name` NOT LIKE '%plumes%' AND `name` NOT LIKE '%armure%' AND `name` NOT LIKE '%Sauvage%' ORDER BY `Generation`,`name` ASC";
            $dds = sqldb::safesql($query);
            $auto_complete = [['name'=>"None",'value'=>'N']];
            foreach ($dds as $key=>$dd){
                $name = $dd['name'];
                array_push($object,['name'=>$name,'value'=>preg_replace('/[\p{Ll}]*\s*/', '', strtr($name, ["Dragodinde "=>"","é"=>"","è"=>""]))]);
                echo "<span><img class='img-rounded' src=\"images/any2/$name 200.png\" alt=\"$name\" draggable=\"true\" ondragstart=\"drag(event)\" data-toggle='tooltip' data-placement='bottom' data-original-title=\"$name\" style='max-width: 65px'></span>";
            }
            echo "<script>
                         var autocomplete = ".json_encode($auto_complete).";
                         var check = ".json_encode($dds).";
                  </script>";
            ?>
        </div>
    </div>
    <div class="row">
        <div id="tree" class="well text-center" style="margin-top:-20px;min-height: 433px;min-width: 970px;">
            <div class="row">
                <div id="father" class="col-xs-6" data-parent="0">
                    <div class="row text-center" data-gen="1">
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 2px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 0px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 2px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 9px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 2px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 0px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 2px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 0px -1px 0px"></span>
                    </div>
                    <div class="row text-center">
                        <span><img src="images/icons/mounts/link_tree_small.png" style="width:60px; margin: 0 46px 0 0px"></span>
                        <span><img src="images/icons/mounts/link_tree_small.png" style="width:60px; margin: 0 55px 0 0px"></span>
                        <span><img src="images/icons/mounts/link_tree_small.png" style="width:60px; margin: 0 46px 0 0px"></span>
                        <span><img src="images/icons/mounts/link_tree_small.png" style="width:60px; margin: 0 1px 0 0px"></span>
                    </div>
                    <div class="row text-center" data-gen="3">
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px 42px -2px 0"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px 52px -2px 0"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px 42px -2px 0"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px 0px -2px 0"></span>
                    </div>
                    <div class="row text-center">
                        <span><img src="images/icons/mounts/link_tree_small.png" style="margin: 0 108px 0 0"></span>
                        <span><img src="images/icons/mounts/link_tree_small.png"></span>
                    </div>
                    <div class="row text-center" data-gen="6">
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px 161px -2px 0"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px -1px -2px 0"></span>
                    </div>
                    <div class="row text-center">
                        <span><img src="images/icons/mounts/link_tree_medium.png"></span>
                    </div>
                    <div class="row text-center" data-gen="10" style="vertical-align: middle">
                        <span style="position: relative;top:9px">
                        <input type="checkbox" class="pred_gen" data-toggle="toggle" data-on="Prédisposée" data-off="Prédisposée" data-onstyle="success" data-offstyle="danger">
                        </span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px 120px -21px 0"></span>
                    </div>
                </div>
                <div id="mother" class="col-xs-6" data-parent="1">
                    <div class="row text-center" data-gen="1">
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 2px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 0px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 2px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 9px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 2px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 0px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 2px -1px 0px"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 50px;margin: -1px 0px -1px 0px"></span>
                    </div>
                    <div class="row text-center">
                        <span><img src="images/icons/mounts/link_tree_small.png" style="width:60px; margin: 0 46px 0 0px"></span>
                        <span><img src="images/icons/mounts/link_tree_small.png" style="width:60px; margin: 0 55px 0 0px"></span>
                        <span><img src="images/icons/mounts/link_tree_small.png" style="width:60px; margin: 0 46px 0 0px"></span>
                        <span><img src="images/icons/mounts/link_tree_small.png" style="width:60px; margin: 0 1px 0 0px"></span>
                    </div>
                    <div class="row text-center" data-gen="3">
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: -0px 42px -2px 0"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: -0px 52px -2px 0"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: -0px 42px -2px 0"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: -0px 0px -2px 0"></span>
                    </div>
                    <div class="row text-center">
                        <span><img src="images/icons/mounts/link_tree_small.png" style="margin: 0 108px 0 0"></span>
                        <span><img src="images/icons/mounts/link_tree_small.png"></span>
                    </div>
                    <div class="row text-center" data-gen="6">
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px 161px -2px 0"></span>
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px -1px -2px 0"></span>
                    </div>
                    <div class="row text-center">
                        <span><img src="images/icons/mounts/link_tree_medium.png"></span>
                    </div>
                    <div class="row text-center" data-gen="10">
                        <span><img class="dd_img" src="images/icons/mounts/inventoryEmptySlot.png" ondrop="drop(event)" ondragover="drag_OK(event)" style="width: 64px;margin: 0px 0px -2px 120px"></span>
                        <span>
                        <input type="checkbox" class="pred_gen" data-toggle="toggle" data-on="Prédisposée" data-off="Prédisposée" data-onstyle="success" data-offstyle="danger">
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                <span><img id="last_link" src="images/icons/mounts/link_tree_large.png"></span>
            </div>
            <div class="col-xs-12 text-center">
                <img id="dd_query_go" src="images/icons/mounts/inventoryEmptySlotUnknown.png" style="width: 64px;margin: -1px 0 0 0">
                <input id="dd_finder" class="form-control" type="text" style="width: 20%;position:absolute;bottom: 143px;left: 40%;">
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <table id="probas_content" class="table table-striped table-hover table-responsive" style="margin-top: 0px;">
        </table>
    </div>
</div>
<?php include(dirname(__FILE__,1).'/includes/bodyjs.php');?>
<script src="js/reproduction.js"></script>
</body>
</html>