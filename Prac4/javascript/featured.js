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

                    if(song["genre"] != undefined && song["genre"] !== null){
                        if(song["genre"].indexOf(",") > -1){
                            text += "Genres: " + song["genre"] + "<br>";
                        }
                        else{
                            text += "Genre: " + song["genre"] + "<br>";
                        }
                    }

                     text += addToText("Duration", song["duration"]);

                     var songDiv = "<div class='featuredSong'>";
                     songDiv += "<img class='artist' src='" + song["artistImageURL"] + "' alt='artist'";
                     songDiv += " alt = '" + song["artist"] + "'>";
                     songDiv += "<img class='album' src='" + song["albumImageURL"] + "' alt='album'";
                     songDiv += " alt = 'Album image for " + song["title"] + "'>";
                     songDiv += "<p>" + text + "</p>";

                     songDiv += getUserRating(index, song["id"]);//in user_rating.js
             
                     songDiv += "<audio controls class='featuredAudio' onplay='pauseOthers(this)'>";
                     songDiv += "<source src='" + song["preview"] + "' type='audio/mpeg'>";
                     songDiv += "<source src='" + song["preview"] + "' type='audio/mpeg'>";
                     songDiv += "</audio>";

                     if(song["preview"].length === 0){
                        songDiv += "<p>(no song preview available)</p><br>";
                     }
             
                     songDiv += "</div>";
             
                     $(".songSection").append(songDiv);
                 });//for each song
             }//if song data was returned
        }//once ready
    }//onreadystatechange
 
    RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api_v2.php", false);
    RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    RedPillRequest.send("key=464fb6a8b26f3d46cdddf54edc682df5&type=info&title=featured&return[]=*");
 }

 function addToText(label, value){
    if(value == null){
        return "";
    }

    return label + ": " + value + "<br>";
}