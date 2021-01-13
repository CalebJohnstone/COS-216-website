<?php
    include_once("COS216/Prac3/php/config.php");

    //create a singleton called RedPillAPI
    class RedPillAPI{
        public $errorStatus = "success";

        public static function instance(){
            static $instance = null;

            if($instance === null){
                $instance = new RedPillAPI();
            }

            return $instance;
        }
        
        private function __construct(){
            //
        }

        public function __destruct(){
            //
        }
        
        public function handleRequest(){
            if(isset($_POST["type"])){
                if($_POST["type"] === "info"){
                    if(isset($_POST["title"])){
                        if(!isset($_POST["return"])){
                            $this->errorStatus = "Error: did not use the return parameter when using the info type and title parameter";
                            return $this->response("");
                        }
                        else if($_POST["return"][0] !== "*"){
                            $this->errorStatus = "Error: invalid value for the return parameter when using the info type and title parameter";
                            return $this->response("");
                        }
                        else if($_POST["title"] === "trending"){//Spotify
                            return $this->response($this->trendingPage());
                        }
                        else if($_POST["title"] === "newReleases"){//Deezer
                            return $this->response($this->newReleasesPage());
                        }
                        else if($_POST["title"] === "topRated"){//Spotify
                            return $this->response($this->topRatedPage());
                        }      
                        else if($_POST["title"] === "featured"){//Deezer
                            return $this->response($this->featuredPage());
                        }    
                        else if($_POST["title"] === "calendar"){//Spotify
                            return $this->response($this->trendingPage());
                        }   
                        else{
                            $this->errorStatus = "Error: invalid value for the title parameter when using the info type";
                            return $this->response("");
                        }                  
                    }
                    else{
                        $this->errorStatus = "Error: did not use the title parameter when using the info type";
                        return $this->response("");
                    }
                }//type=info (the only type so far)
                else{
                    $this->errorStatus = "Error: invalid value for the type parameter";
                    return $this->response(""); 
                }
            }
            else{
                $this->errorStatus = "Error: did not use the type parameter";
                return $this->response("");
            }
        }   
        
        private function trendingPage(){        
            //make API request to Spotify for the "Top Hits South Africa" playlist

            //get the token
            $token = $this->getSpotifyToken()->access_token;

            //get the "Top Hits South Africa" playlist tracks
            $tracks = $this->getTopHitsTracks($token)["items"];
            
            //database
            $db = Database::instance();  
            $db->setZeroRankings("trending_song");

            //songsArray
            $songsArray = array();

            //for each song
            for($i = 0; $i < count($tracks); $i++){
                $currentTrack = $tracks[$i]["track"];

                //store the needed info
                $track = new stdClass();
                $track->id = $currentTrack["id"];

                if($db->getsong("trending_song", $track->id) === null){//add to trending_song if not in the table already
                    $track->title = $currentTrack["name"];

                    //get the track info
                    $trackObject = $this->getTrackObject($token, $track->id);

                    $track->album = $trackObject["album"]["name"];

                    if(!isset($trackObject["album"]["images"][0]["url"])){
                        $track->albumImageURL = "../images/notAvailable.jpg";
                    }
                    else{
                        $track->albumImageURL = $trackObject["album"]["images"][0]["url"]; 
                    }
                       
                    $track->releaseDate = $trackObject["album"]["release_date"];
                    $track->rating = 5 + rand(0,4);
                    $track->artistURL = $trackObject["artists"][0]["href"];

                    $track->genre = "";

                    //get the artist info
                    $artistObject = $this->getArtistObject($token, $track->artistURL);
        
                    $track->artist = $artistObject["name"];

                    if(!isset($artistObject["images"][0]["url"])){
                        $track->artistImageURL = "../images/notAvailable.jpg";
                    }
                    else{
                        $track->artistImageURL = $artistObject["images"][0]["url"];
                    }                        

                    $genres = $artistObject["genres"];

                    if(count($genres) === 0){
                        $track->genre = "";
                    }
                    else{
                        for($a = 0; ($a < count($genres)-1) && ($a < 3); $a++){
                            $track->genre .= $genres[$a] . ", ";
                        }

                        $track->genre .= $genres[count($genres)-1];
                    }

                    //insert into the trending_song table
                    $db->addTrendingSong($track);
                }//if new trending song

                //set the ranking
                $db->setRanking("trending_song", $track->id, $i+1);

                $song = $db->getSong("trending_song", $track->id);
                array_push($songsArray, $song);
            }//for each song

            //delete all of the zero ranks
            $db->deleteZeroRanks("trending_song");
            
            return $songsArray;
        }

        private function topRatedPage(){        
            //make API request to Spotify for the "New Music Friday South Africa" playlist

            //get the token
            $token = $this->getSpotifyToken()->access_token;

            //get the "New Music Friday South Africa" playlist tracks
            $tracks = $this->getNewMusicTracks($token)["items"];
            
            //database
            $db = Database::instance();
            $db->setZeroRankings("topRated_song");  

            //songsArray
            $songsArray = array();

            //for each song
            for($i = 0; $i < count($tracks); $i++){
                $currentTrack = $tracks[$i]["track"];

                //store the needed info
                $track = new stdClass();
                $track->id = $currentTrack["id"];

                if($db->getsong("topRated_song", $track->id) === null){//add to trending_song if not in the table already
                    $track->title = $currentTrack["name"];

                    //get the track info
                    $trackObject = $this->getTrackObject($token, $track->id);

                    $track->album = $trackObject["album"]["name"];

                    if(!isset($trackObject["album"]["images"][0]["url"])){
                        $track->albumImageURL = "../images/notAvailable.jpg";
                    }
                    else{
                        $track->albumImageURL = $trackObject["album"]["images"][0]["url"]; 
                    }

                    $track->releaseDate = $trackObject["album"]["release_date"];
                    $track->ranking = ($i+1);
                    $track->artistURL = $trackObject["artists"][0]["href"];
                    $track->genre = "";

                    //get the artist info
                    $artistObject = $this->getArtistObject($token, $track->artistURL);

                    $track->artist = $artistObject["name"];

                    if(!isset($artistObject["images"][0]["url"])){
                        $track->artistImageURL = "../images/notAvailable.jpg";
                    }
                    else{
                        $track->artistImageURL = $artistObject["images"][0]["url"];
                    }

                    $genres = $artistObject["genres"];
          
                    if(count($genres) === 0){
                        $track->genre = "";
                    }
                    else{
                        for($a = 0; ($a < count($genres)-1) && ($a < 3); $a++){
                            $track->genre .= $genres[$a] . ", ";
                        }

                        $track->genre .= $genres[count($genres)-1];
                    }                                       
          
                    //insert into the trending_song table
                    $db->addTopRatedSong($track);
                }//if new trending song
                else{
                    $db->updateRanking($track->id, $i+1);
                }

                //set the ranking
                $db->setRanking("topRated_song", $track->id, $i+1);
      
                $song = $db->getSong("topRated_song", $track->id);
                array_push($songsArray, $song);
            }//for each song

            //delete all of the zero ranks
            $db->deleteZeroRanks("topRated_song");
            
            return $songsArray;
        }

        private function getSpotifyToken(){
            // create curl resource
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/api/token");

            $request_headers = array(
                "Content-Type:" . "application/x-www-form-urlencoded",
                "Authorization:" . "Basic ZjVmYjAxMzRlMDI4NDA5NzkyN2ZjYWNhNDEzZTIyNmM6ZDU3YzRlMGFkYWIyNGU5YzgwNWNkYjA4YzJlOWQyMDY="
            );

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");

            $username = "u19030119";
            $password = "B0r3T353X20";
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

            // Set request method to POST
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

            $parameters = "grant_type=client_credentials";
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

            // $output contains the output string
            $output = curl_exec($ch);

            //$output = false;

            if($output === false){
                $this->errorStatus = "Error: Spotify API token unavailable";
                return;
            }

            // close curl resource to free up system resources
            curl_close($ch);  

            return json_decode($output);
        }

        private function spotifyRequest($token, $url){
            // create curl resource
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL, $url);

            $request_headers = array(
                "Authorization:" . "Bearer ".$token
            );

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");

            $username = "u19030119";
            $password = "B0r3T353X20";
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

            // Set request method to GET
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            // $output contains the output string
            $output = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch);  

            return json_decode($output, true);
        }

        private function getTopHitsTracks($token){
            return $this->spotifyRequest($token, "https://api.spotify.com/v1/playlists/37i9dQZF1DWWW9iyuOPGds/tracks");
        }

        private function getNewMusicTracks($token){
            return $this->spotifyRequest($token, "https://api.spotify.com/v1/playlists/37i9dQZF1DXd0uyASpbU8w/tracks");
        }

        private function getTrackObject($token, $trackID){
            return $this->spotifyRequest($token, "https://api.spotify.com/v1/tracks/".$trackID);
        }

        private function getArtistObject($token, $artistURL){
            return $this->spotifyRequest($token, $artistURL);
        }

        private function newReleasesPage(){
            //get the top tracks
            $tracks = $this->getDeezerChartTracks();
            $data =  $tracks["data"];

            //return "hello";

            //database
            $db = Database::instance(); 
            $db->setZeroRankings("newRelease_song"); 

            //songsArray
            $songsArray = array();

            //for each song
            for($i = 0; $i < count($data); $i++){
                $currentTrack = $data[$i];

                $track = new stdClass();
                $track->id = $currentTrack["id"];

                if($db->getsong("newRelease_song", $track->id) === null){
                    $track->title = $currentTrack["title"];
                    $track->artist = $currentTrack["artist"]["name"];
                    $track->rating = 5 + rand(0,4);
                    
                    $albumID = $currentTrack["album"]["id"];
    
                    //get the album info
                    $albumObject = $this->getDeezerAlbumObject($albumID);
    
                    $track->genre = "";
    
                    $genreArray = $albumObject["genres"]["data"];
    
                    if(count($genreArray) === 1){
                        $track->genre = $genreArray[0]["name"];
                    }
                    else{
                        for($j = 0; $j < count($genreArray) - 1; $j++){
                            $track->genre .= $genreArray[$j]["name"] . ", ";
                        }
    
                        $track->genre .= $genreArray[count($genreArray)-1]["name"];
                    }
    
                    $track->label = $albumObject["label"];
    
                    //images
                    if(!isset($currentTrack["artist"]["picture_big"])){
                        $track->artistImageURL = "../images/notAvailable.jpg";
                    }
                    else{
                        $track->artistImageURL = $currentTrack["artist"]["picture_big"];
                    }

                    if(!isset($currentTrack["album"]["cover_big"])){
                        $track->albumImageURL = "../images/notAvailable.jpg";
                    }
                    else{
                        $track->albumImageURL = $currentTrack["album"]["cover_big"];
                    }                  
                        
                    //review
                    //get the comment
                    $commentsObject = $this->getDeezerComment($albumID);
    
                    $track->review = "";
    
                    if(count($commentsObject["data"]) > 0){
                        $track->review = $commentsObject["data"][0]["author"]["name"].": ".$commentsObject["data"][0]["text"];
                    }
                    else{
                        $track->review = "No reviews yet";
                    }

                    //insert into the trending_song table
                    $db->addNewReleaseSong($track);
              }//else

              //set the ranking
              $db->setRanking("newRelease_song", $track->id, $i+1);

              $song = $db->getSong("newRelease_song", $track->id);
              array_push($songsArray, $song);
            }//for each song

            //delete all of the zero ranks
            $db->deleteZeroRanks("newRelease_song");

            return $songsArray;
        }

        private function featuredPage(){
            //get the top tracks
            $topPlaylistsObject = $this->getDeezerChartPlaylists();
            $data =  $topPlaylistsObject["data"];

            //database
            $db = Database::instance();  
            $db->setZeroRankings("featured_song"); 

            //songsArray
            $songsArray = array();

            //for each song
            for($i = 0; $i < count($data); $i++){
                $topPlaylist = $data[$i];

                $playlistTracksURL = "https://api.deezer.com/playlist/" . $topPlaylist["id"];
                $tracksObject = $this->getPlaylistTracks($playlistTracksURL);
                $trackArray = $tracksObject["tracks"]["data"];
                $currentTrack = $trackArray[floor(count($trackArray)/5)];

                $track = new stdClass();
                $track->id = $currentTrack["id"];

                //check for duplicates
                $x = 0;

                while(true){
                    $duplicate = false;

                    for($j = 0; $j < count($songsArray); $j++){
                        if(strcmp(array_column($songsArray, "id")[$j], $track->id) === 0){
                            $duplicate = true;
                            break;
                        }
                    }

                    if($duplicate){
                        if($x === count($trackArray) - 1){
                            break;
                        }

                        $currentTrack = $trackArray[$x++];
                        $track->id = $currentTrack["id"];
                    }
                    else{
                        break;
                    }
                }

                if($db->getsong("featured_song", $track->id) === null){
                    $track->title = $currentTrack["title"];
                    $track->artist = $currentTrack["artist"]["name"];
                    
                    $albumID = $currentTrack["album"]["id"];
    
                    //get the album info
                    $albumObject = $this->getDeezerAlbumObject($albumID);

                    if(!isset($albumObject["error"])){
                        $track->genre = "";

                        $genreArray = $albumObject["genres"]["data"];

                        if(count($genreArray) == 1){
                            $track->genre = $genreArray[0]["name"];
                        }
                        else{
                            for($a = 0; $a < count($genreArray) - 1; $a++){
                                $track->genre .= $genreArray[$a]["name"].", ";
                            }

                            $track->genre .= $genreArray[count($genreArray)-1]["name"];
                        }

                        $track->releaseDate = $albumObject["release_date"];
                    }
                    
                    $duration = $currentTrack["duration"];
                    $minutes = floor($duration/60);
                    $seconds = $duration % 6;

                    $track->duration = $minutes.":";

                    if($seconds < 10){
                        $track->duration .= "0";
                    }

                    $track->duration .= $seconds;
    
                    //images
                    $artistID = $currentTrack["artist"]["id"];
                    $artistObject = $this->getDeezerArtistObject($artistID);

                    //images
                    if(!isset($artistObject["picture_big"])){
                        $track->artistImageURL = "../images/notAvailable.jpg";
                    }
                    else{
                        $track->artistImageURL = $artistObject["picture_big"];
                    }

                    if(!isset($currentTrack["album"]["cover_big"])){
                        $track->albumImageURL = "../images/notAvailable.jpg";
                    }
                    else{
                        $track->albumImageURL = $currentTrack["album"]["cover_big"];
                    }                  
                       
                    $track->preview = $currentTrack["preview"];
    
                    //fish
                    //insert into the trending_song table
                    $db->addFeaturedSong($track);
                }//else

                //set the ranking
                $db->setRanking("featured_song", $track->id, $i+1);

                $song = $db->getSong("featured_song", $track->id);
                array_push($songsArray, $song);
            }//for each song

            //delete all of the zero ranks
            $db->deleteZeroRanks("featured_song");

            return $songsArray;
        }

        private function deezerRequest($url){
            // create curl resource
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL, $url);

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // Set request method to GET
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");

            $username = "u19030119";
            $password = "B0r3T353X20";
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

            // $output contains the output string
            $output = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch);  

            return json_decode($output, true);
        }

        private function getDeezerArtistObject($artistID){
            return $this->deezerRequest("https://api.deezer.com/artist/".$artistID);   
        }

        private function getPlaylistTracks($playlistTracksURL){
            return $this->deezerRequest($playlistTracksURL);
        }

        private function getDeezerChartPlaylists(){
            return $this->deezerRequest("https://api.deezer.com/chart/0/playlists");
        }

        private function getDeezerChartTracks(){
            return $this->deezerRequest("https://api.deezer.com/chart/0/tracks");
        }

        private function getDeezerAlbumObject($albumID){
            return $this->deezerRequest("https://api.deezer.com/album/".$albumID);
        }

        private function getDeezerComment($albumID){
            return $this->deezerRequest("https://api.deezer.com/album/".$albumID."/comments");
        }

        private function response($data){
            $response = array(
                "status" => $this->errorStatus,
                "timestamp" => time(),
                "data" => $data
            );    
            
            $value = json_encode($response);

            return $value;
        }
    }//RedPillAPI class

    //use the class
    $api = RedPillAPI::instance();
    echo $api->handleRequest();    
?>