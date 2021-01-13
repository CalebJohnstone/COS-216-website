<!DOCTYPE html>
  <head>
    <title>Log In</title>
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
    <script src="../javascript/jquery-3.4.1.min.js"></script>
    <link rel="icon" sizes="180x180" href="../images/logo.png">
    <script src="../javascript/login.js"></script>
    <script src="../javascript/password.js"></script>
    
    <style>
      body{
        background-image: url("../images/background.jpg");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
      }

      .footer{
        position : fixed;
        bottom : 0px;
      }

      form{
        top: 150px;
      }
    </style>
  
  </head>

  <body> 
    <?php
      require_once("header.php");
    ?>  

    <form action="validate-login.php" method="POST" onsubmit="return validLogin()">
      E-mail:<br><input type="text" name="email"><br>
      Password:<br><input type="password" name="password"><button id="seePswdLogin" type="button" onclick="togglePasswordVisibility()">See</button><br>
      <button type="submit">Log In</button>
    </form>

   </body>
</html>