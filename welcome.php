<!DOCTYPE html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" name="viewport" 
                      content="width=device-width" initial-scale=1"
        </meta>
        <link href="views/style.css" rel="stylesheet" type="text/css"/>
        <script src="views/function.js" type="text/javascript"> </script>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
       
        <script src="https://www.google.com/reCAPTCHA/api.js" async defer></script>
        <script> 
//"live table", refreshing page too, not good.
//        $(document).ready( function () {
//     setInterval(showTable,1000);
//} );
   
        </script>

        <title></title>
    </head>
    <body>
<?php


/* 
 * 
 */
//u can go here only if u r logged in
session_start();

require_once 'views/navbar.php';
require_once 'config.php';

if(!(isset($_SESSION["loggedin"])) || $_SESSION["loggedin"] === false){
header("location: index.php");
    exit;
}
 
$sql = "SELECT * FROM winnings";


   
?>
        <h3> Winnings list</h3>
        <button id="export" onclick="exportTableToExcel('live-table')">Export Table Data To Excel File</button>
    <table id="live-table" border="1" width="1" cellspacing="1" cellpadding="2">
        <thead>
            <tr>
                <th>Win Number</th>
                <th>User ID</th>
                <th>Round number</th>
                <th>Reward amount</th>
                <th>Win name</th>
                <th>Casino name</th>
                <th>Win date</th>
                <th>Currency</th>
            </tr>
        </thead>
        <tbody id='table_row'>
            <?php
            if ($result = $mysqli->query($sql)) {
                
                while ($row = $result->fetch_row()) {
            
            echo "<tr>".
                "<td>".$row[0]."</td>".
                "<td>".$row[1]."</td>".
                "<td>".$row[2]."</td>".
                "<td>".$row[3]."</td>".
                "<td>".$row[4]."</td>".
                "<td>".$row[5]."</td>".
                "<td>".$row[6]."</td>".
                "<td>".$row[7]."</td>".
                "<tr>";
            }
            
                }
                    $result->close();
            ?>
        </tbody>
    </table>
</body>
</html>

 
