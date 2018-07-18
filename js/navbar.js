$(document).ready(function(){
    /******************   INSCRIPTION   ******************/
    $('.glyphicon-edit').on('click', function(){
        window.location.href = "/inscription.php";
    });
    /******************     LOGOUT      ******************/
    $('#navbararea').on('click','.logout',function(){
        $.ajax({
            type: "GET",
            url: "modules/users/logout.php",
            success : updatenav
        });
    });
    /******************      LOGIN      ******************/
    $('#navbararea').on('click','#logingo',function(){
        login();
    });
    $('#navbararea').on('keyup','#password',function(event){
        if (event.keyCode == 13){login();}
    });
    /**************   CHANGE USER SERVER   ***************/
    $('#navbararea').on('change','.userserv',function () {
        $.ajax({
            type: "POST",
            url: "modules/users/setserver.php",
            data: {servor: this.value
                  }
        });
    });
    /******************   ADD TO CART   ******************/
    $('body').on('click','.add_to_shop',function () {
        var item = $(this).attr('data-item');
        var qty = $(this).attr('data-qty');
        var from = location.pathname.substring(1,parseInt(location.pathname.length)-4);
        $.ajax({
            type: "POST",
            url: "modules/cart/cart_update.php",
            data: { action : 'add',
                item : item,
                qty : qty,
                from : from},
            success : shop_success
        });
    });
    $('#cart').on('click','#add_item_to_cart',function () {
        var item = $('#cart_item').val();
        $.ajax({
            type: "POST",
            url: "modules/cart/cart_update.php",
            data: { action : 'add',
                item : item,
                qty : 1,
                from : 'Recherche'},
            success: shop_success
        });
        $.ajax({
            type: "POST",
            url: "modules/cart/cart_content.php",
            success : display_cart
        });
    });
    /****************   REMOVE FROM CART   *****************/
    $('#cart').on('click','.remove',function () {
        var row = $(this).closest('tr');
        var item = row.find('td:nth-child(1)').text();
        cart.row(row).remove().draw();
        $.ajax({
            type: "POST",
            url: "modules/cart/cart_update.php",
            data: { action : 'remove',
                    item : item,
                    qty : '',
                    from : ''}
        });
        $.ajax({
            type: "POST",
            url: "modules/cart/cart_content.php",
            success : display_cart
        });
    });
    $('#cart').on('click','.glyphicon-plus',function () {
        var value = $(this).closest('td').find('input').val();
        if (value<99999){
            $(this).closest('td').find('input').val(parseInt(value)+1);
        }
        update_cart($(this).prev()[0]);
    });
    $('#cart').on('click','.glyphicon-minus',function () {
        var value = $(this).closest('td').find('input').val();
        if (value>1){
            $(this).closest('td').find('input').val(parseInt(value)-1);
        }
        update_cart($(this).next()[0]);
    });
    $('#cart').on('click','#deleteCart',function () {
        $.ajax({
            type: "POST",
            url: "modules/cart/cart_delete.php",
            success: display_cart
        });
    });
    $('#cart').on('click',function (element) {
        if ($( element.target ).hasClass("img-item")){
            var item = element.target.alt;
            $.ajax({
                type: "POST",
                url: "modules/cart/cart_update.php",
                data: { action : 'add',
                    item : item,
                    qty : 1,
                    from : 'Recherche'},
                success: shop_success
            });
            $.ajax({
                type: "POST",
                url: "modules/cart/cart_content.php",
                success : display_cart
            });
        }else{
            if ($('#detailed_cart').hasClass("active")){
                if(element.target.tagName !== 'TH'){
                    $(element.target).closest('tr').toggleClass("collapse");
                }
            }
        }
    });
    $('#navbararea').on('click','.glyphicon-shopping-cart',function () {
        if ($( "#cart" ).is( ":hidden" )){
            $.ajax({
                type: "POST",
                url: "modules/cart/cart_content.php",
                success : display_cart
            });
        }
    });
    $('#cart').on('input','.cart-qty',function () {
        update_cart(this);
    });
    $('#cart').on('change','#mySelect',function (hdv) {
        cart.column(4).search(hdv.target.value).draw();
    });
    $('#cart_item').on('keyup',function(search){
        var recherche = search.target.value;
        if (recherche.length>2) {
            $.ajax({
                type: "POST",
                url: "modules/cart/suggest_item.php",
                data: {request: recherche},
                success: display_suggestions,
                dataType: "json"
            });
        }
    });
    $('#global_cart').on('click',function () {
        if (!$(this).hasClass("active")){
            $('#detailed_cart').removeClass("active");
            $('#global_cart').addClass("active");
            draw_cart_table();
        }
    });
    $('#detailed_cart').on('click',function () {
        if (!$(this).hasClass("active")){
            $('#global_cart').removeClass("active");
            $('#detailed_cart').addClass("active");
            draw_cart_table();
        }
    })
});

