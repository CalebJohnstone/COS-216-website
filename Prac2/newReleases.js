$(document).ready(function (){
    //API request to Deezer to populate New Releases page

    //create the request object
    var reqTopTrack = new XMLHttpRequest();

     //handle the response
     reqTopTrack.onreadystatechange = function(){    
        if(this.status == 200 && this.readyState == 4){
            var topTracksObject = JSON.parse(this.responseText);
            var data = topTracksObject["data"];
    
            $(document.body).append("<div class='songSection'></div>");
    
            //create the song elements
            var songElement =  "<div class='song'><img class='artist'><img class='album'><p></p>";
            songElement += "<div class='tooltip'>Review<span class='tooltiptext'></span></div></div>";
            
            for(var i = 0; i < data.length; i++){
                $(".songSection").append(songElement);
            }
    
            //put the most recent comment into the tooltiptext
            $(".tooltiptext").each(function (index) {
                var topTrack = data[index];
                var topTrackAlbumID = topTrack["album"]["id"];
    
                //request to get the comments
                var reqComments = new XMLHttpRequest();        
    
                reqComments.onreadystatechange = function(){
                   if(this.status == 200 && this.readyState == 4){
                       var commentsObject = JSON.parse(this.responseText);
                       var reviewText;
    
                       if(commentsObject["data"].length > 0){
                           reviewText = commentsObject["data"][0]["author"]["name"] + ": " + commentsObject["data"][0]["text"];
                       }
                       else{
                           reviewText = "No reviews yet";
                       }
    
                       $(".tooltiptext:eq(" + index + ")").html(reviewText);
                   }//reqComments status and readyState
                }//reqComments.onreadystatechange

                var urlString = "https://api.deezer.com/album/" + topTrackAlbumID;
                urlString += "/comments";
                reqComments.open("GET", urlString, true);
                reqComments.send();    
            });
    
            //don't need to make call to Album details for images as the URLs are in the Chart Albums section
            $(".album").each(function (index){
                var topTrack = data[index];
                var imageURL = topTrack["album"]["cover_big"];
    
                $(".album:eq(" + index + ")").attr("src", imageURL).attr("alt", topTrack["title"]);
            });
    
            $(".artist").each(function (index){
                var topAlbum = data[index];
                var imageURL = topAlbum["artist"]["picture_big"];
    
                $(".artist:eq(" + index + ")").attr("src", imageURL).attr("alt", topAlbum["artist"]["name"]);
            });
    
            $(".song p").each(function (index){
               var topTrack = data[index];
               
               if(topTrack == null || topTrack["title"] == null){
                    $(".song:eq(" + index + ")").addClass("EMPTY_SONG");
                    return;
                }

               var text;
    
               text = "Title: " + topTrack["title"] + "<br>";
               text += "Artist: " + topTrack["artist"]["name"] + "<br>";
               
               var rating = (topTrack["rank"]/200000) * 1.5;
               text += "Rating: " + rating.toFixed(2) + "/10 <br>";
    
               var topTrackAlbumID = topTrack["album"]["id"];
    
               //request the album info to get the genre and record label
               var reqAlbum = new XMLHttpRequest();
    
               reqAlbum.onreadystatechange = function(){
                if(this.status == 200 && this.readyState == 4){
                    var albumObject = JSON.parse(reqAlbum.responseText);
    
                    var genres = "";
                    var genreArray = albumObject["genres"]["data"];  
    
                    if(genreArray !== undefined && genreArray.length > 0){
                        if(genreArray.length == 1){
                            genres = genreArray[0]["name"];
                            text += "Genre: ";
                        }
                        else{
                            for(var a = 0; a < genreArray.length - 1; a++){
                                genres += genreArray[a]["name"] + ", ";
                            }

                            genres += genreArray[genreArray.length - 1]["name"];
                            text += "Genres: ";
                        }

                        text += genres + "<br>";
                    }
    
                    text += "Record Label: " + albumObject["label"] + "<br>";
    
                    $("p:eq(" + index + ")").html(text);
                }//reqAlbum status and readyState
               }//reqAlbum.onreadystatechange

               var urlString = "https://api.deezer.com/album/" + topTrackAlbumID;
               reqAlbum.open("GET", urlString, true);
               reqAlbum.send();
            });//for each song text  

            removeEmptySongs();
        }//reqTopTrack status and readyState
        }//reqTopTrack.onreadystatechange

    //open the request

    /*
    asynchronous to not require the whole page to reload again
    */
   
    reqTopTrack.open("GET", "https://api.deezer.com/chart/0/tracks", true);
    reqTopTrack.send();

    $("#loadScreen").delay(4000).fadeOut();
});//when the document is ready

function removeEmptySongs(){
    $(".EMPTY_SONG").remove();
}