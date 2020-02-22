<?php
require_once 'config.php';
/* 
 print data for "live" table
 */
$sql = "SELECT * FROM winnings";

            if ($result = $mysqli->query($sql)) {
                
                while ($row = $result->fetch_row()) {
           echo "<tr id='table_row'>".
                "<td>".$row[0]."</td>".
                "<td>".$row[1]."</td>".
                "<td>".$row[2]."</td>".
                "<td>".$row[3]."</td>".
                "<td>".$row[4]."</td>".
                "<td>".$row[5]."</td>".
                "<td>".$row[6]."</td>".
                "<td>".$row[7]."</td>";
               "<tr>";
            }
            
                }
                     $result->close();
                       $mysqli->close();
?>

