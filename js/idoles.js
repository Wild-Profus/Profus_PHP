var idols={};
var ignored={};
$(document).ready(function() {
    $('#compo').on('click','img',function(){
        delete idols[this.alt];
        this.remove();
        document.getElementById(this.alt).getElementsByTagName('img')[0].style.opacity = 1;
        document.getElementById(this.alt).getElementsByClassName('idol-txt')[0].style.opacity = 1;
        actualize();
    });
    $('#idoles').on('mousedown','.idol',function(event) {
        var idol = this.closest('span').id;
        var name = this.closest('span').firstChild.alt;
        switch (event.which){
            case 1:
                if(idols.hasOwnProperty(idol)){
                    delete idols[idol];
                    document.getElementById('compo').querySelectorAll('[alt="'+idol+'"]')[0].remove();
                    document.getElementById(idol).getElementsByTagName('img')[0].style.opacity = 1;
                    document.getElementById(idol).getElementsByClassName('idol-txt')[0].style.opacity = 1;
                    actualize();
                }else{
                    if (Object.keys(idols).length < 6){
                        idols[idol]=idol;
                        var img = document.createElement('img');
                        img.setAttribute('src', "images/adols/"+idol+".png");
                        img.setAttribute('alt', idol);
                        img.setAttribute('title', name);
                        img.setAttribute('style', 'max-width:40px;');
                        document.getElementById('compo').append(img);
                        actualize();
                    }
                }
                break;
            case 3:
                ignored[idol]=idol;
                this.closest('span').style.display = "none";
                break;
        }
    });
    $('#reset').on('click',function(){
        Object.keys(ignored).map(function(objectKey, index) {
            document.getElementById(objectKey).style.display = "";
        });
    });
    $('#sort').on('click', function(){
        var glyph = this.firstChild.className;
        switch(glyph){
            case "glyphicon glyphicon-sort":
                this.firstChild.className = "glyphicon glyphicon-sort-by-attributes-alt";
                break;
            case "glyphicon glyphicon-sort-by-attributes-alt":
                this.firstChild.className = "glyphicon glyphicon-sort-by-attributes";
                break;
            case "glyphicon glyphicon-sort-by-attributes":
                this.firstChild.className = "glyphicon glyphicon-sort";
                break;
        };
        sort();
    });
    $('#best').on('click',function(){
        var jsonString = JSON.stringify(ignored);
        $.ajax({
            type: "POST",
            url: "modules/idoles/getidolscores.php",
            data: {data : jsonString},
            success: displayscore,
            dataType: "json"
        });
    });
});

function actualize(){
    if (Object.keys(idols).length > 0) {
        var post = {};
        Object.keys(idols).map(function(objectKey, index) {
            post['idol'+index]=objectKey;
        });
        test = $.ajax({
            type: "POST",
            url: "modules/idoles/getidolesinfos.php",
            data: post,
            success: set_idols_parameters,
            dataType: "json"
        }).responseJSON;
        console.log(test);
    }else{
        document.getElementById('restriction').innerHTML = "";
        document.getElementById('score').innerHTML = '0';
        document.getElementById('idoles').parentNode.parentNode.style.top = document.getElementById('compo').parentNode.parentNode.parentNode.offsetHeight+'px';
        var img = document.getElementById('idoles').getElementsByTagName('img');
        for (var i = 0; i < img.length; i++) {
            img[i].style.border = "solid 2px #ddd";
            img[i].parentNode.getElementsByClassName('idol-txt')[0].innerHTML = img[i].dataset.bonus;
        }
    }
    sort();
}

function set_idols_parameters(data){
    console.log(data);
    document.getElementById('score').innerHTML = data[1];
    Object.keys(idols).forEach(function(idol) {
        var span = document.getElementById(idol);
        span.getElementsByTagName('img')[0].style.opacity = 0.4;
        span.getElementsByTagName('img')[0].style.border = "solid 2px #ddd";
        span.getElementsByClassName('idol-txt')[0].style.opacity = 0.4;
        span.getElementsByClassName('idol-txt')[0].innerHTML = span.firstChild.dataset.bonus;
    });
    document.getElementById('restriction').innerHTML = data[2].slice(0, -3);;
    document.getElementById('idoles').parentNode.parentNode.style.top = document.getElementById('compo').parentNode.parentNode.parentNode.offsetHeight+'px';
    Object.keys(data[0]).forEach(function(element) {
        var span = document.getElementById(element);
        var score = data[0][element];
        span.getElementsByClassName('idol-txt')[0].innerHTML = score;
        var source = span.getElementsByTagName('img')[0].dataset.bonus;
        if (score > source){
            span.getElementsByTagName('img')[0].style.border = "solid 2px #19d420";
        }else if (score < source){
            span.getElementsByTagName('img')[0].style.border = "solid 2px #f00";
        }else{
            span.getElementsByTagName('img')[0].style.border = "solid 2px #ddd";
        }
    });
    sort();
}

function sort(){
    var glyph = document.getElementById("sort").firstChild.className;
    var order = [];
    var allIdols = document.getElementById("idoles").getElementsByClassName("idol");
    for (let idolSpan of allIdols) {            
        order.push({"id":idolSpan.id,"score":idolSpan.lastChild.innerText,"name":idolSpan.firstChild.alt,"order":idolSpan.firstChild.dataset.order});
    }
    switch(glyph){
        case "glyphicon glyphicon-sort":
            order.sort(function (a, b) {
                return a.order - b.order;
            });
            break;
        case "glyphicon glyphicon-sort-by-attributes-alt":
            order.sort(function (a, b) {
                return b.score - a.score;
            });
            break;
        case "glyphicon glyphicon-sort-by-attributes":
            order.sort(function (a, b) {
                return a.score - b.score;
            });
            break;
    }
    for (let span of order) {            
        document.getElementById('idoles').appendChild(document.getElementById(span.id));
    }
}

function displayscore(data){
    $('#bestscores').html("");
    data.map(function(data){
        $('#bestscores').append(data.img + data.score +'<br>');
    })
}