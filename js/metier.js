/****************************    PAGE FILTER    *********************************/
jQuery.fn.dataTable.Api.register( 'page.jumpToData()', function ( data, column ) {
    var pos = this.column(column, {order:'current'}).data().indexOf( data );
    if ( pos >= 0 ) {
        var page = Math.floor( pos / this.page.info().length );
        this.page( page ).draw( false );
    }
    return this;
} );
/****************************    VARS INIT    *********************************/
var bonus = 1;
var niveau = 1;
var experience = 0;
var entry = '';
$(document).ready(function(){
/*************************    XP AND LVL ENTRY    ******************************/
    $('#start').on('input','input',function (input) {
        var sibling = $(input.target.parentNode).siblings()[2].childNodes[1];
        if (input.target.value!==''&&input.target.value>0){
            sibling.disabled = true;
            entry = input.target.id;
            if (entry === 'experience'){
                sibling.value = Math.max(Math.min(parseInt(Math.floor((Math.sqrt(1+parseInt(input.target.value)*4/10)+1)/2)),200),1);
            }else if(entry === 'niveau'){
                sibling.value = Math.max(Math.min(parseInt(input.target.value)*(parseInt(input.target.value)-1)*10,398000),0);
            }
            niveau = Math.max(Math.min(parseInt(document.getElementById('niveau').value),200),1);
            document.getElementById('niveau').value = niveau;
            experience = Math.max(Math.min(parseInt(document.getElementById('experience').value),398000),0);
            document.getElementById('experience').value = experience;
        }else{
            input.target.disabled = false;
            sibling.disabled = false;
            entry = '';
            sibling.value = '';
            niveau = 1;
            experience = 0;
        }
    });
    $('#start').keypress('input',function(e) {
        if(e.which == 13) {
            $.ajax({
                type: "POST",
                url: "modules/metiers/itemforskill.php",
                data: {jobId: document.getElementById('skill').value},
                success: displaytable,
                dataType: "json"
            });
            document.getElementById('lvlplanbody').innerHTML= '';
        }
    });
/***************************    RECIPE INIT    ********************************/
    $('#goskill').click(function() {
        $.ajax({
            type: "POST",
            url: "modules/metiers/itemforskill.php",
            data: {jobId: document.getElementById('skill').value},
            success: displaytable,
            dataType: "json"
        });
        document.getElementById('lvlplanbody').innerHTML= '';
    });
/******************************   ADD ROW   ************************************/
    $('#mytable').on('click','.tocraft',function(event){
        var item_level = parseInt(event.target.parentNode.parentNode.firstChild.innerHTML);
        updatexp();
        if (item_level<=niveau){
            if(entry!==''){
                document.getElementById(entry).disabled = true;
            }else{
                document.getElementById('experience').disabled = true;
                document.getElementById('niveau').disabled = true;
            }
            var prevrow = $('#lvlplan tr:last').find('td:nth-child(5)');
            if ($(prevrow).length>0){
                $(prevrow).find('input').prop('disabled',true);
            }
            var item = $(this).parents('tr:first').find('td:nth-child(2)').find('img')[0];
            var basexp = $(this).parents('tr:first').find('td:nth-child(4)')[0].textContent;
            var itemlvl = $(this).parents('tr:first').find('td:nth-child(1)')[0].textContent;
            $.ajax({
                type: "POST",
                url: "modules/metiers/addrow.php",
                data: {
                    itemlevel: itemlvl,
                    basexp: basexp,
                    lastlvl : niveau,
                    lastxp : experience,
                    bonus: bonus
                },
                success: function (data){
                    additem(data,item,itemlvl);
                },
                dataType: "json"
            });
        }
    });
    $('#lvlplan').on("change",".craftqty",function(){
        var qty = $(this).val();
        var craft_lvl = parseInt($(this).parents('tr:first').find('td:nth-child(3)')[0].textContent);
        var base_xp = parseInt($(this).parents('tr:first').find('td:nth-child(4)')[0].textContent);
        $.ajax({
            type: "POST",
            url: "modules/metiers/somemaths.php",
            data: {
                fct: 'lvl_and_qty',
                vars: [experience, qty, craft_lvl, base_xp],
                bonus: bonus
            },
            success: function(data){
                updatewithqty(data,qty);
            },
            dataType: "json"
        });
    });

    $('#lvlplan').on("change",".tolvl",function(){
        var lvl = $(this).val();
        var craft_lvl = parseInt($(this).parents('tr:first').find('td:nth-child(3)')[0].textContent);
        var base_xp = parseInt($(this).parents('tr:first').find('td:nth-child(4)')[0].textContent);
        $.ajax({
            type: "POST",
            url: "modules/metiers/somemaths.php",
            data: {
                fct: 'lvl_and_lvlgoal',
                vars: [experience, lvl, craft_lvl, base_xp],
                bonus: bonus
            },
            success: function (data){
                updatewithlvlgoal(data,lvl);
            },
            dataType: "json"
        });
    });
    $('#rmlastrow').click(function () {
        deletelastrow('lvlplan');
    });
/************************    CRAFT TOOLTIP    ****************************/
    $('#lvlplan').on({
        'mouseenter': function() {
            $('#head_target').tooltip('show');
        },
        'mouseleave': function() {
            $('#head_target').tooltip('hide');
        }
    },'.mygoal');
    $('#bonus').on('change',function () {
        bonus = parseFloat(document.getElementById('bonus').value);
    })
});
/**********************    RECIPE DATATABLE    ***************************/
var table;
function displaytable(data) {
    if (table!==undefined){table.destroy();}
    var height = Math.max(window.innerHeight - 260,340);
    table = $('#mytable').DataTable( {
        data: data,
        columns: [
            { data: 'level' },
            { data: 'img' },
            { data: 'recipe' },
            { data: 'xp' },
            { data: 'craftbtn' }
        ],
        paging : true,
        ordering: false,
        deferRender: true,
        scrollY: height,
        scroller: true,
        bInfo: false,
        "language": {
            "lengthMenu": "_MENU_",
            "zeroRecords": "Aucun résultat ne correpond à cette recherche.",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)"
        },
        "sDom": 'rt<"col-xs-8 col-xs-offset-2"p><"clear">',
        "pagingType": "numbers"
    });
    var decimal = Math.round(parseInt(document.getElementById('niveau').value)/10)*10;
    table.page.jumpToData(""+decimal, 0 );
}

