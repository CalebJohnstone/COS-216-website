$(document).ready(function (){
  $(".footer").append("<div id='google_translate_element'></div>");

  //only show the settings button if the user is logged in
  if(localStorage.getItem("API_key") !== null){
    $(".footer").append('<div id="sideBarOpenBtn"><button class="openbtn" onclick="openSidebar()">Settings</button></div>');
  }
  else{
    //move the other 2 buttons to the left to not have a random gap where the settings button would of been
    $(".dropup").css("margin-left", "10px");
    $("#google_translate_element").css("margin-left", "10px");
  }

});

function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}

$("body").on("change", "#google_translate_element select", function (e) {
  localStorage.setItem("language", "/" + $(this).find(":selected").val());
});

function setLanguage(){
  var language = getCookie("googtrans");
  localStorage.setItem("language", language);
}

function getCookie(name) {
  //so that first cookie matches the split format
  var cookies = "; " + document.cookie;
  var parts = cookies.split("; " + name + "=");

  if (parts.length == 2) 
    return parts.pop().split(";").shift();
}

$(".footer").append("<div class='dropup'><button class='dropbtn'>Theme</button><div class='dropup-content'></div></div></div>");
$(".dropup-content").append("<a onclick='changeTheme(\"light\")' onmouseover='showTheme(\"light\")' onmouseleave='showCurrentTheme()'>Light mode</a>");
$(".dropup-content").append("<a onclick='changeTheme(\"dark\")' onmouseover='showTheme(\"dark\")' onmouseleave='showCurrentTheme()'>Dark mode</a>");

function changeTheme(theme){
    showTheme(theme);
    localStorage.setItem("theme", theme);
}

function showTheme(theme){
    if(theme == "light"){
      $("body").get(0).style.setProperty("--white", "black");
      $("body").get(0).style.setProperty("--black", "white");
    }
    else if(theme == "dark"){
      $("body").get(0).style.setProperty("--white", "white");
      $("body").get(0).style.setProperty("--black", "black");
    }
}

function showCurrentTheme(){
  showTheme(localStorage.getItem("theme"));
}