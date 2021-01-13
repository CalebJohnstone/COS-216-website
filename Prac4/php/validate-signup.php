<!DOCTYPE html>
<html>
    <head>
        <script src="../javascript/jquery-3.4.1.min.js"></script>
    </head>

<?php
    require_once("config.php");
    require_once("signup.php");

    //store the values in variables
    if(isset($_POST)){
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        if(strlen($name) == 0){
            echo "<script type='text/javascript'>alert('You did not enter your name');</script>";
        }
        else if(strlen($surname) == 0){
            echo "<script type='text/javascript'>alert('You did not enter your surname');</script>";
        }
        else if(strlen($email) == 0){
            echo "<script type='text/javascript'>alert('You did not enter your email');</script>";
        }
        else if(strpos($email, " ") > -1){
            echo "<script type='text/javascript'>alert('Spaces are not allowed in your email');</script>";
        } 
        else if(strlen($password) == 0){
            echo "<script type='text/javascript'>alert('You did not enter your password');</script>";
        }
        else if(strpos($password, " ") > -1){
            echo "<script type='text/javascript'>alert('Spaces are not allowed in your password');</script>";
        } 
        else if(strpos($email, '@') === false){
            echo "<script type='text/javascript'>alert('You need a @ in your email');</script>";
        }
        else if(!validPassword($password)){
            echo "<script type='text/javascript'>alert('Your password needs to have a lowercase letter, an uppercase letter, a number and a symbol. It must also be at least 9 characters in length');</script>";
        }
        else{
            //use the class
            $db = Database::instance();

            if($db->UserExists($email) === false){
                //calculate the hash
                $salt = $email; 

                if(strlen($name) % 2 === 0){
                    $salt .= $name;
                }
                else{
                    $salt .= $surname;
                }

                $hash = hash("gost", $password.$salt);

                //calculate the API_key
                $API_key = createAPIkey();
                $db->addUser($name, $surname, $email, $hash, $API_key);
                
                echo "<script type='text/javascript'>
                    localStorage.setItem('API_key','".$API_key."');
                    localStorage.setItem('seen_message', false);
                </script>";

                echo "<meta http-equiv='refresh' content='0; url=launchPage.php'>";
            }
            else{
                echo "<script src=\"../javascript/accountExists.js\"></script>";
            }
        }
    }

    function createAPIkey(){
        return md5(rand() . microtime());
    }

    function validPassword($password){
        if(strlen($password) > 8 && preg_match("/[A-Z]/", $password) && preg_match("/[a-z]/", $password) && preg_match("/[0-9]/", $password) && preg_match("/[!@#\$%^&*]/", $password)){
            return true;
        }  

        return false;
    }
?>

</html>