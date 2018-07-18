/********************************    SIZE    *************************************/
var extent = [0, 0, 5000, 4000];//Superficie [minx, miny, maxx, maxy]
var projection = new ol.proj.Projection({
    code: 'EPSG:4326', //EPSG:4326 or EPSG:3857
    units: 'pixels',//'degrees', 'ft', 'm', 'pixels', 'tile-pixels', 'us-ft'
    extent: extent });
/**************************    BACKGROUND IMAGE    *******************************/
var dofusmap = new ol.layer.Image({
    source: new ol.source.ImageStatic   ({
        url: 'images/map/map.jpg',
        projection: projection,
        imageExtent: extent
    })
});
/********************************    VIEW    *************************************/
var dofusview = new ol.View({
    projection: projection,
    center: ol.extent.getCenter(extent),
    zoom: 2.5,
    minZoom : 1.5,
    maxZoom: 5.5
});
/**************************    CUSTOM CONTROLS    ********************************/
window.app = {};
var app = window.app;
/***************************    RESET BUTTON    **********************************/
app.reset_layers_app = function(opt_options) {
    var options = opt_options || {};
    var button = document.createElement('button');
    button.className = 'glyphicon glyphicon-refresh';
    var reset_layers = function() {
        for (var i = map.getLayers().a.length - 1; i > 0; i--) {
            map.removeLayer(map.getLayers().a[i]);
        }
        $('.ckb').prop('checked', false);
        ressources = [];
    };
    button.addEventListener('click', reset_layers, false);
    button.addEventListener('touchstart', reset_layers, false);
    var element = document.createElement('div');
    element.className = 'reset ol-unselectable ol-control';
    element.appendChild(button);
    ol.control.Control.call(this, {
        element: element,
        target: options.target
    });
};
ol.inherits(app.reset_layers_app, ol.control.Control);
/***************************    POSITION BOX    **********************************/
app.position = function(opt_options) {
    var options = opt_options || {};
    var element = document.createElement('div');
    element.className = 'text-center ol-unselectable ol-control';
    element.id = 'position';
    ol.control.Control.call(this, {
        element: element,
        target: options.target
    });
};
ol.inherits(app.position, ol.control.Control);
/***************************    EXPORT BUTTON    *********************************/
app.export = function(opt_options) {
    var options = opt_options || {};
    var button = document.createElement('button');
    button.className = 'glyphicon glyphicon-download-alt';
    var export_png = function() {
        map.once('postcompose', function(event) {
            var canvas = event.context.canvas;
            document.getElementById('export').href = canvas.toDataURL('image/png');
        });
        map.renderSync();
    };
    button.addEventListener('click', export_png, false);
    button.addEventListener('touchstart', export_png, false);
    var element = document.createElement('a');
    element.className = 'ol-unselectable ol-control';
    element.id = "export";
    element.download="dofusmap.png";
    element.appendChild(button);
    ol.control.Control.call(this, {
        element: element,
        target: options.target
    });
};
ol.inherits(app.export, ol.control.Control);
/*****************************    DRAW LAYER    **********************************/
var draw_source = new ol.source.Vector({wrapX: false});
var drawing = new ol.layer.Vector({
    source: draw_source
});
var drawn = [];
/********************************    MAP    **************************************/
var map = new ol.Map({
    controls:   [
        new ol.control.FullScreen(),
        new app.reset_layers_app(),
        new app.position(),
        new app.export()
    ],
    layers: [dofusmap,drawing],
    target: document.getElementById('map'),
    view: dofusview
});
/*************************    MAP PRE-LOADING   **********************************/
dofusmap.once('postcompose', function() {
    $('#map').addClass("loaded");
    $('#loadingbar').remove();
});
/*****************************    DRAWING   **************************************/
var draw;
function addInteraction() {
    var value = "LineString";
    if (value !== 'None') {
        draw = new ol.interaction.Draw({
            source: draw_source,
            type: value
        });
        map.addInteraction(draw);
    }
}
/**************************   GET RESSOURCES   ***********************************/
var dsource = [];
var dlayer = [];
var data_tooltip = [];
var ressources = [];
$(document).ready(function() {
    $(".ckb").bind('change mouseUp',function(checkbox){
        if ((checkbox.target.checked)&&!(checkbox.target.value in ressources)){
            $.ajax({
                type: "POST",
                url: "modules/map/mapgetinfo.php",
                data:{res: checkbox.target.value},
                success : display_resources
            });
        }else{
            map.removeLayer(dlayer[checkbox.target.value]);
            Object.keys(ressources).forEach(function (cell) {
                if ($.inArray(checkbox.target.value, data_tooltip[cell][1])!==-1){
                    if (data_tooltip[cell][1].length>1){
                        data_tooltip[cell][1].splice(data_tooltip[cell][1].indexOf(checkbox.target.value), 1 );
                    }else{
                        delete data_tooltip[cell];
                        delete ressources[cell];
                    }
                }
            })
        }
    });
});
/*******************************    VALUES    ************************************/
var pitchleft= 8;
var pitchbot = 14;
var cellheight = 24.85;
var cellwidth = 34.75;
/**************************    SHOW RESSOURCES   *********************************/
function display_resources(data) {
    var dmap = [];
    data[1].forEach(function (ans) {
        var cell_id = ans['id'];
        delete ans.id;
        var style = new ol.style.Style({
            stroke: new ol.style.Stroke({color: ans['color']}),
            fill: new ol.style.Fill({color: ans['color']}),
            text: new ol.style.Text({
                text: ans[data[0]].toString(),
                font: 'Calibri,sans-serif',
                scale: 1.5,
                fill: new ol.style.Fill({color: '#fff'})
            })
        });
        delete ans.color;
        var y = cell_id%160;
        var x = (cell_id - y)/160;
        ressources[cell_id] = new ol.Feature(new ol.geom.Polygon([[[x*cellwidth+pitchleft,y*cellheight+pitchbot],[(x+1)*cellwidth+pitchleft,y*cellheight+pitchbot],[(x+1)*cellwidth+pitchleft,(y+1)*cellheight+pitchbot],[x*cellwidth+pitchleft,(y+1)*cellheight+pitchbot],[x*cellwidth+pitchleft,y*cellheight+pitchbot]]]));
        ressources[cell_id].setStyle(style);
        dmap.push(ressources[cell_id]);
        if (cell_id in data_tooltip){
            data_tooltip[cell_id][1].push(data[0]);
        }else{
            data_tooltip[cell_id]=[];
            data_tooltip[cell_id][0] = "";
            data_tooltip[cell_id][1] = [data[0]];
            $.each(ans, function(index, value) {
                data_tooltip[cell_id][0] += index + " : " + value + "\n";
            });
        }
    });
    dsource[data[0]] = new ol.source.Vector({features: dmap});
    dlayer[data[0]] = new ol.layer.Vector({source: dsource[data[0]]});
    map.addLayer(dlayer[data[0]]);
}

