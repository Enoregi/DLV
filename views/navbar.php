<?php
$username = "";
if(isset($_SESSION['login'])){
    $username=$_SESSION['login'];
}
?>

<html>

<div>

<ul>
  <li><a class="active" href="welcome.php">Winnings list</a></li>
  <li><a href="users.php">User list</a></li>
  <li id='logout' ><a href="signout.php">Logout from <?php echo $username;?> </a></li>
  
</ul>

    </div>
</html>