$(window).on("load", function() {
    $('.preload').addClass("loaded");
    $('#loadingbar').remove();
});

function login(){
    $.ajax({
        type: "POST",
        url: "modules/users/login.php",
            data: {
                user: document.getElementById("username").value,
                password: document.getElementById("password").value
            },
        success: updatenav
    });
}

function updatenav() {
    $("#navbararea").load('includes/menu.php');
}

function shop_success(){
    $('#btnarea>i.glyphicon-shopping-cart').toggleClass('added');
    setTimeout(function(){$('#btnarea>i.glyphicon-shopping-cart').toggleClass('added'); }, 250);
}

function update_cart(input){
    var item = input.parentElement.parentElement.firstChild.textContent;
    var qty = input.value;
    $.ajax({
        type: "POST",
        url: "modules/cart/cart_update.php",
        data: { action : 'update',
            item : item,
            qty : qty,
            from : ''}
    });
}
var cart;
var cart_global;
var cart_detailed;
function display_cart(data) {
    cart_global = data[0];
    cart_detailed = data[1];
    draw_cart_table();
}

function display_suggestions(data){
    $("#cart_item").autocomplete({
        source: data
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<div>" + item.image +"&nbsp;&nbsp;&nbsp;"+ item.name + "  (Niv. "+item.level+")</div>" )
            .appendTo( ul );
    };
}

function draw_cart_table() {
    var display_chosen = $('#display_type').children('.active').attr('id');
    if (display_chosen==='detailed_cart'){
        if (cart!==undefined){
            cart.destroy();
            $('#cart_content').html('');
        }
        cart = $('#cart_content').DataTable( {
            data: cart_detailed,
            columns: [
                { "title": "Objet", data: 'name' },
                { "orderable": false, data: 'image' },
                { "title": "Quantité", data: 'qty' },
                { "title": "Besoin pour", data: 'for' },
                { "title": "Hôtel de vente", "visible": true, searchable: true, data: 'hdv' }
            ],
            bInfo: false,
            "bDeferRender": true,
            "aoColumnDefs": [
                { "sClass": "text-center", "aTargets": [0,1,2,3]}
            ],
            "pageLength": 5,
            "language": {
                "lengthMenu": "_MENU_",
                "zeroRecords": "Aucun résultat ne correpond à cette recherche.",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Pas d'entrée correpsondante dans la base.",
                "infoFiltered": "(parmi _MAX_)"
            },
            "sDom": '<"#select_hdv">rtp',
            "pagingType": "numbers"
        });
        var myDiv = document.getElementById("select_hdv");
        var selectList = document.createElement("select");
        var optionArray = cart.column( 4 ).data().unique();
        selectList.setAttribute("id", "mySelect");
        $('#select_hdv').html('<label>Filtrer par hôtel de vente : </label> ');
        myDiv.appendChild(selectList);
        var default_option = document.createElement("option");
        default_option.setAttribute("value", "");
        default_option.text = "Tout";
        selectList.appendChild(default_option);
        for (var i = 0; i < optionArray.length; i++) {
            var option = document.createElement("option");
            option.setAttribute("value", optionArray[i]);
            option.style = "background-image:url(images/icons/hdv/"+optionArray[i]+".png);";
            option.innerHTML = '<img src="images/icons/hdv/'+optionArray[i]+'.png"/>'+optionArray[i];
            selectList.appendChild(option);
        }
    }else{
        if (cart!==undefined){
            cart.destroy();
            $('#cart_content').html('');
        }
        cart = $('#cart_content').DataTable( {
            data: cart_global,
            columns: [
                { "title": "Objet", data: 'name' },
                { "width": "60px", data: 'image' },
                { "title": "Depuis", "width": "52px", data: 'from' },
                { "title": "Niveau", "width": "30px", data: 'level' },
                { "title": "Métier", "width": "94px", data: 'metier' },
                { "title": "Recette", "width": "416px", data: 'recipe' },
                { "title": "Quantité", "width": "126px", data: 'quantity' },
                { "width": "45px", data: 'btn' }
            ],
            ordering: false,
            bInfo: false,
            "bDeferRender": true,
            "aoColumnDefs": [
                { "sClass": "text-center", "aTargets": [0,1,2,3,4,5,6,7]}
            ],
            "pageLength": 5,
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
    }
}