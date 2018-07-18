$(document).ready(function(){
    $.ajax({
        type: "GET",
        url: "modules/almanax/almaquery.php",
        success: almanarray,
        dataType: "json"
    });
    $(".pars").bind('click keyup',function(event){        
        var req = "";
        if (event.target.tagName === "BUTTON"){
            if (event.target.className === "pars btn btn-lg btn-success"){
                $(".pars").each(function() {
                    if (this.type === "submit"){
                            this.className = "pars btn btn-lg btn-danger";
                        }
                    });                
            }else{
                $(".pars").each(function() {
                    if (this.type === "submit"){
                        if (this != event.target){
                            this.className = "pars btn btn-lg btn-danger";
                        }else{
                            this.className = "pars btn btn-lg btn-success";
                            req = this.value;
                        }
                    }
                });
            }
        }     
        req = req + $('#query').val();
        table = $('#mytable').DataTable();
        table.page.len($( "#table_len option:selected" ).val());
        table.search(req).draw();
    });
    $('#ics_export').on('click', function () {
        var data = "BEGIN:VCALENDAR\nVERSION:2.0\nPRODID:-//Profus.ovh//NONSGML Almanax//FR\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH\n";
        table.rows( { filter : 'applied'} ).data().each(function (row) {
            data += "BEGIN:VEVENT\nDTSTART:20"
                +row.date.split('<br')[0].split('/').reverse().join('')
                +"T010000Z\nDESCRIPTION: "+row.bonus+"\n"
                +row.qty+" "+row.item
                +"\nSUMMARY:Almanax "+row.qty+" "+row.item
                +"\nTRANSP:TRANSPARENT\nEND:VEVENT\n";
        });
        data += "END:VCALENDAR";
        download(data,'almanax.ics','text/x-vCalendar;charset=' + document.characterSet);
    });
    $('#mytable').on('click','.gremind',function (event) {
        console.log(event.target.parentNode.firstChild.nextSibling.nextSibling.textContent);
        var date = event.target.parentNode.firstChild.nextSibling.nextSibling.textContent.split('/').reverse().join('');
        var bonus = event.target.parentNode.nextSibling.firstChild.textContent.replace("%","%25");
        var offrande = event.target.parentNode.nextSibling.nextSibling.firstChild.nextSibling.nextSibling.nextSibling.textContent.replace("%","%25");
        var url = "http://calendar.google.com/calendar/render?action=TEMPLATE&text=Almanax&dates=20"+date+"T150000Z/20"+date+"T151000Z&details=Bonus+%3A+"+bonus+'%0AOffrande+%3A+'+offrande+"%0A%0AAlmanax+par+Profus&sf=true&output=xml#eventpage_6";
        window.open(url);
    })
});
var table;
function almanarray(data) {
    if (table!==undefined){table.destroy();}
    table = $('#mytable').DataTable( {
        data: data,
        columns: [
            { data: 'date' },
            { data: 'bonus' },
            { data: 'offering' }
        ],
        ordering: false,
        bInfo: false,
        "bDeferRender": true,
        "aoColumnDefs": [
            { "sClass": "text-center", "aTargets": [0,2]}
        ],
        "search": {
            "smart": true,
            "caseInsensitive": true,
            "regex": false
        },
        "pageLength": 7,
        "language": {
            "lengthMenu": "_MENU_",
            "zeroRecords": "Aucun résultat ne correpond à cette recherche.",
            "info": "Page _PAGE_ sur _PAGES_",
            "infoEmpty": "Pas d'entrée correpsondante dans la base.",
            "infoFiltered": "(parmi _MAX_)"
        },
        "sDom": 'rt<"col-xs-8 col-xs-offset-2"p><"clear">',
        "pagingType": "numbers"
    });
}

function download(data, filename, type) {
    var a = document.createElement("a"),
        file = new Blob([data], {type: type});
    if (window.navigator.msSaveOrOpenBlob)
        window.navigator.msSaveOrOpenBlob(file, filename);
    else {
        var url = URL.createObjectURL(file);
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 0);
    }
}