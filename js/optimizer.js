$(document).ready(function(){

    $('body').on('click', function (e) {

        $('[data-toggle="popover"]').each(function () {

            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {

                $(this).popover('hide');

            }

        });

    });

    $('#class').on('click',function (image) {

        var classe = image.target.alt;

        $.ajax({

            type: "POST",

            url: "modules/optimizer/get_spells.php",

            data:{class: classe},

            success : show_spells

        });

        document.getElementById("symbol_img").src='images/icons/symbols/'+classe.toLowerCase()+'.png';

        document.getElementById('tour_de_jeu').innerHTML='';

    });

    $('#spells').on('click',function (image) {

        var spell = image.target.alt;

        if ($('h4:contains('+spell+')').length<image.target.dataset.limit){

            $.ajax({

                type: "POST",

                url: "modules/optimizer/get_spell.php",

                data:{spell: spell},

                success : add_spell

            });

        }

    });

    $('#tour_de_jeu').on('click','.glyphicon-remove',function (button) {

        button.target.parentNode.parentNode.parentNode.parentNode.remove();

    });

    $('#itemSelect').on('click','button',function () {

        $(this).toggleClass("active");

    });

    $('#weaponSelect').on('click','button',function () {

        $(this).toggleClass("active");

    });

    $('#opti_go').on('click',function () {

        var lvlMin = document.getElementById('lvlMin').value;

        var lvlMax = document.getElementById('lvlMax').value;

        var types = [];

        [].forEach.call(document.getElementById('itemSelect').getElementsByClassName('active'), function (el) {

            el.firstChild.dataset.type.split("-").forEach(function (id) {

                types.push(id);

            });

        });

        [].forEach.call(document.getElementById('weaponSelect').getElementsByClassName('active'), function (el) {

            el.firstChild.dataset.type.split("-").forEach(function (id) {

                types.push(id);

            });

        });
        if (types.length === 0){
            types = [1,9,10,11,16,17,23,82,151];
        }

        var must = [];

        [].forEach.call(document.getElementById('selector').getElementsByClassName('must'), function (el) {

            must.push(el.dataset.effectid);

        });

        var mustEq = [];

        [].forEach.call(document.getElementById('selector').getElementsByClassName('mustEq'), function (el) {

            mustEq.push(el.dataset.effectid);

        });

        var see = [];

        [].forEach.call(document.getElementById('selector').getElementsByClassName('see'), function (el) {

            see.push(el.dataset.effectid);

        });

        var seeEq = [];

        [].forEach.call(document.getElementById('selector').getElementsByClassName('seeEq'), function (el) {

            seeEq.push(el.dataset.effectid);

        });

        var checkboxes = document.getElementById('selector').getElementsByTagName('input');

        for (var i=0; i<checkboxes.length; i++) {

            if (checkboxes[i].checked) {

                mustEq.push(checkboxes[i].dataset.effectid);

            }

        }

        $.ajax({

            type: "POST",

            url: "modules/optimizer/get_panel.php",

            data: { lvlMin: lvlMin,

                    lvlMax: lvlMax,

                    types: types,

                    must: must,

                    mustEq: mustEq,

                    see: see,

                    seeEq: seeEq},

            success: display_results,

            dataType: "json"

        });

    });

    $('#selector').bind('contextmenu', function(){ return false });

    $('#selector > div > div > img').mousedown(function(evt){

        switch (evt.which){

            case 1:

                if(evt.target.className === 'img-rounded'){

                    evt.target.className = "img-rounded must";

                }else if(evt.target.className === 'img-rounded must'){

                    if(typeof evt.target.dataset.eq !== 'undefined'){

                        evt.target.className = "img-rounded mustEq";

                    }else{

                        evt.target.className = "img-rounded see";

                    }

                }else if(evt.target.className === 'img-rounded mustEq'){

                    evt.target.className = "img-rounded see";

                }else if(evt.target.className === 'img-rounded see'){

                    if(typeof evt.target.dataset.eq !== 'undefined'){

                        evt.target.className = "img-rounded seeEq";

                    }else{

                        evt.target.className = "img-rounded";

                    }

                }else{

                    evt.target.className = "img-rounded";

                }

                break;

            case 2:

                break;

            case 3:

                if(evt.target.className === 'img-rounded'){

                    if(typeof evt.target.dataset.eq !== 'undefined'){

                        evt.target.className = "img-rounded seeEq";

                    }else{

                        evt.target.className = "img-rounded see";

                    }

                }else if(evt.target.className === 'img-rounded seeEq'){

                    evt.target.className = "img-rounded see";

                }else if(evt.target.className === 'img-rounded see'){

                    if(typeof evt.target.dataset.eq !== 'undefined'){

                        evt.target.className = "img-rounded mustEq";

                    }else{

                        evt.target.className = "img-rounded must";

                    }

                }else if(evt.target.className === 'img-rounded mustEq'){

                    evt.target.className = "img-rounded must";

                }else{

                    evt.target.className = "img-rounded";

                }

                break;

        }

    });

    $('#lvlMin').on('input',function () {

        document.getElementById('lvlMin').value = Math.min(Math.max(parseInt(document.getElementById('lvlMin').value),0),200);

    });

    $('#lvlMax').on('input',function () {

        document.getElementById('lvlMax').value = Math.min(Math.max(parseInt(document.getElementById('lvlMax').value),0),200);

    });

    $('#results').on('click', 'td',function (cell) {

        var row = cell.target.parentNode;

        var item = row.firstChild.innerHTML;

        if (row.dataset.content===undefined){

            $.ajax({

                type: "POST",

                url: "modules/optimizer/get_item.php",

                data: { item: item},

                success: function(data){

                    row = row.firstChild;

                    row.dataset.title = data[0];

                    row.dataset.content = data[1];

                    row.dataset.toggle = "popover";

                    row.dataset.trigger = "focus";

                    row.dataset.container = "body";

                    row.dataset.placement = "left";

                    $(row).popover({

                        html : true

                    }).popover('toggle');

                },

                dataType: "json"

            });

        }else{

            $(row).popover('toggle');

        }

    })

});



function show_spells(data) {

    $('#class_spells').html(data);

    $("body").tooltip({ selector: '[data-toggle=tooltip]' });

}



var spells=[];

function add_spell(data){

    var turn = document.getElementById('tour_de_jeu').childNodes;

    e = document.createElement('div');

    e.className = 'col-xs-3';

    e.innerHTML = data;

    document.getElementById('tour_de_jeu').appendChild(e);

}



var results;

function display_results(data){

    console.log(data);

    if (typeof results!=='undefined'){

        results.destroy();

        document.getElementById('results').innerHTML = '';

    }

    var columns = [];

    var target = [];

    var k = 0;

    if(data!==false){

        Object.keys(data[0]).forEach(function (column) {

            columns.push({ "title": column, data: column });

            target.push(k);

            k++;

        });

    }else{

        data = [];

    }

    results = $('#results').DataTable( {

        data: data,

        columns: columns,

        paging: false,

        bInfo: false,

        ordering : false,

        "aoColumnDefs": [

            { "sClass": "text-center", "aTargets": target}

        ],

        "language": {

            "lengthMenu": "_MENU_",

            "zeroRecords": "Aucun résultat ne correpond à cette recherche.",

            "info": "Page _PAGE_ sur _PAGES_",

            "infoEmpty": "Pas d'entrée correpsondante dans la base.",

            "infoFiltered": "(parmi _MAX_)"

        },

        "sDom": 'rtp'

    });

}