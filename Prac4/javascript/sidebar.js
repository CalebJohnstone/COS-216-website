populateSelects();

function getPreferences(){
    var API_key = localStorage.getItem("API_key");

    if(API_key === null){
        return;
    }

    //send request to RedPillAPI to get the preferences
    var RedPillRequest = new XMLHttpRequest();

    var parameters = "key=" + API_key + "&type=get&return[]=userPreferences";

    RedPillRequest.onreadystatechange = function(){
        if(this.status == 200 && this.readyState == 4){
            var obj = JSON.parse(this.responseText);
            var data = obj["data"];

            var sort = data["sort"];
            var filterGenre = data["filter"]["genre"];
            var filterYear = data["filter"]["year"];

            //choose the appropriate input element's option (if the preference is NOT NULL)
            if(sort !== null){
                //select the radio button
                $("input[name='sortRadio'][value='" + sort + "']").prop("checked", true);
            }

            if(filterGenre !== null){
                //select the option from the selects
                $("#genreSave").val(filterGenre);
            }

            if(filterYear !== null){
                //select the option from the selects
                $("#yearSave").val(filterYear);
            }
        }
    }

    RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api_v2.php", false);
    RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    RedPillRequest.send(parameters);
}

function populateSelects(){
    populateGenres();
    populateYears();
}

function populateGenres(){
    $(".genre").each(function(index){
        $("#genreSave").append("<option value='" + $(this).text() + "'>" + $(this).text() + "</option>");
    });
}

function populateYears(){
    $(".year").each(function(index){
        $("#yearSave").append("<option value='" + $(this).text() + "'>" + $(this).text() + "</option>");
    });
}

/* Set the width of the sidebar to 250px and the left margin of the page content to 250px */
function openSidebar() {
    document.getElementById("mySidebar").style.width = "500px";
    getPreferences();
}
  
/* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
function closeSidebar() {
    document.getElementById("mySidebar").style.width = "0";

    //maybe --> set the searchPage input values here ??
}

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