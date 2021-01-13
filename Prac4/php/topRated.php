<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
  <head>
    <title>Top Rated</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../javascript/jquery-3.4.1.min.js"></script>
    <script src="../javascript/header.js"></script>
    <script src="../javascript/loading.js"></script>
    <link rel="icon" sizes="180x180" href="../images/logo.png">

    <style>
      body
      {
        background-image: url("../images/background.jpg");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
      }
    </style>
    
  </head>
  
  <body>  
  <div id="page-container">
   <div id="content-wrap">

    <?php
      require_once('header.php');
      require_once('footer.php');
      require_once('config.php');

      echo "<script src='../javascript/topRated.js'></script>";
      ?>
    </div>
    </div>

  </body>
</html>