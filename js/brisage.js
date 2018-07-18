var brokerate;
var totalweight;
var itemlevel;
var item;
$(document).ready(function(){
    $('#item').on('keyup',function(input){
        var recherche = this.value;
        if (recherche.length>2) {
            $.ajax({
                type: "POST",
                url: "modules/brisage/suggestitem.php",
                data: {request: recherche},
                success: displaysuggestions,
                dataType: "json"
            });
        }
    });
    $('#goquery').on('click', function(event){
        event.preventDefault();
        var item = document.getElementById('item').value;
        var srv = document.getElementById('itbb_srv').value;
        if (item.length>1) {
            $.ajax({
                type: "POST",
                url: "modules/brisage/getitembrisage.php",
                data: {item: item,
                       srv : srv},
                success: display_item,
                dataType: "json"
            });
        }
    });
    $('#itbb_srv').on('change', function(event){
        var item = document.getElementById('item').value;
        var srv = document.getElementById('itbb_srv').value;
        if (item.length>1) {
            $.ajax({
                type: "POST",
                url: "modules/brisage/getitembrisage.php",
                data: {item: item,
                       srv : srv},
                success: display_item,
                dataType: "json"
            });
        }
    });
    $("#broke_simulation").on('click','#saveRunesPrices', function(event){
        event.preventDefault();
        var item = document.getElementById('item').value;
        var srv = document.getElementById('itbb_srv').value;
        var tbod = document.getElementById("tbody");
        var prices = [];
        for (var i = 0, row; row = tbod.rows[i]; i++) {
           prices.push({"runeName":row.childNodes[0].innerText,
                        "runePrice": parseInt(row.childNodes[3].innerHTML)});
        }
        if (item.length>1) {
            $.ajax({
                type: "POST",
                url: "modules/brisage/saveRunesPrices.php",
                data: {srv : srv,
                       prices : prices}
            });
        }
    });
    $('#itbb_craftable').on('input','#brokerate',function(input){
        brokerate = input.target.innerHTML;
        updatebrokequantities();
        updatebrokeprices();
    });
    $('#broke_simulation').on('input','td',function(){
        updateexo();
        updatetotalweight();
        updatebrokequantities();
        updatebrokeprices();
    });
    $('#itbb_roll_selector').on('click','button',function (button) {
        var selector = button.target.id;
        $("#itbb_roll_selector button").attr("class", "btn btn-default btn-lg");
        $("#"+selector).attr("class", "btn btn-default btn-lg active");
        var table = document.getElementById("broke_simulation");
        for (var i = 1; i < table.rows.length-1; i++) {
            table.rows[i].cells[1].innerText = item[i-1][selector];
        }
        updatetotalweight();
        updatebrokequantities();
        updatebrokeprices();
    })
});

