<!DOCTYPE html>
  <head>
    <title>New Releases</title>
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
    <script src="../javascript/jquery-3.4.1.min.js"></script>
    <script src="../javascript/header.js"></script>
    <script src="../javascript/loading.js"></script>
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
      require_once('footer.php');
      require_once('header.php');
      require_once('config.php');

      $endTime = time();
      $timeDiff = $endTime - $_SESSION["start_time"];

      if($timeDiff >= 300){//5 minutes
        echo "<script src='../javascript/newReleases.js'></script>";
      }  
      else{
        //load from the database table trending_song
        $db = Database::instance();
        $newReleaseSongs = $db->getSongs("newRelease_song");

        buildPage($newReleaseSongs);      
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
        $text .= "Rating: ".$song->rating."/10<br>";
         
        if(strpos($song->genre, ",") !== false){
          $text .= "Genres: ".$song->genre."<br>";
        }
        else{
          $text .= "Genre: ".$song->genre."<br>";
        }

        $text .= "Record Label: ".$song->label."<br>";

        $songDiv = "<div class='song'>";
        $songDiv .= "<img class='artist' src='". $song->artistImageURL ."'";
        $songDiv .= " alt = '". $song->artist ."'>";
        $songDiv .= "<img class='album' src='". $song->albumImageURL ."'";
        $songDiv .= " alt = 'Album image for song: ". $song->title ."'>";
        $songDiv .= "<p>". $text ."</p>";

        $songDiv .= "<div class='tooltip'>Review<span class='tooltiptext'>".$song->review."</span></div>";
        $songDiv .= "</div>";

        echo $songDiv;
      }
    ?>
      </div>
    </div>

   </body>
</html>