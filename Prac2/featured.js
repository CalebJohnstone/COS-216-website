$(document).ready(function (){
    var reqTopPlaylists = new XMLHttpRequest();

    reqTopPlaylists.onreadystatechange = function(){
        if(this.status == 200 && this.readyState == 4){
            var topPlaylistsObject = JSON.parse(this.responseText);
            var data = topPlaylistsObject["data"];

            $(document.body).append("<div class='songSection'></div>");

            //create the song elements
            var songElement =  "<div class='featuredSong'><img class='artist'><img class='album'><p></p><audio controls class='featuredAudio' onplay='pauseOthers(this)'></audio></div>";
            for(var i = 0; i < data.length; i++){
                $(".songSection").append(songElement);
            }

            //take first song from each playlist
            $(".featuredSong p").each(function (index){
                var topPlaylist = data[index];
                var playlistTracksURL = "https://api.deezer.com/playlist/" + topPlaylist["id"];

                //go to the tracks in the playlist
                var reqPlaylistTracks = new XMLHttpRequest();

                reqPlaylistTracks.onreadystatechange = function(){
                    if(this.status == 200 && this.readyState == 4){
                        var tracksObject = JSON.parse(this.responseText);
                        var trackArray = tracksObject["tracks"]["data"];
            
                        var numTracks = trackArray.length;
                        var trackIndex = Math.floor(numTracks/4);
                        var track = trackArray[trackIndex];

                        //extract the track info
                        if(track == null || track["title"] == null){
                            $(".featuredSong:eq(" + index + ")").addClass("EMPTY_SONG");
                            return;
                        }

                        var text = "";
                        text += "Title: " + track["title"] + "<br>";
                        text += "Artist: " + track["artist"]["name"] + "<br>";
                        
                        //request the album info to get the genre and record label
                        var albumID = track["album"]["id"];
                        
                        var reqAlbum = new XMLHttpRequest();

                        reqAlbum.onreadystatechange = function(){
                            if(this.status == 200 && this.readyState == 4){
                                var albumObject = JSON.parse(reqAlbum.responseText);

                                if(!albumObject.hasOwnProperty("error")){    
                                    var genres = "";
                                    var genreArray = albumObject["genres"]["data"];  

                                    if(genreArray !== undefined && genreArray.length > 0){
                                        if(genreArray.length == 1){
                                            genres = genreArray[0]["name"];
                                            text += "Genre: ";
                                        }
                                        else {
                                            for(var a = 0; a < genreArray.length - 1; a++){
                                                genres += genreArray[a]["name"] + ", ";
                                            }
    
                                            genres += genreArray[genreArray.length - 1]["name"];
                                            text += "Genres: ";
                                        }

                                        text += genres + "<br>";
                                    }
                                    
                                    text += "Release Date: " + albumObject["release_date"] + "<br>";
                                }//if no error for the album

                                //duration in seconds
                                var duration = track["duration"];
                                var minutes = Math.floor(duration/60);
                                var seconds = duration % 6;
                                var durationString = minutes + ":";
                                
                                if(seconds < 10)
                                    durationString += "0";

                                durationString += seconds;

                                text += "Duration: " + durationString + "<br>";
                                $("p:eq(" + index + ")").html(text); 
                            }//reqAlbum status and readyState
                        }//reqAlbum.onreadystatechange 

                        var urlString = "https://api.deezer.com/album/" + albumID;
                        reqAlbum.open("GET", urlString, true);
                        reqAlbum.send();

                        //do images
                        var artistName = track["artist"]["name"];

                        //get the artist ID to be able to get the picture
                        var artistID = track["artist"]["id"];

                        var reqArtistPic = new XMLHttpRequest();

                        reqArtistPic.onreadystatechange = function(){
                            if(this.status == 200 && this.readyState == 4){
                                var artistObject = JSON.parse(this.responseText);                                
                                var artistImageURL = artistObject["picture_big"];

                                $(".artist:eq(" + index + ")").attr("src", artistImageURL).attr("alt", artistName);
                            }//reqArtistPic status and readyState
                        }//reqArtistPic.onreadystatechange

                        reqArtistPic.open("GET", "https://api.deezer.com/artist/" + artistID, true);
                        reqArtistPic.send();
                        
                        var albumName = track["album"]["title"];
                        var albumImageURL = track["album"]["cover_big"];

                        $(".album:eq(" + index + ")").attr("src", albumImageURL).attr("alt", albumName);

                        //set the audio content
                        var audioURL = track["preview"];
                        var source = "<source src='" + audioURL + "' type='audio/mpeg'>";
                        $("audio:eq(" + index + ")").append(source);

                        if(track["preview"] === ""){
                            //insert a label to say that no preview is available
                            var label = "<label for='audio:eq(" + index + ")'>No song sample available</label>";
                            $("audio:eq(" + index + ")").before(label);
                            $("audio:eq(" + index + ")").remove();
                        }
                    }//reqPlaylistTracks status and readyState
                }//reqPlaylistTracks.onreadystatechange

                reqPlaylistTracks.open("GET", playlistTracksURL, true);
                reqPlaylistTracks.send();       
            });//for each song

            removeEmptySongs();
            $("#loadScreen").delay(6000).fadeOut(); 
        }//reqTopPlaylistsDeezer status and readyState
    }//reqTopPlaylistsDeezer.onreadystatechange

    reqTopPlaylists.open("GET", "https://api.deezer.com/chart/0/playlists", true);
    reqTopPlaylists.send();  
});//document.ready

function removeEmptySongs(){
    $(".EMPTY_SONG").remove();
}

function pauseOthers(ele) {
    $("audio").not(ele).each(function (index, audio) {
        audio.pause();
    });
}