function additem(data,item,itemlvl){
    $('#lvlplan tbody:last').append('<tr><td style="vertical-align: middle;">Niveau : '+niveau+'<br/>Expérience : '+experience+'</td>' +
        '<td style="vertical-align: middle;">'+item.outerHTML+'</td>' +
        '<td style="vertical-align: middle;">'+itemlvl+'</td>' +
        '<td style="vertical-align: middle;">'+data[0]+'</td>' +
        '<td class="mygoal form-horizontal" style="padding: 1px;vertical-align: middle;"><div class="form-group row" style="margin-bottom: 0px;">' +
        '<div class="col-xs-12">' +
        '<label class="col-xs-6" style="margin-top: 4px;">Quantité</label>' +
        '<input class="craftqty control-input text-center col-xs-4 col-xs-offset-1" value="1" style="padding: 1px;">' +
        '</div>' +
        '<div class="col-xs-12">' +
        '<label class="col-xs-6">Niveau</label>' +
        '<input class="tolvl control-input text-center col-xs-4 col-xs-offset-1" style="padding: 1px;">' +
        '</div>' +
        '</div>' +
        '</td>' +
        '<td style="vertical-align: middle;">Niveau atteint : <span id="lvl">'+data[1]+'</span><br/>Expérience atteinte: <span id="exp">'+data[2]+'</span></td>'+
        '<td style="vertical-align: middle;">'+shopbtn(item.alt,1)+'</td>'+
        '</tr>');
}

function updatewithqty(data,qty){
    var xpandlvl = data;
    var resultcol =  $('#lvlplan tr:last').find('td:nth-child(6)');
    var targetcol = $('#lvlplan tr:last').find('td:nth-child(5)').find('div:nth-child(2)');
    $(targetcol).find('input:nth-child(2)').prop('disabled', true);
    $(resultcol).find('span:nth-child(1)').html(xpandlvl[1]);
    $(resultcol).find('span:nth-child(3)').html(xpandlvl[0]);
    $('#lvlplan tr:last').find('i.add_to_shop')[0].dataset.qty = qty;
    if (qty==0||qty==""){
        (targetcol).find('input:nth-child(2)').prop('disabled', false);
    }
}

function updatewithlvlgoal(data,lvl){
    var xpandlvl = data;
    var resultcol =  $('#lvlplan tr:last').find('td:nth-child(6)');
    var targetcol = $('#lvlplan tr:last').find('td:nth-child(5)').find('div:nth-child(1)').find('div:nth-child(1)');
    $(targetcol).find('input:nth-child(2)').prop('disabled', true);
    $(resultcol).find('span:nth-child(1)').html(xpandlvl[1]);
    $(resultcol).find('span:nth-child(3)').html(xpandlvl[0]);
    $(targetcol).find('input:nth-child(2)').val(xpandlvl[2]);
    $('#lvlplan tr:last').find('i.add_to_shop')[0].dataset.qty = xpandlvl[2];
    if (lvl==0||lvl==""){
        (targetcol).find('input:nth-child(2)').prop('disabled', false);
    }
}

function deletelastrow(tableID) {
    var table = document.getElementById(tableID);
    var rowCount = table.rows.length;
    if (rowCount>1) {
        table.deleteRow(rowCount - 1);
        var prevrow = $('#lvlplan tr:last').find('td:nth-child(5)');
        if ($(prevrow).length>0){
            $(prevrow).find('input').prop('disabled',false);
        }
    }
    if (rowCount === 2){
        if (entry !== ''){
            document.getElementById(entry).disabled = false;
        }else{
            document.getElementById('experience').disabled = false;
            document.getElementById('niveau').disabled = false;
        }
        niveau = document.getElementById('niveau').value;
        experience = document.getElementById('experience').value;
    }
    if (rowCount === 1){
        document.getElementById('niveau').value = '';
        document.getElementById('experience').value = '';
        document.getElementById('experience').disabled = false;
        document.getElementById('niveau').disabled = false;
        niveau = 1;
        experience = 0;
    }
}

function shopbtn(item,qty){
    return '<i class="add_to_shop glyphicon glyphicon-shopping-cart btn btn-success" title ="'+item+'" data-item="'+item.replace(/'/g , "&#39;")+'" data-qty="'+qty+'"></i>';
}

function updatexp(){
    var table = document.getElementById('lvlplan');
    var rowCount = table.rows.length;
    if (rowCount>1){
        var test = $('#lvlplan tr:last').find('td:nth-child(6)').find('span');
        niveau = parseInt(test[0].innerHTML);
        experience = parseInt(test[1].innerHTML);
    }else{
        niveau = Math.max(document.getElementById('niveau').value,1);
        experience = Math.max(document.getElementById('experience').value,0);
    }
}