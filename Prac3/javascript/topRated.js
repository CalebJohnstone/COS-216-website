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

                 data.forEach(function(song){
                     if(song === null || song["title"] === null){
                         return;
                     }

                     //build the song
                     var text = "";

                     text += addToText("Title", song["title"]);
                     text += addToText("Artist", song["artist"]);
                     text += addToText("Release Date", song["releaseDate"]); 
                     
                     if(song["ranking"] !== null){
                        text += "Ranking: " + song["ranking"] + "<br>";
                     }

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
                     songDiv += "</div>";
             
                     $(".songSection").append(songDiv);
                 });//for each song
             }//if song data was returned
        }//once ready
    }//onreadystatechange
 
    RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api.php", false);
    RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    RedPillRequest.send("type=info&title=topRated&return[]=*");
 }

 function addToText(label, value){
    if(value == null){
        return "";
    }

    return label + ": " + value + "<br>";
}