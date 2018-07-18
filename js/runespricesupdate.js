$(document).ready(function(){
    $('body').on('click','#saverunesprices',function(){
        var input = document.getElementById('parea').getElementsByTagName('input');
        var data={};
        data['server'] = document.getElementById('Servor').value;
        for(var i = 0;i < input.length; i++)        {
            data[input[i].id] = input[i].value;
        }
        $.ajax({
            type: "POST",
            url: "modules/runesprices/saverunesprices.php",
            data: data,
            success: operation_result,
            dataType: "json"
        });
    });
    $('form').on('change','#Servor',function(input){
        $.ajax({
            type: "POST",
            url: "modules/runesprices/getrunesprices.php",
            data: {server : input.target.value},
            success: show_rune_prices,
            dataType: "json"
        });
    })
});

function operation_result(data){
    document.getElementById('operation_result').innerHTML = data;
}

function show_rune_prices(data) {
    data.forEach(function (row) {
       document.getElementById(row.rune).value = row.srv;
    });
}