function displaysuggestions(data){
    $("#item").autocomplete({
        source: data
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<div>" + item.image +"&nbsp;&nbsp;&nbsp;"+ item.name + "  (Niv. "+item.level+")</div>" )
            .appendTo( ul );
    };
}

function display_item(data){
    document.getElementById('broke_simulation').innerHTML = "";
    Object.keys(data[0]).forEach(function(prop) {
        document.getElementById("itbb_"+prop).innerHTML = data[0][prop];
    });
    document.getElementById("itbb_roll_selector").innerHTML = "<span>Jet : </span><button id='min' class='btn btn-default btn-lg'>Min</button><button id='moy' class='btn btn-default btn-lg active'>Moy</button><button id='max' class='btn btn-default btn-lg'>Max</button>";
    itemlevel = data[0]['level'];
    brokerate = data[1];
    var table = document.createElement("table");
    var thead = document.createElement("thead");
    var thr = document.createElement("tr");
    var title = ['Stat','Jet','Poid', 'Prix [' + data[2] + ']','Quantité avec focus','Valeur','Sans focus'];
    title.forEach(function (header) {
        var th = document.createElement('th');
        th.className = "text-center";
        th.appendChild(document.createTextNode(header));
        thr.appendChild(th);
    });
    var savePricesButton = document.createElement("button");
    savePricesButton.id = "saveRunesPrices";
    savePricesButton.className = "btn btn-sm btn-default";
    savePricesButton.style = "padding:5px 7px 3px 7px; margin:0 0 0 3px";
    var saveIcon = document.createElement("i");
    saveIcon.className = "glyphicon glyphicon-floppy-disk";
    savePricesButton.appendChild(saveIcon);
    thr.childNodes[3].appendChild(savePricesButton);
    var thf = document.createElement('th');
    thf.id = 'totalnofocus';
    thf.className = "text-center";
    thr.appendChild(thf);
    thead.appendChild(thr);
    document.getElementById('broke_simulation').appendChild(thead);
    var tbody = document.createElement("tbody");
    Object.keys(data[3]).forEach(function(index) {
        var row = document.createElement('tr');
        var name_cell = document.createElement('td');
        var img = document.createElement('img');
        img.src = "images/items/Rune "+data[3][index]['rune']+".png";
        img.style.width = '38px';
        img.style.margin = '0 3px 0 0';
        name_cell.appendChild(img);
        name_cell.appendChild(document.createTextNode(data[3][index]['stat']));
        row.appendChild(name_cell);
        var jet_cell = document.createElement('td');
        jet_cell.setAttribute("contenteditable","true");
        jet_cell.appendChild(document.createTextNode(data[3][index]['jet']));
        row.appendChild(jet_cell);
        var weight_cell = document.createElement('td');
        weight_cell.appendChild(document.createTextNode(data[3][index]['weight']/data[3][index]['base']));
        row.appendChild(weight_cell);
        var price_cell = document.createElement('td');
        price_cell.setAttribute("contenteditable","true");
        price_cell.appendChild(document.createTextNode(data[3][index]['price']));
        row.appendChild(price_cell);
        for(var i = 0; i<4;i++){
            var cell = document.createElement('td');
            row.appendChild(cell);
        }
        tbody.appendChild(row);
    });
    var tr_exo = document.createElement("tr");
    var td_sct = document.createElement("td");
    td_sct.innerHTML = data[4];
    td_sct.id = "exo_td";
    tr_exo.appendChild(td_sct);
    var jet_cell = document.createElement('td');
    jet_cell.setAttribute("contenteditable","true");
    tr_exo.appendChild(jet_cell);
    var weight_cell = document.createElement('td');
    weight_cell.appendChild(document.createTextNode(0));
    tr_exo.appendChild(weight_cell);
    var price_cell = document.createElement('td');
    price_cell.setAttribute("contenteditable","true");
    price_cell.appendChild(document.createTextNode(0));
    tr_exo.appendChild(price_cell);
    for(var i = 0; i<4;i++){
        var cell = document.createElement('td');
        tr_exo.appendChild(cell);
    }
    tbody.appendChild(tr_exo);
    tbody.id='tbody';
    document.getElementById('broke_simulation').appendChild(tbody);
    updatetotalweight();
    updatebrokequantities();
    updatebrokeprices();
    item = data[3];
}

function updatetotalweight(){
    totalweight=0;
    $('#broke_simulation tr').each(function(){
        var weight = $(this).find('td:nth-child(2)').text();
        if (weight < 0){
            weight = 0;
        }
        var runeweight = $(this).find('td:nth-child(3)').text();
        totalweight = totalweight + weight * runeweight;
    });
}

function updatebrokequantities(){
    $('#broke_simulation tr').each(function(){
        var weight = $(this).find('td:nth-child(2)').text();
        if (weight>0){
            var runeweight;
            var stat = $(this).find('td:nth-child(1)').text();
            if(stat.search('PAPMPortee')!==-1){
                stat = $('#exo :selected').text();
            }
            if (stat==='Initiative'||stat==='Vitalité'||stat==='Pods'){
                runeweight=1;
            } else {
                runeweight = $(this).find('td:nth-child(3)').text();
            }
            var localfocusweight = weight*$(this).find('td:nth-child(3)').text();
            var runesqty = ((localfocusweight+(totalweight-localfocusweight)/2)/runeweight)*(itemlevel*0.025)*(brokerate/100);
            var runesqty2 = (localfocusweight/runeweight)*(itemlevel*0.025)*(brokerate/100);
            if (runesqty<0||runesqty=='Infinity'||runesqty=='NaN'){
                runesqty = 0;
                runesqty2 = 0;
            }
            $(this).find('td:nth-child(5)').text(Math.round(runesqty));
            $(this).find('td:nth-child(7)').text(Math.round(runesqty2));
        }else{
            $(this).find('td:nth-child(5)').text("");
            $(this).find('td:nth-child(7)').text("");
        }
    })
}

function updatebrokeprices(){
    var totalnofocus = 0;
    $('#broke_simulation tr').each(function(){
        var qty = $(this).find('td:nth-child(5)').text();
        var price = $(this).find('td:nth-child(4)').text();
        $(this).find('td:nth-child(6)').text(Math.round(qty*price));
        var qty2 = $(this).find('td:nth-child(7)').text();
        $(this).find('td:nth-child(8)').text(Math.round(qty2*price));
        totalnofocus = totalnofocus + qty2*price;
    });
    $('#totalnofocus').html(totalnofocus);
}

function updateexo(){
    $('#exo').closest('tr').find('td:nth-child(3)').text($('#exo').val());
    $('#exo').closest('tr').find('td:nth-child(4)').text($('#exo option:selected').attr('data-price'));
}