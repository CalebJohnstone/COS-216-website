validateLogin();

function validateLogin(){
    var email = $("#emailValue").text();
    var password = $("#passwordValue").text();

    //make login request to RedPillAPI
    var RedPillRequest = new XMLHttpRequest();
 
    RedPillRequest.onreadystatechange = function(){
        if(this.status == 200 && this.readyState == 4){
            var obj = JSON.parse(this.responseText);
            var status = obj["status"];

            if(status === "success"){
                var API_key = obj["data"];

                localStorage.setItem("API_key", API_key);
                localStorage.setItem("seen_message", true);

                window.location.replace("../php/launchPage.php");
            }
            else{//not a valid <username, password>
                alert("Your email or password is invalid");
                window.location.replace("../php/login.php");
            }            
        }
    }

    RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api_v2.php", false);
    RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    RedPillRequest.send("key=464fb6a8b26f3d46cdddf54edc682df5&type=login&email=" + email + "&password=" + password + "&return[]=key");
}