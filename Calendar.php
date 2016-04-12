<html>

    <?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Comp205P";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
    
// Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 

?>
    
    <head>
        <style>
            table, td, th{
                border: 1px solid black;
                border-collapse: collapse;
            }
            
            th, td{
                width: 50px;
                height: 50px;
                text-align: center;
            }
        </style>
        
        <script>
            
            function goLastMonth(month, year){
                if(month == 1)
                    {
                        --year;
                        month = 13;
                    }
                --month;
                var monthstring= ""+month+"";
                var monthlength = monthstring.length;
                if(monthlength <=1){
                    monthstring = "0" + monthstring;
                }
                document.location.href ="<?php $_SERVER['PHP_SELF'];?>?month="+monthstring+"&year="+year;
            }
            
            function goNextMonth(month, year){
                if (month == 12)
                    {
                        ++year;
                        month = 0;
                    }
                ++month;
                var monthstring= ""+month+"";
                var monthlength = monthstring.length;
                if(monthlength <=1){
                    monthstring = "0" + monthstring;
                }
                document.location.href ="<?php $_SERVER['PHP_SELF'];?>?month="+monthstring+"&year="+year;
            }
            
        </script>
    </head>
    
    <body>
        <?php 
        
        //Get current date
        if (isset($_GET['day']) == true){
            $day = $_GET['day'];
        } 
        else {
            $day = date("j");
        }
        if(isset($_GET['month'])== true){
            $month = $_GET['month'];
        } 
        else {
            $month = date("n");
        }
        if(isset($_GET['year']) == true){
            $year = $_GET['year'];
        }  
        else{
            $year = date("Y");
        }
        
        //Store the date in timestamp
        $currentTimeStamp = strtotime("$year-$month-$day");
        $monthName = date ("F", $currentTimeStamp);
        $numDays = date ("t", $currentTimeStamp);
        
        $counter = 0;
        ?>
        <table>
            <tr>
                <th><input type='button' value='<' name='previousbutton' onclick="goLastMonth(<?php echo $month.",".$year;?>)"></td>
                <th colspan="5"><?php echo $monthName.", ".$year?></td>
                <th><input type='button' value='>' name='nextbutton' onclick="goNextMonth(<?php echo $month.",".$year;?>)"></td>
            </tr>
            
            <tr>
                <th>Sun</th>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
            </tr>

            <?php
                echo "<tr>";
                    
                    for($i = 1; $i < $numDays+1; $i++, $counter++){
$timeStamp = strtotime("$year-$month-$i");
if($i == 1) {
 $firstDay = date("w", $timeStamp);
 for($j = 0; $j < $firstDay; $j++, $counter++) {
 echo "<td> </td>";
 }
}
if($counter % 7 == 0) {
echo"</tr><tr>";
}


echo "<td>$i</td>";

}
while($counter % 7 != 0) {
 echo "<td></td>";
 $counter++;
}
echo "</tr>";
?>
</table>
    <br>

 <?php
$sql = "SELECT id, title, date, time, link FROM Event ORDER BY date";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr class='blue'><th colspan='3' >Upcoming Events</th><tr>";
    echo "<tr class='violet'><th class='date'>Date</th><th class='time'>Time</th><th class='title'>Title</th></tr>";
     // output data of each row
     while($row = $result->fetch_assoc()) {
         
         $time = strtotime($row["date"]);
         
         if(date('Y', $time) > date("Y"))
         {
             $link = $row["link"];
             echo "<tr>";
             echo "<td>" .$row["date"]."</td>";
             echo "<td>" .$row["time"]. "</td>";
             echo "<td><a href=".$link." target='_blank'>" .$row["title"]. "</td>";
             echo "</tr>";
         }
         else
         {
             if(date('m', $time) > date("m"))
             {
                 $link = $row["link"];
                 echo "<tr>";
                 echo "<td>" .$row["date"]."</td>";
                 echo "<td>" .$row["time"]. "</td>";
                 echo "<td><a href=".$link." target='_blank'>" .$row["title"]. "</td>";
                 echo "</tr>";
             }
             else if(date('m', $time) == date("m"))
             {
                 if(date('d', $time) >= date("j"))
                 {
                     $link = $row["link"];
                     echo "<tr>";
                     echo "<td>" .$row["date"]."</td>";
                     echo "<td>" .$row["time"]. "</td>";
                     echo "<td><a href=".$link." target='_blank'>" .$row["title"]. "</td>";
                     echo "</tr>";
                 }
             }

         }
         
         
         
         /*if (date('Y', $time) >= date("Y") && date('m', $time) >= date("m") && date('d', $time) >= date("j"))
         {
             $link = $row["link"];
             echo "<tr>";
             echo "<td>" .$row["date"]."</td>";
             echo "<td>" .$row["time"]. "</td>";
             echo "<td><a href=".$link.">" .$row["title"]. "</td>";
             echo "</tr>";
         }*/
         
     }
     echo "</table>";
    } else {
    echo "<table><tr class='blue'><th colspan='3' >Upcoming Events</th></tr>";
    echo "<tr class='violet'><th class='date'>Date</th><th class='time'>Time</th><th class='title'>Title</th></tr>";
    echo "<tr><th colspan='3'>More events are coming!</th></tr>";
    echo "</table>";
    }

$conn->close();
    ?>
    </body>

</html>