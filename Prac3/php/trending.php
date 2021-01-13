<!DOCTYPE html>
  <head>
    <title>Trending</title>
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
      require_once('footer.php');
    ?>

    <div id="searchBar">
      <input type="text" id="searchTerm" placeholder="search for what you want">
      <button type="button" onclick="clearSearch();" id="reloadBtn">Clear</button>
    </div>

    <div id="searchFilter">
      <h3>Sort according to:</h3>
      <input type="radio" name="searchFilter" onclick="determineOperations()" value="artist">Artist
      <input type="radio" name="searchFilter" onclick="determineOperations()" value="title">Song Title
      <input type="radio" name="searchFilter" onclick="determineOperations()" value="album">Album

      <button type="button" id="clearSortBtn" onclick="clearSort()">Clear</button>

      <div id="filterFields">
        <h3>Filter: </h3>
        <label for="genreSelect">Genre</label>
        <select id="genreSelect" onchange="determineOperations()"></select>
        <button type="button" id="clearGenreBtn" onclick="clearGenreFilter()">Clear</button>

        <label for="yearSelect">Year</label>
        <select id="yearSelect" onchange="determineOperations()"></select>
        <button type="button" id="clearYearBtn" onclick="clearYearFilter()">Clear</button>
      </div>
    </div> 

    <img id="loadScreen" alt="loading" src="../animations/Eclipse-1s-200px.svg">

    <?php
      require_once('config.php');

      $endTime = time();
      $timeDiff = $endTime - $_SESSION["start_time"];

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