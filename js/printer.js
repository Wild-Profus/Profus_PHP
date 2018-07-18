var cart_global;
var cart_detailed;
$(document).ready(function(){
    $.ajax({
        type: "POST",
        url: "modules/cart/cart_content.php",
        success : display_cart
    });
});

function display_cart(data) {
    cart_global = data[0];
    for (var prop in cart_global) {
        var r = /value='\d+/;
        cart_global[prop]['qty'] = cart_global[prop]['quantity'].match(r)[0].replace("value='","");
    }
    $('#global').DataTable( {
        data: cart_global,
        columns: [
            { "title": "Objet", data: 'name' },
            { "orderable": false, "title": "Niveau", "width": "30px", data: 'level' },
            { "orderable": false, "width": "60px", data: 'image' },
            { "orderable": false, "title": "Quantité", "width": "26px", data: 'qty' },
            { "title": "Métier", "width": "94px", data: 'metier' },
            { "orderable": false, "title": "Recette", "width": "416px", data: 'recipe' },
            { "orderable": false, "title": "Depuis", "width": "52px", data: 'from' }
        ],
        bInfo: false,
        paging: false,
        "bDeferRender": true,
        "aoColumnDefs": [
            { "sClass": "text-center", "aTargets": [0,1,2,3,4,5,6]}
        ],
        "language": {
            "lengthMenu": "_MENU_",
            "zeroRecords": "Aucun résultat ne correpond à cette recherche.",
            "info": "Page _PAGE_ sur _PAGES_",
            "infoEmpty": "Pas d'entrée correpsondante dans la base.",
            "infoFiltered": "(parmi _MAX_)"
        },
        "sDom": 'rtp',
        "pagingType": "numbers"
    });
    cart_detailed = data[1];
    var unique = [];
    for (var prop in cart_detailed) {
        var hdv = cart_detailed[prop]['hdv'] ;
        unique[hdv]=hdv;
    }
    for (var prop in unique){
        $('#table_area').append('<div class="col-xs-12">'+
                                '<h2 class="text-center">Hôtel de vente des ' + prop + '</h2>'+
                                '<table id="'+ prop +'" class="table table-striped table-hover table-responsive"></table></div>');
        $('#'+prop).DataTable( {
            data: cart_detailed,
            columns: [
                { "title": "Objet", data: 'name' },
                { "orderable": false, data: 'image' },
                { "orderable": false, "title": "Quantité", data: 'qty' },
                { "orderable": false, "title": "Besoin pour", data: 'for' },
                { "title": "Hôtel de vente", "visible": false, searchable: true, data: 'hdv' }
            ],
            bInfo: false,
            paging: false,
            "bDeferRender": true,
            "aoColumnDefs": [
                { "sClass": "text-center", "aTargets": [0,1,2,3]}
            ],
            "language": {
                "lengthMenu": "_MENU_",
                "zeroRecords": "Aucun résultat ne correpond à cette recherche.",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Pas d'entrée correpsondante dans la base.",
                "infoFiltered": "(parmi _MAX_)"
            },
            "sDom": '<"#select_hdv">rtp',
            "pagingType": "numbers"
        }).column(4).search(prop).draw();
    }
    setTimeout(function(){
        window.print();
    }, 2000);
}