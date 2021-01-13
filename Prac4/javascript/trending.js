RedPillRequest();

function RedPillRequest(){
    //make request to my API
    var RedPillRequest = new XMLHttpRequest();
 
    RedPillRequest.onreadystatechange = function(){
        if(this.status == 200 && this.readyState == 4){
            var obj = JSON.parse(this.responseText);
            var data = obj["data"];
 
             if(data.length > 0){
                 //build the page
                $("#content-wrap").append("<div class='songSection'></div>");

                 data.forEach(function(song, index){
                    if(song["title"] == null){
                        return;
                    }
                    
                     //build the song
                     var text = "";

                     text += addToText("Title", song["title"]);
                     text += addToText("Artist", song["artist"]);
                     text += addToText("Release Date", song["releaseDate"]);

                    if(song["genre"] != undefined && song["genre"] !== null){
                        if(song["genre"].indexOf(",") > -1){
                            text += "Genres: " + song["genre"] + "<br>";
                        }
                        else{
                            text += "Genre: " + song["genre"] + "<br>";
                        }
                    }

                     text += addToText("Album", song["album"]);

                     var songDiv = "<div class='song'>";
                     songDiv += "<img class='artist' src='" + song["artistImageURL"] + "'";
                     songDiv += " alt = '" + song["artist"] + "'>";
                     songDiv += "<img class='album' src='" + song["albumImageURL"] + "'";
                     songDiv += " alt = '" + song["album"] + "'>";
                     songDiv += "<p>" + text + "</p>";

                     songDiv += getUserRating(index, song["id"]);//in user_rating.js
                     
                     songDiv += "</div>";
                     
                     $(".songSection").append(songDiv);
                 });//for each song
             }//if song data was returned
        }//once ready
    }//onreadystatechange
 
    RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api_v2.php", false);
    RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    RedPillRequest.send("key=464fb6a8b26f3d46cdddf54edc682df5&type=info&title=trending&return[]=*");
 }

 function addToText(label, value){
     if(value == null){
         return "";
     }

     return label + ": " + value + "<br>";
 }           