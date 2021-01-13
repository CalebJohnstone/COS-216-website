<?php
    class Database{
        private $connection;
        private $host = "wheatley.cs.up.ac.za";
        private $username = "u19030119";
        private $password = "B0r3T353X20";

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
                $this->connection->select_db("u19030119_MUSIC_P4");
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

        public function validAPIkey($API_key){
            $statement = $this->connection->prepare("SELECT * FROM User WHERE API_key = ?");
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

        public function setSeenMessage($API_key){
            $statement = $this->connection->prepare("UPDATE User SET seenMessage = 1 WHERE API_key = ?");
            $statement->bind_param('s', $key); 

            $key = $API_key;

            $statement->execute();
        }

        public function setNotSeenMessage($email){
            $statement = $this->connection->prepare("UPDATE User SET seenMessage = 0 WHERE email = ?");
            $statement->bind_param('s', $e); 

            $e = $email;

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

        public function correctPassword($email, $password){
            $statement = $this->connection->prepare("SELECT * FROM User WHERE email = ? and password = ?");
            $statement->bind_param('ss', $e, $hash);

            $e = $email;
            $salt = $email;

            //get the user's name
            $name = $this->getUserDetail($email, "name");

            if(strlen($name) % 2 === 0){
                $salt .= $name;
            }
            else{
                //get the user's surname
                $surname = $this->getUserDetail($email, "surname");
                $salt .= $surname;
            }

            $hash = hash("gost", $password.$salt);

            //execute the SELECT
            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return true;
            }
            else{
                return false;
            }
        }

        public function getUserID($key){
            $statement = $this->connection->prepare("SELECT * FROM User WHERE API_key = ?");
            $statement->bind_param('s', $k);

            $k = $key;

            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return $row["id"];
            }
            else{
                return null;
            }         
        }

        private function getUserDetail($email, $detail){
            $statement = $this->connection->prepare("SELECT * FROM User WHERE email = ?");
            $statement->bind_param('s', $e);

            $e = $email;

            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return $row[$detail];
            }
            else{
                return null;
            }
        }

        public function getUserKey($email){
            return $this->getUserDetail($email, "API_key");
        }

        public function getMainKey(){
            return $this->getUserKey("calebdjohnstone@gmail.com");
        }

        public function addTrendingSong($track){
            $statement = $this->connection->prepare("INSERT INTO trending_song VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param('ssssssssi', $id, $title, $artist, $album, $albumImageURL, $artistImageURL, $releaseDate, $genre, $ranking);

            $id = $track->id;
            $title = $track->title;
            $artist = $track->artist;
            $album = $track->album;
            $albumImageURL = $track->albumImageURL;
            $artistImageURL = $track->artistImageURL;
            $releaseDate = $track->releaseDate;
            $genre = $track->genre;

            $ranking = 0;
            
            $statement->execute();
        }

        public function addNewReleaseSong($track){
            $statement = $this->connection->prepare("INSERT INTO newRelease_song VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param('ssssssssiss', $id, $title, $artist, $albumImageURL, $artistImageURL, $genre, $label, $review, $ranking, $releaseDate, $album);

            $id = $track->id;
            $title = $track->title;
            $artist = $track->artist;
            $albumImageURL = $track->albumImageURL;
            $artistImageURL = $track->artistImageURL;
            $genre = $track->genre;
            $label = $track->label;
            $review = $track->review;

            $ranking = 0;

            //Prac4 Task 4
            $releaseDate = $track->releaseDate;
            $album = $track->album;
            
            $statement->execute();
        }

        //Prac4 Task 4

        public function addFeaturedSong($track){
            $statement = $this->connection->prepare("INSERT INTO featured_song VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param('sssssssssis', $id, $title, $artist, $albumImageURL, $artistImageURL, $genre, $releaseDate, $duration, $preview, $ranking, $album);

            $id = $track->id;
            $title = $track->title;
            $artist = $track->artist;
            $albumImageURL = $track->albumImageURL;
            $artistImageURL = $track->artistImageURL;
            $genre = $track->genre;
            $releaseDate = $track->releaseDate;
            $duration = $track->duration;
            $preview = $track->preview;    
            
            $ranking = 0;

            //Prac4 Task 4
            $album = $track->album;
            
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

        public function deleteUserPreference($userID, $type){
            $statement = $this->connection->prepare("DELETE FROM user_preference WHERE userID = ? AND type = ?");
            $statement->bind_param('is', $user, $t); 

            $user = $userID;
            $t = $type;

            $statement->execute();
        }

        public function setUserPreference($userID, $type, $value){
            //determine whether we are updating (changing the value that is already there) OR inserting (setting the value for the first time)
            if($this->getUserPreference($userID, $type) !== null){
                $this->updateUserPreference($userID, $type, $value);
            }
            else{
                $this->addUserPreference($userID, $type, $value);
            }
        }

        private function addUserPreference($userID, $type, $value){
            $statement = $this->connection->prepare("INSERT INTO user_preference VALUES (?, ?, ?)");
            $statement->bind_param('iss', $id, $t, $v);
            
            $id = $userID;
            $t = $type;
            $v = $value;
            
            $statement->execute();
        }

        private function updateUserPreference($userID, $type, $value){
            $statement = $this->connection->prepare("UPDATE user_preference SET value = ? WHERE userID = ? AND type = ?");
            $statement->bind_param('sis', $v, $u, $t);
            
            $v = $value;
            $u = $userID;
            $t = $type;
            
            $statement->execute();
        }

        public function getUserPreference($userID, $type){
            $statement = $this->connection->prepare("SELECT * FROM user_preference WHERE userID = ? AND type = ?");
            $statement->bind_param('is', $id, $t);

            $id = $userID; 
            $t = $type;         
            
            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return $row["value"];
            }
            else{
                return null;
            }
        }

        public function setRanking($table, $id, $rank){
            $statement = $this->connection->prepare("UPDATE $table SET ranking = ? WHERE id = ?");
            $statement->bind_param('is', $r, $i);
            
            $r = $rank;
            $i = $id;
            
            $statement->execute();
        }

        public function updateRanking($id, $rank){
            $statement = $this->connection->prepare("UPDATE topRated_song SET ranking = ? WHERE id = ?");
            $statement->bind_param('is', $r, $i);
            
            $r = $rank;
            $i = $id;
            
            $statement->execute();
        }

        public function getSongWithRating($table, $trackID){
            $row = $this->getSong($table, $trackID);
            $row["rating"] = $this->getSongRating($table, $trackID);

            return $row;
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
                return null;//
            }
        }

        private function removeStalePreferences(){
            //genres

            //store the trending_song genres in an array
            $genreArray = array();

            $query = "SELECT genre FROM trending_song";
            $result = $this->connection->query($query);

            while($currentGenreList = $result->fetch_assoc()["genre"]){                
                $currentGenres = explode(", ", $currentGenreList);

                foreach($currentGenres as $genre){
                    if(!in_array($genre, $genreArray)){
                        array_push($genreArray, $genre);
                    }
                }//for each genre in the current list
            }//while result set

            $genres = "DELETE FROM user_preference WHERE type = 'filterGenre' AND value NOT IN (
                '" . implode("\',\'", $genreArray) . "'
            )";
            $this->connection->query($genres);

            //years
            //store the trending_song genres in an array
            $yearsArray = array();

            $query = "SELECT DISTINCT SUBSTRING(releaseDate, 1, 4) AS year FROM trending_song ORDER BY year";
            $result = $this->connection->query($query);

            while($currentYear = $result->fetch_assoc()["year"]){              
                array_push($yearsArray, $currentYear);
            }//while genreLists set

            $years = "DELETE FROM user_preference WHERE type = 'filterYear' AND value NOT IN (
                " . implode(", ", $yearsArray) . "
            )";
            $this->connection->query($years);
        }

        public function getTrendingGenres(){
            $this->removeStalePreferences();

            $query = "SELECT genre FROM trending_song";
            $result = $this->connection->query($query);

            return $result;
        }

        public function getTrendingYears(){
            $query = "SELECT DISTINCT SUBSTRING(releaseDate, 1, 4) AS year FROM trending_song ORDER BY year";
            $result = $this->connection->query($query);
            
            return $result;
        }

        //Prac4 Task 4
        public function getSongRating($table, $id){
            $statement = $this->connection->prepare("SELECT AVG(rating) AS avg_rating FROM user_rating WHERE table_name = ? AND id = ?");
            $statement->bind_param('ss', $t, $i);

            $t = $table;
            $i = $id;          
            
            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return number_format($row["avg_rating"], 2);
            }
            else{
                return null;
            }
        }

        private function deleteStaleRatings(){
            $tables = array(
                "trending_song",
                "newRelease_song",
                "featured_song"
            );

            foreach($tables as $table){
                $statement = $this->connection->prepare("DELETE FROM user_rating WHERE table_name = ? AND id NOT IN (SELECT id FROM $table);");
                $statement->bind_param('s', $t);

                $t = $table;

                $statement->execute();
            }
        }

        public function getTopRatedSongs(){
            $this->deleteStaleRatings();

            $query = "SELECT table_name, id, AVG(rating) AS avg_rating FROM user_rating GROUP BY table_name, id ORDER BY avg_rating, id DESC LIMIT 20";
            $result = $this->connection->query($query);
            
            return $result;
        }

        public function setUserRating($API_key, $table, $id, $rating){
            $userID = $this->getUserID($API_key);

            if($this->getUserRating($userID, $table, $id) === null){
                return $this->addUserRating($userID, $table, $id, $rating);
            }
            else{
                return $this->updateUserRating($userID, $table, $id, $rating);
            }
        }

        private function addUserRating($userID, $table, $id, $rating){
            $statement = $this->connection->prepare("INSERT INTO user_rating VALUES (?, ?, ?, ?)");
            $statement->bind_param('issi', $u, $t, $i, $r);
            
            $u = $userID;
            $t = $table;
            $i = $id;
            $r = $rating;

            $statement->execute();

            return "user rating for song with id = $id added for the first time and set to $rating";
        }

        private function updateUserRating($userID, $table, $id, $rating){
            $statement = $this->connection->prepare("UPDATE user_rating SET rating = ? WHERE table_name = ? AND id = ? AND userID = ?");
            $statement->bind_param('issi', $r, $t, $i, $u);
            
            $r = $rating;
            $t = $table;
            $i = $id;
            $u = $userID;
            

            $statement->execute();

            return "user rating for song with id = $id updated to $rating";
        }

        public function getUserRating($userID, $table, $id){
            $statement = $this->connection->prepare("SELECT * FROM user_rating WHERE userID = ? AND table_name = ? AND id = ?");
            $statement->bind_param('iss', $u, $t, $i);

            $u = $userID;
            $t = $table;
            $i = $id;          
            
            $statement->execute();
            $result = $statement->get_result();

            if($row = $result->fetch_array(MYSQLI_ASSOC)){
                return $row["rating"];
            }
            else{
                return null;//no rating by user
            }
        }
        //Prac4 Task 4
    }
?>