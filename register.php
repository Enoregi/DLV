<?php

/* 
 * user can register
 */
// Include config file
include_once 'config.php';

// Define variables and initialize with empty values
$name = $surname = $password = $confirm_password = $login = "";
$login_err = $password_err = $confirm_password_err = $name_err = $surname_err ="";
 
// Processing when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate login
    if(empty(trim($_POST["login"])) ){
      $login_err = "Please enter login.";
    } else{
        //check is the same login already exists
        
        $sql = "SELECT id FROM users WHERE login = ?"; 
        $stmt = $mysqli->prepare($sql);
        
        if($stmt){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_login);
            
            // Set parameters
            $param_login= trim($_POST["login"]);
            
            // Attempt to execute the prepared statement
            
            if($stmt->execute()){
                
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $login_err = "This login is already taken.";
                    
                } else{
                   
                    $name = htmlspecialchars(trim($_POST["name"]));
                   
                    $surname = htmlspecialchars(trim($_POST["surname"]));
                    
                    $login = htmlspecialchars(trim($_POST["login"]));
                  
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                die();
            }
        }
               
    }
    if(empty($login)) $login_err="Please enter your login";
        if(empty($name)) $name_err="Please enter your name";
            if(empty($surname)) $surname_err="Please enter your surname";
       
     $validate_pass = htmlspecialchars(trim($_POST["password"]));
        $validate_conf_pass = htmlspecialchars(trim($_POST["conf_password"])); 
    // Validate password
    if(empty($validate_pass)){
        $password_err = "Please enter a password.";     
    }
    elseif(strlen($validate_pass) < 6){
        $password_err = "Password must have atleast 6 characters.";
    }
    elseif(strcmp($validate_pass, $validate_conf_pass)!== 0 ){
        $confirm_password_err="Passwords doesnt match";
    }
    else{
        $password = htmlspecialchars(trim($_POST["password"]));      
       
    }
    
    // Check input errors before inserting in database
    if(empty($login_err) && empty($password_err) && empty($name_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (login, password, surname, name) VALUES (?, ?, ?, ?);";
          
        if($stmt = $mysqli->prepare($sql)){
            
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $param_login, $param_password, $param_surname, $param_name);
                $stmt->execute();
            
            // Set parameters
            $param_login = $login;
               // Creates a password hash
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_surname = $surname;
            $param_name = $name;
          
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to welcome page               
                 $stmt->close();
                  $mysqli->close();
                //send user to login  
            //TO DO: alert user about success
                 header("location: index.php");
                   exit();      
            } else{
//                $mysqli->close();
                $stmt->close(); 
            }
        }
        
    }
    else
    {
      
       echo "we have some errors";
 // Close connection
   $mysqli->close();
    }
    
    
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
            <title> </title>
            <meta> </meta>
            <link href="views/style.css" rel="stylesheet" type="text/css"/>
           
        </head>
    <body>
        <div id="register-window">
<form action="register.php"  method="post">
    <label class="register">Enter Login:</label>
    <input class="register" type="text" name="login" value="<?php echo $login; ?>"/>
     <label class="register" id="err_msg"><?php echo $login_err ?></label>
     
     <label class="register">Enter Name:</label>
        <input class="register" type="text" id="name-input" name="name" value="<?php echo $name; ?>"/>
         <label class="register" id="err_msg"><?php echo $name_err ?></label>
         
         <label class="register">Enter Surname:</label>
            <input class="register" type="text" name="surname" value="<?php echo $surname; ?>"/>
             <label class="register" id="err_msg"><?php echo $surname_err ?></label>
             
            <label class="register">Enter Password:</label>
                <input class="register" type="password" id="pass-input" name="password"/>  
                  <label class="register" id="err_msg"><?php echo $password_err ?></label>
                  
                 <label class="register">Reenter Password:</label>
                 <input class="register" type="password" id="pass-input" name="conf_password"/> 
                   <label class="register" id="err_msg"><?php echo $confirm_password_err ?></label>
                   
                    <input class="register" id="submit" type="submit" value="SIGN UP"/>
                    
     </form>
       
        <a href="index.php" id="backToLogin"> Login </a>
            
        </div>
</body>
</html>