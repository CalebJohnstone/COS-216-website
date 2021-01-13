$(document).ready(function(){
    //
});

function validLogin(){
    var email = document.getElementsByName("email")[0].value;
    var password = document.getElementsByName("password")[0].value;

    //check email
    if(email.length == 0){
        alert("You did not enter an email");
        return false;
    }
    else if(email.indexOf(" ") > -1){
        alert('Spaces are not allowed in your details');
        return false;
    }
    else if(!validEmail(email)){
        alert("Your email needs to have a @ in it");
        return false;
    }

    //check password
    if(password.length == 0){
        alert("You did not enter a password");
        return false;
    }
    else if(password.indexOf(" ") > -1){
        alert('Spaces are not allowed in your details');
        return false;
    }
    else if(!validPassword(password)){
        alert("Your password needs to have a lowercase letter, an uppercase letter, a number and a symbol. It must also be at least 9 characters in length");
        return false;
    }

    return true;
}

function validEmail(email){
    var re = /@/;
    return re.test(email);
}

function validPassword(password){
    var re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#^`()~_+=/@$!%*?&])[A-Za-z\d#^`()~_+=/@$!%*?&]{9,}$/;
    return re.test(password);
}