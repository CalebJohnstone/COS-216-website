<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
  <head>
    <title>Trending</title>
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
    <script src="../javascript/jquery-3.4.1.min.js"></script>
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

      .footer{
        bottom: -150px;
      }
    </style>

  </head>

  <body>  
  <div id="page-container">
   <div id="content-wrap">
   
    <?php
      require_once('header.php');
      require_once('filterBar.php');
      require_once('footer.php');
      require_once('config.php');

      $endTime = time();
      $timeDiff = $endTime - $_SESSION["start_time"];

      //to explicitly test the request to the API or sourcing from the database --> remove and add minus sign respectively
      
      $timeDiff = 300;

      if($timeDiff >= 300){//5 minutes       
        echo "<script src='../javascript/trending.js'></script>";
      }  
      else{
        //load from the database table trending_song
        $db = Database::instance(); 
        $trendingSongs = $db->getSongs("trending_song");

        buildPage($trendingSongs);      
      }

      echo "<script src='../javascript/searchPage.js'></script>";
      
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

       /* highlight_string("<?php\n\$data =\n" . var_export($song, true) . ";\n?>"); */

        $text = "";

        $text .= "Title: ".$song->title."<br>";
        $text .= "Artist: ".$song->artist."<br>";
        $text .= "Release Date: ".$song->releaseDate."<br>";
        $text .= "Rating: ".$song->rating."/10<br>";
         
        if(strpos($song->genre, ",") !== false){
          $text .= "Genres: ".$song->genre."<br>";
        }
        else{
          $text .= "Genre: ".$song->genre."<br>";
        }

        $text .= "Album: ".$song->album."<br>";

        $songDiv = "<div class='song'>";
        $songDiv .= "<img class='artist' src='". $song->artistImageURL ."'";
        $songDiv .= " alt = '". $song->artist ."'>";
        $songDiv .= "<img class='album' src='". $song->albumImageURL ."'";
        $songDiv .= " alt = '". $song->album ."'>";
        $songDiv .= "<p>". $text ."</p>";
        $songDiv .= "</div>";

        echo $songDiv;
      }
    ?>
      </div>
    </div>

  </body>
</html>