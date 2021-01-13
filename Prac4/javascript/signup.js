$(document).ready(function(){
    $(".footer").css({
        "position": "fixed",
        "bottom": "-15px"
    });
});

function validDetails(){
    var name = document.getElementsByName("name")[0].value;
    var surname = document.getElementsByName("surname")[0].value;
    var email = document.getElementsByName("email")[0].value;
    var password = document.getElementsByName("password")[0].value;

    //check the name
    if(name.length == 0){
        alert("You did not enter your name");
        return false;
    }

    //check the surname
    if(surname.length == 0){
        alert("You did not enter your surname");
        return false;
    }
    
    //check email
    if(email.length == 0){
        alert("You did not enter an email");
        return false;
    }
    else if(email.indexOf(" ") > -1){
        alert('Spaces are not allowed in your email');
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
        alert('Spaces are not allowed in your password');
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
    var re = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^\*])(?=.{9,})");
    return re.test(password);
}