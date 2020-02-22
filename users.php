<?php
/* 
 *print list of users
 * allow to change user permisions/access level
 */
session_start();
require_once 'config.php';
require_once 'views/navbar.php';

if(!(isset($_SESSION["loggedin"])) || $_SESSION["loggedin"] === false){
header("location: index.php");
    exit;
    
}
$user_permision = 0;
if(isset($_SESSION['id'])){
//check current user access level, ok if > 0
$sql="SELECT access_level FROM Users WHERE id = ".$_SESSION['id'];
//run sql

$result = $mysqli->query($sql);

if ($result->num_rows > 0){
    
    while($row = $result->fetch_assoc()) {
      $user_permision = $row['access_level'];
    }
       $result->close(); 
} else {
    echo "0 users?";
}
if ($user_permision <= 0){
    
    header("location: welcome.php");
    exit;
}
else{
//
$sql = "SELECT id, login, name, surname, last_login, access_level FROM Users WHERE id <>".$_SESSION['id'];

$other_users = $mysqli->query($sql);
}
?>
<!DOCTYPE html>
<head> 
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="views/style.css">
</head>
 <body>
     <h3> User list</h3>
 <table id="live-table" border="1" width="1" cellspacing="1" cellpadding="2">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Login</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Last Login</th>
                <th>Access Level</th>             
                </tr>
        </thead>
        <tbody id='user_list'>
<?php

if ($other_users->num_rows > 0){
    
    while ($row = $other_users->fetch_row()) {
        
          echo "<tr id='table_row'>".
                "<td>"."<a href='view_user.php?selected_user_id=".$row[0]."'>".$row[0]."</a></td>".
                "<td>".$row[1]."</td>".
                "<td>".$row[2]."</td>".
                "<td>".$row[3]."</td>".
                "<td>".$row[4]."</td>".
                "<td>".$row[5]."</td>".
               
                "<tr>";
            }
    }
      $other_users->close();

}

$mysqli->close();
?>
      </tbody>
    </table>
</body>
</html>