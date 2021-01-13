   var title = $(document).find("title").text().toLowerCase();

   if(title === "new releases"){
      title = "newReleases";
   }
 
 function getUserRating(index, id){
   if(localStorage.getItem("API_key") !== null){
      //make request to API to use DB to get user_rating
      var userRating;

      var ratingReq = new XMLHttpRequest();

      ratingReq.onreadystatechange = function(){
         if(this.status == 200 && this.readyState == 4){
              var obj = JSON.parse(this.responseText);
              userRating = obj["data"]["user_rating"];
  
              if(userRating === null){
                  userRating = "none";
              }
         }
      }
  
      ratingReq.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api_v2.php", false);
      ratingReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  
      var API_key = localStorage.getItem("API_key");

      ratingReq.send("key=" + API_key + "&type=get&title=" + title + "&id=" + id + "&return[]=userRating");

      //add the rating bar
      var ratingDiv = "<div class='slideContainer'>";

      var value;

      if(userRating === "none"){
          value = "0";
      }
      else{
          value = userRating;
          userRating += "/10";
      }

      ratingDiv += "<p>Your rating: </p><input type='range' min='1' max='10' value='" + value + "' class='slider' id='range" + index + "' onchange=\"displayValue(" + index + ", '" + id + "');\">";

      ratingDiv += "<div id='rating" + index + "'><p> " + userRating + "</p></div>";
      ratingDiv += "</div>";

      return ratingDiv;
   }
   else{
      return "";
   }
 }
 
 //onchange event for the rating sliders --> song <index, id>
 function displayValue(index, id){
    //change the value that is displayed next to the slider
    var rating = $("#range" + index).val();
    $("#rating" + index + " p").html(" " + rating + "/10");

    //make a type=rate request to the API to set/update the rating
    var RedPillRequest = new XMLHttpRequest();
 
    /*
    RedPillRequest.onreadystatechange = function(){
      if(this.status == 200 && this.readyState == 4){
         var obj = JSON.parse(this.responseText);
         //console.log(obj);
      }
    }
    */

    RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api_v2.php", false);

    var API_key = localStorage.getItem("API_key");

    var parameters = "key=" + API_key + "&type=rate&title=" + title + "&id=" + id + "&value=" + rating + "&return[]=NULL";

    RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    RedPillRequest.send(parameters);
 }