$('#info').tooltip({
    animation: false,
    trigger: 'manual'
});
/*******************    SHOW TOOLTIPS AND POSITION   ***************************/
var current;
map.on('pointermove', function(evt) {
    var cursor = get_xy(evt);
    if (current!==cursor[0]){
        current = cursor[0];
        $('#position').html(cursor[1]);
    }
    if (cursor[0] in ressources) {
        $('#info').css({
            left: (evt.pixel[0] + 13) + 'px',
            top: (evt.pixel[1] ) + 'px'
        });
        var data = cursor[1]+" \n"+data_tooltip[cursor[0]][0];
        $('#info').tooltip('hide')
            .attr('data-original-title', data)
            .tooltip('fixTitle')
            .tooltip('show');
    } else {
        $('#info').tooltip('hide');
    }
});
/*********************    GET CELL ID FROM EVENT   ****************************/
function get_xy(e){
    var x = Math.round(e.coordinate[0])-pitchleft;
    var y = Math.round(e.coordinate[1])-pitchbot;
    var dx = Math.round((x - x % cellwidth)/cellwidth);
    var dy = Math.round((y - y % cellheight)/cellheight);
    var cell = dx*160+dy;
    var dox = dx - 93;
    var doy = 60 - dy;
    var pos = '['+dox+','+doy+']';
    return [cell,pos];
}
/*****************************    DRAWING   ***********************************/
map.on('click', function (event) {
    if (map.getLayers().a.indexOf(drawing)===-1){
        draw_source = new ol.source.Vector({wrapX: false});
        drawing = new ol.layer.Vector({
            source: draw_source
        });
        map.addLayer(drawing);
    }
    var cell = get_xy(event);
    var cursor = get_cell_center(cell[0]);
    var feature = new ol.Feature({
        geometry: new ol.geom.Point(cursor),
        name: cell[0]
    });
    if ($.inArray(cell[0],drawn)===-1){
        draw_source.addFeature(feature);
        drawn.push(cell[0]);
    }else{
        var temp = draw_source.getFeatures().filter(function( obj ) {
            return obj.T.name == cell[0];
        })[0];
        draw_source.removeFeature(temp);
        drawn.splice(drawn.indexOf(cell[0]),1)
    }
});

function get_cell_center(cell){
    var y = cell%160;
    var x = (cell-y)/160;
    return [x*cellwidth+pitchleft+cellwidth/2,y*cellheight+pitchbot+cellheight/2]
}