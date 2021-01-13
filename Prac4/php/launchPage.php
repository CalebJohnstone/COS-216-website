<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
  <html lang="en">
  <head>
    <title>RED PILL MUSIC</title>
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
    <script src="../javascript/jquery-3.4.1.min.js"></script>
    <script src="../javascript/launchPage.js"></script>
    <link rel="icon" sizes="180x180" href="../images/logo.png">

  </head>

  <body>
      <?php
        require_once('header.php');
        require_once('footer.php');
        require_once('config.php');
        
        $_SESSION["start_time"] = time();
      ?>

     <img id="welcome" alt="Welcome Sign" src="../images/welcome.jpg">

      <div id="helloBlock">
        <img id="glad" alt="We are glad you are here sign" src="../images/welcomeglad.png">
        <img id="explore" alt="Explore Sound" src="../images/explore.png">
      </div>

  </body>
</html>