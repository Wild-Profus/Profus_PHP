$(document).ready(function(){ 
    /***************    USERNAME CHECKS   ****************/
    $('#insForm').on('keyup','#entryname',function(){
        if (this.value.length<6){failure(this);
            $('#entrynamehelp').html('Nom d\'utilisateur trop court : 6 lettres et/ou chiffres minimum.')
        }else if(this.value.length>=6){
            $(this).parents('div:first').removeClass("has-success");
            $(this).parents('div:first').removeClass("has-error");
            if (noSpecialChars(this)){
                $.ajax({
                    type: "POST",
                    url: "modules/users/checkexist.php",
                    data:{
                        user: this.value
                    },
                    success: usrexist,
                    dataType: "JSON"
                });
            }
        }
    });    
    /***************    PASSWORD CHECKS   ****************/
    $('#insForm').on('keyup','#entrypassword',function(){
        if (this.value.length<6){failure(this);
            $('#entrypasswordhelp').html('Mot de passe trop court : 6 lettres et/ou chiffres minimum.')
        }else if(this.value.length>=6){noSpecialChars(this)
        }
    });
    $('#insForm').on('keyup','#entrypasswordcheck',function(){
        $pwdinp = $(this).parent().prev().find('input');
        $pwdval = $pwdinp[0]["value"];
        if (this.value!= $pwdval){failure(this);
            $(this).next().html('Les mots de passe entrés de correspondent pas.');
        }else if (this.value===$pwdval){noSpecialChars(this);
            $(this).next().html('Mots de passe identiques');
        }
    });
    /***************    CREATE ACCOUNT   ****************/
    $('#insForm').on('click','#entryform',function(){
        if (inputsOK()) {
            $.ajax({
                type: "POST",
                url: "modules/users/createaccount.php",
                data:{                        
                    user: document.getElementById("entryname").value,
                    password: document.getElementById("entrypassword").value,
                    email: document.getElementById("entrymail").value
                },
                success: inscription
            });
        }
    });
})
/***************    CHECK FOR SPECIAL CHARS   ****************/
function noSpecialChars(event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String(event.value);
    if (!regex.test(key)) {
        failure(event);
        $(event).next().html('Chiffres et lettres uniquement svp !');
        return false;
    }
    else{
        success(event);
        $(event).next().html('Entrée valide !');
        return true;
    }
}
/***************    ALL ENTRIES VALID   ****************/
function inscription(data){
    console.log(data);
    if(data===true){
        window.location.href = "/index.php";        
    }else{
        document.getElementById("insHelp").value = data;
    }
}
/***************    ALL ENTRIES VALID   ****************/
function inputsOK(){
    var cond1 = $("#entryname").parent().hasClass("has-success");
    var cond2 = $("#entrypassword").parent().hasClass("has-success");
    var cond3 = $("#entrypasswordcheck").parent().hasClass("has-success");
    var cond4 = $("#checkform")[0]['checked'];
    return (cond1&&cond2&&cond3&&cond4);
}
/***************    VISUAL INFO   ****************/
function failure(event) {
    $(event).parents('div:first').removeClass("has-success");
    $(event).parents('div:first').addClass("has-error");
}
function success(event) {
    $(event).parents('div:first').removeClass("has-error");
    $(event).parents('div:first').addClass("has-success");
}
/***********    CHECK IF USERNAME IS AVAILABLE   ************/
function usrexist(data){
    if (data===true){
        $('#entrynamehelp').html('Nom d\'utilisateur disponible !');
    }
    else{
        $('#entrynamehelp').html('Ce nom d\'utilisateur existe déjà !')
    }
}