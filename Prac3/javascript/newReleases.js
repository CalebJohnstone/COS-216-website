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
                    if(song["title"] == null){
                        return;
                    }                     

                     //build the song
                     var text = "";

                     text += addToText("Title", song["title"]);
                     text += addToText("Artist", song["artist"]);

                     if(song["rating"] !== null){
                        text += "Rating: " + song["rating"] + "/10<br>";
                     }

                    if(song["genre"] !== undefined && song["genre"] !== null && song["genre"].length > 1){
                        if(song["genre"].indexOf(",") > -1){
                            text += "Genres: " + song["genre"] + "<br>";
                        }
                        else{
                            text += "Genre: " + song["genre"] + "<br>";
                        }
                    }

                     text += addToText("Record Label", song["label"]);

                     var songDiv = "<div class='song'>";
                     songDiv += "<img class='artist' src='" + song["artistImageURL"] + "'";
                     songDiv += " alt = '" + song["artist"] + "'>";
                     songDiv += "<img class='album' src='" + song["albumImageURL"] + "'";
                     songDiv += " alt = 'Album image for song" + song["title"] + "'>";
                     songDiv += "<p>" + text + "</p>";

                     songDiv += "<div class='tooltip'>Review<span class='tooltiptext'>" + song["review"] + "</span></div>";
                     songDiv += "</div>";
             
                     $(".songSection").append(songDiv);
                 });//for each song
             }//if song data was returned
        }//once ready
    }//onreadystatechange
 
    RedPillRequest.open("POST", "http://wheatley.cs.up.ac.za/u19030119/api.php", false);
    RedPillRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    RedPillRequest.send("type=info&title=newReleases&return[]=*");
 }

 function addToText($label, $value){
    if($value == null){
        return "";
    }

    return $label + ": " + $value + "<br>";
}