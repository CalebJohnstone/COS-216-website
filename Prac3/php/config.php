<?php
   session_start(); // session start

   $GLOBALS["API_key"] = null;

    class Database{
        private $connection;
        private $host = "wheatley.cs.up.ac.za";
        private $username = "u19030119";
        private $password = "";

        public static function instance(){
            static $instance = null;
            if($instance === null){
                $instance = new Database();
            }

            return $instance;
        }

        private function __construct(){
            $this->connection = new MySqli($this->host, $this->username, $this->password);
            
            if($this->connection->connect_error){
                die("connection failure: " . $this->connection->connect_error);
            }
            else{
                $this->connection->select_db("u19030119_MUSIC_P3");
            }
        }

        public function __destruct(){
            //disconnect from the database
            $this->connection->close();
        }

        public function addUser($name, $surname, $email, $hash, $API_key){
            //insert the new User into the User table

            //security: to guard against SQL-injection
            $statement = $this->connection->prepare("INSERT INTO User VALUES (?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param('isssssi', $id, $n, $s, $e, $h, $key, $seen);
            
            $id = null;
            $n = $name;
            $s = $surname;
            $e = $email;
            $h = $hash;
            $key = $API_key;
            $seen = 0;
            
            $statement->execute();
        }

        public function setSeenMessage($API_key){
            $statement = $this->connection->prepare("UPDATE User SET seenMessage = 1 WHERE API_key = ?");
            $statement->bind_param('s', $key);

            $key = $API_key;
            
            $statement->execute();
        }

        public function getSeenMessage($API_key){
            $statement = $this->connection->prepare("SELECT * FROM User WHERE API_key = ? AND seenMessage = 1");
            $statement->bind_param('s', $key);

            $key = $API_key;
            
            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return true;
            }
            else{
                return false;
            }
        }

        function UserExists($email){
            $statement = $this->connection->prepare("SELECT * FROM User WHERE email = ?");
            $statement->bind_param('s', $e);
            
            $e = $email;        
            $statement->execute();
    
            $result = $statement->get_result();
    
            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return true;
            }
            else{
                return false;
            }
        }

        public function getUserAPIkey($email){
            $statement = $this->connection->prepare("SELECT * FROM User WHERE email = ?");
            $statement->bind_param('s', $e);

            $e = $email;
            
            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return $row["API_key"];
            }
            else{
                return null;
            }
        }
        
        public function addTrendingSong($track){
            $statement = $this->connection->prepare("INSERT INTO trending_song VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
            $statement->bind_param('sssssssis', $id, $title, $artist, $album, $albumImageURL, $artistImageURL, $releaseDate, $rating, $genre);

            $id = $track->id;
            $title = $track->title;
            $artist = $track->artist;
            $album = $track->album;
            $albumImageURL = $track->albumImageURL;
            $artistImageURL = $track->artistImageURL;
            $releaseDate = $track->releaseDate;
            $rating = $track->rating;
            $genre = $track->genre;
            
            $statement->execute();
        }

        public function addNewReleaseSong($track){
            $statement = $this->connection->prepare("INSERT INTO newRelease_song VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
            $statement->bind_param('sssssisss', $id, $title, $artist, $albumImageURL, $artistImageURL, $rating, $genre, $label, $review);

            $id = $track->id;
            $title = $track->title;
            $artist = $track->artist;
            $albumImageURL = $track->albumImageURL;
            $artistImageURL = $track->artistImageURL;
            $rating = $track->rating;
            $genre = $track->genre;
            $label = $track->label;
            $review = $track->review;
            
            $statement->execute();
        }

        public function addTopRatedSong($track){
            $statement = $this->connection->prepare("INSERT INTO topRated_song VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param('sssssssis', $id, $title, $artist, $album, $albumImageURL, $artistImageURL,  $releaseDate, $ranking, $genre);

            $id = $track->id;
            $title = $track->title;
            $artist = $track->artist;
            $album = $track->album;
            $albumImageURL = $track->albumImageURL;
            $artistImageURL = $track->artistImageURL;
            $releaseDate = $track->releaseDate;
            $ranking = $track->ranking;
            $genre = $track->genre;
            
            $statement->execute();
        }

        public function addFeaturedSong($track){
            $statement = $this->connection->prepare("INSERT INTO featured_song VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
            $statement->bind_param('sssssssss', $id, $title, $artist, $albumImageURL, $artistImageURL, $genre, $releaseDate, $duration, $preview);

            $id = $track->id;
            $title = $track->title;
            $artist = $track->artist;
            $albumImageURL = $track->albumImageURL;
            $artistImageURL = $track->artistImageURL;
            $genre = $track->genre;
            $releaseDate = $track->releaseDate;
            $duration = $track->duration;
            $preview = $track->preview;            
            
            $statement->execute();
        }

        public function getSongs($table){
            $query = "SELECT * FROM $table";

            if(strcmp($table, "topRated_song") === 0){
                $query .= " ORDER BY ranking";
            }

            $result = $this->connection->query($query);

            return $result;
        }

        public function setZeroRankings($table){
            $query = "UPDATE $table SET ranking = 0";
            $this->connection->query($query);
        }

        public function deleteZeroRanks($table){
            $query = "DELETE FROM $table WHERE ranking = 0";
            $this->connection->query($query);
        }

        public function setRanking($table, $id, $rank){
            $statement = $this->connection->prepare("UPDATE $table SET ranking = ? WHERE id = ?");
            $statement->bind_param('is', $r, $i);

            $i = $id;
            $r = $rank;
            
            $statement->execute();
        }

        public function updateRanking($id, $ranking){
            $statement = $this->connection->prepare("UPDATE topRated_song SET ranking = $ranking WHERE id = ?");
            $statement->bind_param('s', $trackID);

            $trackID = $id;
            
            $statement->execute();
        }

        public function getSong($table, $trackID){
            $statement = $this->connection->prepare("SELECT * FROM $table WHERE id = ?");
            $statement->bind_param('s', $id);

            $id = $trackID;          
            
            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return $row;
            }
            else{
                return null;
            }
        }

        public function getTrendingGenres(){
            $query = "SELECT genre FROM trending_song";
            $result = $this->connection->query($query);

            return $result;
        }

        public function getTrendingYears(){
            $query = "SELECT DISTINCT SUBSTRING(releaseDate, 1, 4) AS year FROM trending_song ORDER BY year";
            $result = $this->connection->query($query);
            
            return $result;
        }
    }
?>