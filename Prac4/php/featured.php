<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
  <head>
    <title>Featured</title>
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
    <script src="../javascript/jquery-3.4.1.min.js"></script>
    <script src="../javascript/audio.js"></script>
    <script src="../javascript/header.js"></script>
    <script src="../javascript/loading.js"></script>
    <script src="../javascript/user_rating.js"></script>
    <link rel="icon" sizes="180x180" href="../images/logo.png">
  
    <style>
      body{
        background-image: url("../images/background.jpg");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
      }

      .songSection
      {
        top: 50px;
      }
    </style>
    
  </head>

  <body>
  <img id="loadScreen" alt="loading" src="../animations/Eclipse-1s-200px.svg">

  <div id="page-container">
   <div id="content-wrap">
    <?php
      require_once('header.php');
      require_once('footer.php');
      require_once('config.php');

      $endTime = time();
      $timeDiff = $endTime - $_SESSION["start_time"];

      //to explicitly test the request to the API or sourcing from the database --> remove and add minus sign respectively
      
      $timeDiff = 1200;

      if($timeDiff >= 1200){//20 minutes
        echo "<script src='../javascript/featured.js'></script>";
      }  
      else{
        //load from the database table featured_song
        $db = Database::instance(); 
        $featuredSongs = $db->getSongs("featured_song");

        buildPage($featuredSongs);      
      }   
      
      function buildPage($set){
        //combine this data with html structure AND echo this to display on the page
        echo "<div class='songSection'>";

        while($song = $set->fetch_assoc()){
          buildSong($song);
        }

        echo "</div>";  
      }

      function buildSong($track){
        $song = (object) $track;

        $text = "";

        $text .= "Title: ".$song->title."<br>";
        $text .= "Artist: ".$song->artist."<br>";

        if($song->genre !== null){
          if(strpos($song->genre, ",") !== false){
            $text .= "Genres: ".$song->genre."<br>";
          }
          else{
            $text .= "Genre: ".$song->genre."<br>";
          }
        }

        $text .= "Release Date: ".$song->releaseDate."<br>";
        $text .= "Duration: ".$song->duration."<br>";        

        $songDiv = "<div class='featuredSong'>";
        $songDiv .= "<img class='artist' src='". $song->artistImageURL ."' alt='artist'";
        $songDiv .= " alt = '". $song->artist ."'>";
        $songDiv .= "<img class='album' src='". $song->albumImageURL ."' alt='album'";
        $songDiv .= " alt = 'Album image for ". $song->title ."'>";
        $songDiv .= "<p>". $text ."</p>";

        $songDiv .= "<audio controls class='featuredAudio' onplay='pauseOthers(this)'>";
        $songDiv .= "<source src='".$song->preview."' type='audio/mpeg'>";
        $songDiv .= "<source src='".$song->preview."' type='audio/mpeg'>";
        $songDiv .= "</audio>";

        $songDiv .= "</div>";

        echo $songDiv;
      }
    ?>
    </div>
    </div>
    
   </body>
</html>