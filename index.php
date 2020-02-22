<?php
/*
LOGIN
*/

require_once 'config.php';
// Define variables and initialize with empty values
$password = $name = $login= $captcha = "";
$login_err = $password_err = $captcha_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Check if email is empty
    if(empty($_POST["login"])){
        $login_err = "Please enter login.";
    } else{
        $login = htmlspecialchars(trim($_POST["login"]));
    }
    
    // Check if password is empty
    if(empty($_POST["password"])){
        $password_err = "Please enter your password.";
    } else{
        $password = htmlspecialchars(trim($_POST["password"]));
    }
    //check captcha
     if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
          $captcha_err = 'Please check the the captcha form.';
          
        }
        if(empty($captcha_err)){
        $secretKey = "6LdegtoUAAAAAOhYvJNOVpLyYXTbbPKzF_1ztyiA";
        
        $ip = $_SERVER['REMOTE_ADDR'];
        // post request to server
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' .
                         urlencode($secretKey) .  '&response=' . urlencode($captcha);
        //check server setting for file_get_contents() to be allowed
        $response = file_get_contents($url);
        $responseKeys = json_decode($response,true);
        // should return JSON with success as true
         if(!$responseKeys["success"]) {
                     
                $captcha_err = "Not a legit user / retry Captcha";
        }
        }
       
        
    // Validate credentials
    if(empty($login_err) && empty($password_err) && empty($captcha_err)){
        // Prepare a select statement
        
        $sql = "SELECT id, password FROM users WHERE login = ?";
        $stmt = $mysqli->prepare($sql);
        if($stmt){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_login);
                     
            // Set parameters
            $param_login = $login;
                        
            // Attempt to execute the prepared statement
    if($stmt->execute()){
        // Store result
        $stmt->store_result();
              
        // Check if login exist, if yes then verify password
        if($stmt->num_rows == 1){
        // Bind result variables
        $stmt->bind_result($id, $hashed_password);
            if($stmt->fetch()){
                        
                if(password_verify($password, $hashed_password)){
               // Password is correct, so start a new session        
                    // Store data in session variables
                    session_start();
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["login"] = $login;
                    
                //save login time
                $this_date = date("Y-m-d H:i:s", time());
                    $sql = "UPDATE users SET last_login='".$this_date."' WHERE id=".$id;
                    
                    $mysqli->query($sql);
                    
                    // Redirect user to welcome page/login success
                        header("location: welcome.php");
                            exit();
                        
                } else{
                // Display an error message if password is not valid
                    $password_err = "The password you entered was not valid.";
                }
                            }
                } else{
                    // Display an error message if email doesn't exist
                    $login_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            } 
        }
        
    }
    
    // Close connection
    $mysqli->close();
}
?>
<!DOCTYPE html>
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <head>
        <meta charset="UTF-8">
        <link href="views/style.css" rel="stylesheet" type="text/css"/>
        

        <title></title>
    </head>
    <body>
        <div id = "login-window">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="login-form" method="post">
          <label>Enter Login:</label>
            <input type="text" name="login" value="<?php echo $login; ?>"/>
                <label id ="err_msg" > <?php echo $login_err?> </label>
                
            <label>Enter Password:</label>
               <input type="password" name="password"/> 
               <label id ="err_msg" > <?php echo $password_err?> </label>
            
               <div class="g-recaptcha" data-sitekey="6LdegtoUAAAAANSEr8UNWuF9OztI8MO0K44SuN0r"></div>
                <br/>
               <label id ="captcha_err_msg"> <?php echo $captcha_err?> </label>
                <input id="submit" type="submit" value="LOGIN" />
        </form>
            <p id="donthave"> Don't have an account?</p>
        <?php echo "<a id='donthave' href='register.php'>Register</a>"?>
        </div>
    </body>
</html>
