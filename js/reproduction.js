var dragosProbas;
$(document).ready(function(){
    $(window).on('resize',function () {
        var width = document.getElementById("tree").offsetWidth;
        document.getElementById('last_link').style.width = 489.5 * width/970 +'px';
    }).resize();
    var dd_img = $('#source > span > img');
    $('#source').bind('contextmenu', function(){ return false });
    dd_img.mousedown(function(event){
        dd_img.on('mouseup mousemove', function handler(evt) {
            if (evt.type === 'mouseup') {
                switch (event.which){
                    case 1:
                        add_parent(event.target.alt,event.target.src);
                        break;
                    case 2:
                        break;
                    case 3:
                        event.preventDefault();
                        var father_free = document.getElementById('father').getElementsByTagName('img');
                        var mother_free = document.getElementById('mother').getElementsByTagName('img');
                        if (father_free[0].alt===undefined || father_free[0].alt===""){
                            for(var i = 0;i < father_free.length; i++){
                                if (father_free[i].className==='dd_img'){
                                    father_free[i].alt = event.target.alt;
                                    father_free[i].src = event.target.src;
                                }
                            }
                        }else if (mother_free[0].alt===undefined || mother_free[0].alt===""){
                            for(var j = 0;j < father_free.length; j++){
                                if (mother_free[j].className==='dd_img'){
                                    mother_free[j].alt = event.target.alt;
                                    mother_free[j].src = event.target.src;
                                }
                            }
                        }
                        break;
                }
            } else {

            }
            dd_img.off('mouseup mousemove', handler);
        });
    });
    $('#source').on('click','button', function () {
        var images = Array.prototype.slice.apply(document.getElementById('tree').getElementsByClassName('dd_img'));
        for (var i = 0; i < images.length; i++) {
            images[i].src="images/icons/mounts/inventoryEmptySlot.png";
        }
    });
    $('#tree').on('click','.dd_img',function (img) {
        img.target.src = "images/icons/mounts/inventoryEmptySlot.png";
        img.target.alt = "";
    });
    $("#dd_finder").autocomplete({
        source: autocomplete,
        select: function( event, ui ) {
            add_parent(ui.item.name,'images/any2/' + ui.item.name + ' 200.png');
            document.getElementById('dd_finder').value = "";
            return false;
        }
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<div><img src='images/any2/"+item.name+" 200.png' style='width: 30px;'>&nbsp;&nbsp;&nbsp;"+ item.name + "</div>" )
            .appendTo( ul );
    };
    $('#dd_query_go').on('click',function () {
        var images = Array.prototype.slice.apply(document.getElementById('tree').getElementsByClassName('dd_img'));
        var empties = [];
        var lineage = [[],[]];
        for (var i = 0; i < images.length; i++) {
            if (images[i].src.indexOf("images/icons/mounts/inventoryEmptySlot.png") !== -1) {
                empties.push(images[i]);
            }else{
                if(images[i].alt!=='None'||images[i].src!=="images/icons/mounts/inventoryEmptySlot.png"){
                    var name = images[i].alt.replace('É','E');
                    var gsw = images[i].parentNode.parentNode.dataset.gen;
                    var parent = images[i].parentNode.parentNode.parentNode.dataset.parent;
                    lineage[parent].push({name:name,gsw:gsw});
                }
            }
        }
        updateProba(lineage);
    });
    $('#tree').on('change','.pred_gen',function (event) {
        if (event.target.checked==1){
            event.target.parentNode.parentNode.parentNode.dataset.gen = 20;
        }else{
            event.target.parentNode.parentNode.parentNode.dataset.gen = 10;
        }
    })
});
/******************************** DRAG N DROP ***********************************/
function drag_OK(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("alt", ev.target.alt);
    ev.dataTransfer.setData("src", ev.target.src);
}

function drop(ev) {
    ev.preventDefault();
    ev.target.src = ev.dataTransfer.getData("src");
    ev.target.alt = ev.dataTransfer.getData("alt");
}
function add_parent(name,src){
    var images = Array.prototype.slice.apply(document.getElementById('tree').getElementsByClassName('dd_img'));
    var empties = [];
    for (var i = 0; i < images.length; i++) {
        if (images[i].src.indexOf("images/icons/mounts/inventoryEmptySlot.png") !== -1) {
            empties.push(images[i]);
        }
    }
    if(empties.length>0){
        if((name=="None")&&(empties.length>2)){
            if (((empties.length%2==0)&&(empties.length>16))||((empties.length%2==1)&&(empties.length<16))){
                empties[0].src = 'images/icons/mounts/None.png';
                empties[0].alt = 'None';
                empties[1].src = 'images/icons/mounts/None.png';
                empties[1].alt = 'None';
            }else{
                empties[0].src = src;
                empties[0].alt = name;
            }
        }else{
            empties[0].src = src;
            empties[0].alt = name;
        }
    }
}

var COMBINATIONS = {
    // monocolor -> bicolor
    'Dragodinde Rousse':	{'Dragodinde Amande': 'Dragodinde Amande et Rousse',	  'Dragodinde Dorée': 	'Dragodinde Dorée et Rousse',	   'Dragodinde Indigo': 'Dragodinde Indigo et Rousse',  'Dragodinde Ebène':  'Dragodinde Ebène et Rousse',	'Dragodinde Pourpre': 'Dragodinde Pourpre et Rousse', 'Dragodinde Orchidée': 'Dragodinde Orchidée et Rousse',	 'Dragodinde Ivoire':   'Dragodinde Ivoire et Rousse',	  'Dragodinde Turquoise': 'Dragodinde Turquoise et Rousse',	'Dragodinde Emeraude':  'Dragodinde Emeraude et Rousse',	 'Dragodinde Prune':     'Dragodinde Prune et Rousse'},
    'Dragodinde Amande':	{'Dragodinde Rousse': 'Dragodinde Amande et Rousse',	  'Dragodinde Dorée': 	'Dragodinde Amande et Dorée',	   'Dragodinde Indigo': 'Dragodinde Amande et Indigo',  'Dragodinde Ebène':  'Dragodinde Amande et Ebène',	'Dragodinde Pourpre': 'Dragodinde Amande et Pourpre', 'Dragodinde Orchidée': 'Dragodinde Amande et Orchidée',	 'Dragodinde Ivoire':   'Dragodinde Amande et Ivoire',	  'Dragodinde Turquoise': 'Dragodinde Amande et Turquoise',	'Dragodinde Emeraude':  'Dragodinde Amande et Emeraude',	 'Dragodinde Prune':     'Dragodinde Prune et Amande'},
    'Dragodinde Dorée' :	{'Dragodinde Rousse': 'Dragodinde Dorée et Rousse',	  'Dragodinde Amande': 'Dragodinde Amande et Dorée',	   'Dragodinde Indigo': 'Dragodinde Dorée et Indigo',   'Dragodinde Ebène':  'Dragodinde Dorée et Ebène',	    'Dragodinde Pourpre': 'Dragodinde Dorée et Pourpre',	 'Dragodinde Orchidée': 'Dragodinde Dorée et Orchidée',	 'Dragodinde Ivoire':   'Dragodinde Dorée et Ivoire',	  'Dragodinde Turquoise': 'Dragodinde Dorée et Turquoise',	'Dragodinde Emeraude':  'Dragodinde Dorée et Emeraude',	 'Dragodinde Prune':     'Dragodinde Prune et Dorée'},
    'Dragodinde Indigo':	{'Dragodinde Rousse': 'Dragodinde Indigo et Rousse',	  'Dragodinde Amande': 'Dragodinde Amande et Indigo',   'Dragodinde Dorée':  'Dragodinde Dorée et Indigo',   'Dragodinde Ebène':  'Dragodinde Ebène et Indigo',	'Dragodinde Pourpre': 'Dragodinde Indigo et Pourpre', 'Dragodinde Orchidée': 'Dragodinde Indigo et Orchidée',	 'Dragodinde Ivoire':   'Dragodinde Indigo et Ivoire',	  'Dragodinde Turquoise': 'Dragodinde Indigo et Turquoise',	'Dragodinde Emeraude':  'Dragodinde Emeraude et Indigo',	 'Dragodinde Prune':     'Dragodinde Prune et Indigo'},
    'Dragodinde Ebène' :	{'Dragodinde Rousse': 'Dragodinde Ebène et Rousse',	  'Dragodinde Amande': 'Dragodinde Amande et Ebène',	   'Dragodinde Dorée':  'Dragodinde Dorée et Ebène',	   'Dragodinde Indigo': 'Dragodinde Ebène et Indigo',	'Dragodinde Pourpre': 'Dragodinde Ebène et Pourpre',	 'Dragodinde Orchidée': 'Dragodinde Ebène et Orchidée',	 'Dragodinde Ivoire':   'Dragodinde Ebène et Ivoire',	  'Dragodinde Turquoise': 'Dragodinde Ebène et Turquoise',	'Dragodinde Emeraude':  'Dragodinde Ebène et Emeraude',	 'Dragodinde Prune':     'Dragodinde Prune et Ebène'},
    'Dragodinde Pourpre':	{'Dragodinde Rousse': 'Dragodinde Pourpre et Rousse',  'Dragodinde Amande': 'Dragodinde Amande et Pourpre',  'Dragodinde Dorée':  'Dragodinde Dorée et Pourpre',  'Dragodinde Indigo': 'Dragodinde Indigo et Pourpre',	'Dragodinde Ebène':   'Dragodinde Ebène et Pourpre',	 'Dragodinde Orchidée': 'Dragodinde Orchidée et Pourpre', 'Dragodinde Ivoire':   'Dragodinde Ivoire et Pourpre',	  'Dragodinde Turquoise': 'Dragodinde Turquoise et Pourpre',	'Dragodinde Emeraude':  'Dragodinde Emeraude et Pourpre', 'Dragodinde Prune':     'Dragodinde Prune et Pourpre'},
    'Dragodinde Orchidée':	{'Dragodinde Rousse': 'Dragodinde Orchidée et Rousse', 'Dragodinde Amande': 'Dragodinde Amande et Orchidée', 'Dragodinde Dorée':  'Dragodinde Dorée et Orchidée', 'Dragodinde Indigo': 'Dragodinde Indigo et Orchidée',	'Dragodinde Ebène':   'Dragodinde Ebène et Orchidée', 'Dragodinde Pourpre':  'Dragodinde Orchidée et Pourpre', 'Dragodinde Ivoire':   'Dragodinde Ivoire et Orchidée',	  'Dragodinde Turquoise': 'Dragodinde Turquoise et Orchidée','Dragodinde Emeraude':  'Dragodinde Emeraude et Orchidée','Dragodinde Prune':     'Dragodinde Prune et Orchidée'},
    'Dragodinde Ivoire':	{'Dragodinde Rousse': 'Dragodinde Ivoire et Rousse',	  'Dragodinde Amande': 'Dragodinde Amande et Ivoire',   'Dragodinde Dorée':  'Dragodinde Dorée et Ivoire',   'Dragodinde Indigo': 'Dragodinde Indigo et Ivoire',	'Dragodinde Ebène':   'Dragodinde Ebène et Ivoire',	 'Dragodinde Pourpre':  'Dragodinde Ivoire et Pourpre',	 'Dragodinde Orchidée': 'Dragodinde Ivoire et Orchidée',   'Dragodinde Turquoise': 'Dragodinde Ivoire et Turquoise',	'Dragodinde Emeraude':  'Dragodinde Emeraude et Ivoire',	 'Dragodinde Prune':     'Dragodinde Prune et Ivoire'},
    'Dragodinde Turquoise': {'Dragodinde Rousse': 'Dragodinde Turquoise et Rousse','Dragodinde Amande': 'Dragodinde Amande et Turquoise','Dragodinde Dorée':  'Dragodinde Dorée et Turquoise','Dragodinde Indigo': 'Dragodinde Indigo et Turquoise','Dragodinde Ebène':   'Dragodinde Ebène et Turquoise','Dragodinde Pourpre':  'Dragodinde Turquoise et Pourpre','Dragodinde Orchidée': 'Dragodinde Turquoise et Orchidée','Dragodinde Ivoire':    'Dragodinde Ivoire et Turquoise',  'Dragodinde Emeraude':  'Dragodinde Emeraude et Turquoise','Dragodinde Prune':    'Dragodinde Prune et Turquoise'},
    'Dragodinde Emeraude':	{'Dragodinde Rousse': 'Dragodinde Emeraude et Rousse', 'Dragodinde Amande': 'Dragodinde Amande et Emeraude', 'Dragodinde Dorée':  'Dragodinde Dorée et Emeraude', 'Dragodinde Indigo': 'Dragodinde Emeraude et Indigo',	'Dragodinde Ebène':   'Dragodinde Ebène et Emeraude', 'Dragodinde Pourpre':  'Dragodinde Emeraude et Pourpre', 'Dragodinde Orchidée': 'Dragodinde Emeraude et Orchidée', 'Dragodinde Ivoire':    'Dragodinde Emeraude et Ivoire',	'Dragodinde Turquoise': 'Dragodinde Emeraude et Turquoise','Dragodinde Prune':    'Dragodinde Prune et Emeraude'},
    'Dragodinde Prune':	    {'Dragodinde Rousse': 'Dragodinde Prune et Rousse',	  'Dragodinde Amande': 'Dragodinde Prune et Amande',	   'Dragodinde Dorée':  'Dragodinde Prune et Dorée',	   'Dragodinde Indigo': 'Dragodinde Prune et Indigo',	'Dragodinde Ebène':   'Dragodinde Prune et Ebène',	 'Dragodinde Pourpre':  'Dragodinde Prune et Pourpre',	 'Dragodinde Orchidée': 'Dragodinde Prune et Orchidée',	  'Dragodinde Ivoire':    'Dragodinde Prune et Ivoire',	    'Dragodinde Turquoise': 'Dragodinde Prune et Turquoise',	  'Dragodinde Emeraude': 'Dragodinde Prune et Emeraude'},
    // bicolor -> monocolor
    'Dragodinde Amande et Rousse':      {'Dragodinde Amande et Dorée':     'Dragodinde Indigo', 'Dragodinde Ebène et Indigo': 'Dragodinde Pourpre'},
    'Dragodinde Dorée et Rousse':       {'Dragodinde Amande et Dorée':     'Dragodinde Indigo', 'Dragodinde Ebène et Indigo': 'Dragodinde Orchidée'},
    'Dragodinde Amande et Dorée':       {'Dragodinde Amande et Rousse':    'Dragodinde Indigo', 'Dragodinde Dorée et Rousse': 'Dragodinde Ebène'},
    'Dragodinde Ebène et Indigo':       {'Dragodinde Amande et Rousse':    'Dragodinde Pourpre','Dragodinde Dorée et Rousse': 'Dragodinde Orchidée'},
    'Dragodinde Indigo et Pourpre':     {'Dragodinde Orchidée et Pourpre': 'Dragodinde Ivoire'},
    'Dragodinde Ebène et Orchidée':     {'Dragodinde Orchidée et Pourpre': 'Dragodinde Turquoise'},
    'Dragodinde Orchidée et Pourpre':   {'Dragodinde Indigo et Pourpre':   'Dragodinde Ivoire', 'Dragodinde Ebène et Orchidée': 'Dragodinde Turquoise'},
    'Dragodinde Ivoire et Pourpre':     {'Dragodinde Ivoire et Turquoise': 'Dragodinde Emeraude'},
    'Dragodinde Turquoise et Orchidée': {'Dragodinde Ivoire et Turquoise': 'Dragodinde Prune'},
    'Dragodinde Ivoire et Turquoise':   {'Dragodinde Ivoire et Pourpre':   'Dragodinde Emeraude','Dragodinde Turquoise et Orchidée': 'Dragodinde Prune'}
};

function updateProba(data){
    var fatherTree = data[0];
    var motherTree = data[1];
    var fatherType = [];
    var fatherSum = 0;
    for(var i = 0 ; i < fatherTree.length; i++){
        var a = fatherTree[i];
        if (fatherType[a['name']] === undefined)
            fatherType[a['name']] = 0;
        fatherType[a['name']] += parseInt(a['gsw']);
        fatherSum += parseInt(a['gsw']);
    }
    var motherType = [];
    var motherSum = 0;
    for(var j = 0 ; j < motherTree.length; j++){
        var b = motherTree[j];
        if (motherType[b['name']] === undefined)
            motherType[b['name']] = 0;
        motherType[b['name']] += parseInt(b['gsw']);
        motherSum += parseInt(b['gsw']);
    }
    var probas = [];
    for(var fathertype in fatherType){
        for(var mothertype in motherType){

            var p1 = fatherType[fathertype] / fatherSum;
            var p2 = motherType[mothertype] / motherSum;
            var p = p1 * p2;
            var father_object = check.filter(function (obj) {
                return obj.name === fathertype;
            })[0];
            var gcw1 = 100 * Math.floor(2000/parseInt(father_object.Taux_apprentisage))/100 / (2 - parseInt(father_object.Generation) % 2);
            var mother_object = check.filter(function (obj) {
                return obj.name === mothertype;
            })[0];
            var gcw2 = 100 * Math.floor(2000/parseInt(mother_object.Taux_apprentisage))/100 / (2 - parseInt(mother_object.Generation) % 2);
            var gcwmix = 0;

            var mix = COMBINATIONS[fathertype];

            if (typeof (mix) !== "undefined" && typeof (mix[mothertype]) !== "undefined"){

                var mix_object = check.filter(function (obj) {
                    return obj.name === mix[mothertype];
                })[0];
                gcwmix = 0.5 * 100 * Math.floor(2000/parseInt(mix_object.Taux_apprentisage))/100 / (2 - parseInt(mix_object.Generation) % 2);
            }
            if (probas[fathertype] === undefined)
                probas[fathertype] = 0;
            probas[fathertype] += p * gcw1 / (gcw1 + gcw2 + gcwmix);

            if (probas[mothertype] === undefined)
                probas[mothertype] = 0;
            probas[mothertype] += p * gcw2 / (gcw1 + gcw2 + gcwmix);

            if (gcwmix > 0) {
                if (probas[mix[mothertype]] === undefined)
                    probas[mix[mothertype]] = 0;
                probas[mix[mothertype]] += p * gcwmix / (gcw1 + gcw2 + gcwmix);
            }
        }
    }
    var tableProba = [];
    for(var proba in probas){
        tableProba.push({name:proba, prob: Math.round(probas[proba] * 10000) / 100, img:'<img style="width: 64px;" src="images/any2/'+proba+' 200.png" alt="'+proba+'"/>'});
    }
    if (dragosProbas!== undefined){
        dragosProbas.destroy();
        $('#probas_content').html('');
    }
    dragosProbas = $('#probas_content').DataTable( {
        data: tableProba,
        columns: [
            { "title": "",data:'img', "orderable": false},
            { "title": "Race",data:'name', "orderable": false},
            { "title": "Probabilité (%)",data:'prob'}
        ],
        ordering: true,
        bInfo: false,
        "bDeferRender": true,
        "order": [[ 2, "desc" ]],
        "aoColumnDefs": [
            { "sClass": "text-center", "aTargets": [0,1,2]}
        ],
        paging: false,
        "language": {
            "lengthMenu": "MENU",
            "zeroRecords": "Ajouter des Dragodindes dans les arbres et cliquer sur le ?.",
            "info": "Page PAGE sur PAGES",
            "infoEmpty": "Pas d'entrée correpsondante dans la base.",
            "infoFiltered": "(parmi MAX)"
        },
        "sDom": 'rt',
        "pagingType": "numbers",
        "initComplete": function (oSettings) {
            document.getElementById("probas_content").scrollIntoView(false);
        }
    });
}