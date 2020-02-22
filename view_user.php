<?php

/* 
 * priveleged user can change other user's pw/access level
 */

session_start();

require_once 'config.php';
require_once 'views/navbar.php';

//user is selected, save his id for later
if($_SERVER["REQUEST_METHOD"] == "GET"){

     if(!empty($_GET["selected_user_id"])){
        
        $_SESSION["selected_user_id"] = htmlspecialchars(trim($_GET["selected_user_id"]));
        
     }
    
}
//init. vars. for html labels
$password_err = $confirm_password_err = "";

    //check permission?
    $user_permision = 0;
if(isset($_SESSION['id'])){
//check current user access level, ok if > 0, 0 is the default value for all users
$sql="SELECT access_level FROM Users WHERE id = ".$_SESSION['id'];

$result = $mysqli->query($sql);

if ($result->num_rows > 0){
    
    while($row = $result->fetch_assoc()) {
      $user_permision = $row['access_level'];
    }
    
       $result->close(); 
} else {
    echo "0 users?";
}//user is not allowed to be here
if ($user_permision <= 0){
  
    header("location: welcome.php");
    exit;
}

}
else
{   //some one may try to acces this page w/o loggin in
    header("location: welcome.php");
    exit;
}
//password is changed, maybe access level is too
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST["password"])){
            
    
    $password = "";
    
    $validate_conf_pass = htmlspecialchars(trim($_POST["conf_password"])); 
    
    $validate_password = htmlspecialchars(trim($_POST["password"]));  
    // Validate password
    if(empty($validate_conf_pass)){
        $confirm_password_err = "Please enter a password in both fields.";     
    }
    elseif(strlen($validate_password) < 6){
        $password_err = "Password must have atleast 6 characters.";
    }
    elseif(strcmp($validate_password, $validate_conf_pass)!== 0 ){
        $confirm_password_err="Passwords doesnt match";
    }
    else{
        $password = htmlspecialchars(trim($_POST["password"]));      
       
    }
 //type in pw's twice correctly to make sure there are no typos  
    if(empty($password_err) && empty($confirm_password_err)){
        $sql = "UPDATE users set password =? WHERE id=?";
       
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql_pw_change = "UPDATE users set password ='".$param_password."' WHERE id=".$_SESSION["selected_user_id"];
        
         if ($mysqli->query($sql_pw_change) === TRUE) {
    echo "Users password successfully changed<br>";
} else {
    echo "Error: " . $mysqli->error;
}
    }

    
            }
//empty() returns false on 0
//give permissions to other users, 0 - base level/default value
 if(!empty($_POST["access_level"]) || $_POST["access_level"] === '0' ){ 
     $level= $_POST["access_level"];
       $sql_permis_change = "UPDATE users set access_level =".$level." WHERE id= ".$_SESSION["selected_user_id"];
       
         if ($mysqli->query($sql_permis_change) === TRUE) {
    echo "Users priveleges successfully changed";
} else {
    echo "Error: " . $mysqli->error;
}
  }   
 
 $mysqli->close(); 
}
?>
<!DOCTYPE html>

    <head>
        <meta charset="UTF-8">
        <link href="views/style.css" rel="stylesheet" type="text/css"/>
        

        <title>View user</title>
    </head>
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <label class="register">Edit Password:</label>
                <input class="register" type="password" id="pass-input" name="password"/>  
                  <label class="register" id="err_msg"><?php echo $password_err ?></label>
                  
                 <label class="register">Reenter New Password:</label>
                 <input class="register" type="password" id="pass-input" name="conf_password"/> 
                   <label class="register" id="err_msg"><?php echo $confirm_password_err ?></label>
                   
                    <label class="register" for="quantity">Change level of user privileges:</label>
                        <input class="register" type="number" id=access-lvl" name="access_level" value="" min="0">
                   
                        <input id='change' type="submit" value="Change" />
        </form>
    </body>
</html>