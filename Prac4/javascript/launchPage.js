$(".footer").css("visibility", "hidden");

$(document).ready(function (){
    //localStorage.setItem("seen_message", false);
    $(".footer").css("visibility", "visible").css("bottom", "-850px").css("position", "relative");//TODO: footer shows at the top of the page before during the alert
    
    //set the language to the user's last saved language
    var language = localStorage.getItem("language");
    var currentLanaguageCookie = getCookie("googtrans");

    var newLanguageCookie = "googtrans=" + language + "; expires=Session; path=/";
    document.cookie = newLanguageCookie;

    if(localStorage.getItem("API_key") !== null && localStorage.getItem("seen_message") === null){
        alert("Your API key for Red Pill Music is: " + localStorage.getItem("API_key") + ". This is for development using our API. You must save this. You can ignore this if you are not a developer.");
        localStorage.setItem("seen_message", true);
    }
});

function getCookie(name) {
    //so that first cookie matches the split format
    var cookies = "; " + document.cookie;
    var parts = cookies.split("; " + name + "=");
  
    if (parts.length == 2) 
      return parts.pop().split(";").shift();
  }