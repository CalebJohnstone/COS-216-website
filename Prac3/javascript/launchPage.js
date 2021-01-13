$(document).ready(function (){
    if(localStorage.getItem("API_key") !== null && localStorage.getItem("seen_message") === null){
        alert("Your API key for Red Pill Music is: " + localStorage.getItem("API_key") + ". This is for development using our API. You must save this. You can ignore this if you are not a developer.");
        localStorage.setItem("seen_message", true);
    }
});