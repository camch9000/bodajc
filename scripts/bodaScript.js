function validaEmail(email)
{
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
    if(re.test(email))
        return true;
    else
        return false;
}

function validaMail()
{
    if(!validaEmail($("#validarData .contentShow #email").val()))
        $("#validarData .contentShow #email").css({"border": "solid 3px #f42922"});
    else
        $("#validarData .contentShow #email").css({"border": "solid 2px green"});    
}

function validatePhone(phone)
{
    var re = /^(\+?\d{1,2}\s)?\(?\d{3,4}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/;
    
    if(re.test(phone))
        return true;
    else
        return false;
}

function waitingLogin(value)
{
    $("#validarData input").attr('readonly', value);
}