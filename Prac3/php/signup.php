<!DOCTYPE html>
  <head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/styles.css" type="text/css">
    <script src="../javascript/jquery-3.4.1.min.js"></script>
    <script src="../javascript/signup.js"></script>
    <script src="../javascript/password.js"></script>
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
    <?php 
        require_once('header.php');
        require_once('footer.php');
    ?> 

    <div class="signUpHeading">Sign Up</div>
    
    <form action="validate-signup.php" method="POST" onsubmit="return validDetails()">
      Name:<br><input type="text" name="name"><br>
      Surname:<br><input type="text" name="surname"><br>
      E-mail:<br><input type="text" name="email"><br>
      Password:<br><input type="password" name="password"><button id="seePswdSignUp" type="button" onclick="togglePasswordVisibility()">See</button><br>
      <button type="submit">Sign Up</button>
    </form>
    
   </body>
</html>