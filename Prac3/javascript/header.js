$("html").attr("lang", localStorage.getItem("language"));

$(document).ready(function(){
    var title = $(document).find("title").text().replace(/ /g, '');

    if(title === "REDPILLMUSIC"){
        title = "launchPage";
    }

    var topNavATag = $("#topnav a[href *= '" + title + "' i]");
    topNavATag.attr("class", "active");

    if(localStorage.getItem("API_key") !== null){
        $("a[href='login.php']").attr("href", "").html("Log Out");
        $("a[href='signup.php']").remove();

        if(localStorage.getItem("seen_message") === "false"){
            alert("Your API key for Red Pill Music is: " + localStorage.getItem("API_key") + ". This is for development using our API. You must save this. You can ignore this if you are not a developer.");
            localStorage.setItem("seen_message", true);
        }
    }
    else{
        $("a[href='logout.php']").attr("href", "login.php").html("Login");
    }
});