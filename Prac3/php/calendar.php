<!DOCTYPE html>
  <head>
    <title>Calendar</title>
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
    <script src="../javascript/jquery-3.4.1.min.js"></script>
    <script src="../javascript/header.js"></script>
    <script src="../javascript/calendar.js"></script>
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
    </style>
  
  </head>

  <body> 
  <img id="loadScreen" alt="loading" src="../animations/Eclipse-1s-200px.svg">
  
  <div id="page-container">
   <div id="content-wrap">
    <?php 
      require_once('header.php');
      require_once('config.php');

      $GLOBALS["dates"] = array();

      $endTime = time();
      $timeDiff = $endTime - $_SESSION["start_time"];

      if($timeDiff >= 300){//5 minutes
        echo "<script src='../javascript/calendarRequest.js'></script>";
      }  
      else{
        //load from the database table trending_song
        $db = Database::instance(); 
        $calendarSongs = $db->getSongs("trending_song");

        buildPage($calendarSongs);      
      }  
      
      function buildPage($set){
        while($song = $set->fetch_assoc()){
          addDate($song);
        }

        echo '<div id="datesArray">';
        
        for($i = 0; $i < count($GLOBALS["dates"]); $i++){
          echo '<div class="date">';
          echo '<div class="dateText">'.$GLOBALS["dates"][$i]->text.'</div>';
          echo '<div class="dateYear">'.$GLOBALS["dates"][$i]->year.'</div>';
          echo '<div class="dateMonth">'.$GLOBALS["dates"][$i]->month.'</div>';
          echo '<div class="dateDay">'.$GLOBALS["dates"][$i]->day.'</div>';
          echo '</div>';
        }

        echo '</div>';
      }

      function addDate($track){
        $song = (object) $track;

        //store the artist: title, (year, month, day) in the $GLOBALS["dates"] array
        $currentDate = new stdClass();

        $currentDate->text = $song->artist.": ".$song->title;
        $currentDate->year = substr($song->releaseDate, 0, 4);
        $currentDate->month = substr($song->releaseDate, 5, 2);
        $currentDate->day = substr($song->releaseDate, 8);

        array_push($GLOBALS["dates"], $currentDate);
      }      
    ?> 
    </div>
  </div>
    
   </body>
</html>