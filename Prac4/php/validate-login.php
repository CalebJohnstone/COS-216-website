<?php
    require_once("config.php");
    require_once("login.php");

    echo '<script src="../javascript/jquery-3.4.1.min.js"></script>';

    if(isset($_POST)){
        $email = $_POST["email"];
        $password = $_POST["password"];

        //echo $email.",".$password;//
        $validInput = false;

        if(strlen($email) == 0){
            echo "<script type='text/javascript'>alert('You did not enter your email');</script>";
        }
        else if(strpos($email, " ") > -1){
            echo "<script type='text/javascript'>alert('Spaces are not allowed in your email');</script>";
        } 
        else if(strpos($email, '@') === false){
            echo "<script type='text/javascript'>alert('You need a @ in your email');</script>";
        }
        else if(strlen($password) == 0){
            echo "<script type='text/javascript'>alert('You did not enter your password');</script>";
        }
        else if(strpos($password, " ") > -1){
            echo "<script type='text/javascript'>alert('Spaces are not allowed in your password');</script>";
        }
        else{
            echo "<div id='emailValue'>$email</div>";
            echo "<div id='passwordValue'>$password</div>";
            echo "<script src='../javascript/validate-login.js'></script>";

            $validInput = true;
        }

        if(!$validInput){
            echo "<meta http-equiv='refresh' content='0; url=login.php'>";
        }        
    }
?>