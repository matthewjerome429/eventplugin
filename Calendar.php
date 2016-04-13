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
            
            th.violet, tr.violet{
                background-color: #6f2977;
            }
            
            td.gray{
                background-color: lightgray;
            }
            
            th.blue, tr.blue{
                background-color: #0086d4;
            }
            
            th.date{
                width: 90px;
            }
            
            th.time{
                width: 70px;
            }
            
            th.title{
                width: 202px;
            }
            
            td.event{
                background-color: #0086d4;
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
        
        //Print Event Calendar
        <table>
            <tr>
                <th class="blue"><input type='button' value='<' name='previousbutton' onclick="goLastMonth(<?php echo $month.",".$year;?>)"></td>
                <th class="blue" colspan="5"><?php echo $monthName.", ".$year?></td>
                <th class="blue"><input type='button' value='>' name='nextbutton' onclick="goNextMonth(<?php echo $month.",".$year;?>)"></td>
            </tr>
            
            <tr>
                <th class="violet">Sun</th>
                <th class="violet">Mon</th>
                <th class="violet">Tue</th>
                <th class="violet">Wed</th>
                <th class="violet">Thu</th>
                <th class="violet">Fri</th>
                <th class="violet">Sat</th>
            </tr>

            <?php
                echo "<tr>";
                    
                    for ($i = 1; $i < $numDays+1; $i++, $counter++) 
                    {
                        $sql = "SELECT date FROM Event ORDER BY date";
                        $result = $conn->query($sql);
                        $hasEvent = false;
                        
                        if ($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                $time = strtotime($row["date"]);
                                
                                if (date('Y', $time) == $year && date('m', $time) == $month && date('d', $time) == $i)
                                {
                                    $hasEvent = true;
                                    break;
                                }

                            }
                        }
                        else
                        {
                            $hasEvent = false;
                        }
                        
                        $timeStamp = strtotime("$year-$month-$i");
                        
                        if($i == 1)
                        {
                            $firstDay = date ("w", $timeStamp);
                            for ($j = 0; $j < $firstDay; $j++, $counter++)
                            {
                                //Print Blank Space
                                echo "<td class= 'gray'>&nbsp;</td>";
                            }
                        }
                        
                        if($counter % 7 == 0)
                        {
                            echo "</tr><tr>";
                        }
                        
                        $monthstring = $month;
                        $monthlength = strlen($monthstring);
                        $daystring = $i;
                        $daylength = strlen($daystring);
                        if($monthlength <= 1)
                        {
                            $monthstring = "0".$monthstring;
                        }
                        if($daylength <=1)
                        {
                            $daystring = "0".$daystring;
                        }
                        
                        if($year < date("Y"))
                        {
                            //fill past date with gray
                            echo "<td class='gray'>".$i."</td>";
                        }
                        else if($year > date("Y"))
                        {
                            if($hasEvent == 1)
                                    {
                                        //highlight date that has event
                                        echo "<td class='event'><a href='http://google.com'>".$i."</td>";
                                    }
                                    else
                                    {
                                        //Normal date
                                        echo "<td>".$i."</td>";
                                    }
                        }
                        else
                        {
                            if($month < date("n"))
                            {
                                //fill past date with gray
                                echo "<td class='gray'>".$i."</td>";
                            }
                            else if($month > date("n"))
                            {
                                if($hasEvent == 1)
                                    {
                                        //highlight date that has event
                                        echo "<td class='event'><a href='http://google.com'>".$i."</td>";
                                    }
                                    else
                                    {
                                        //Normal date
                                        echo "<td>".$i."</td>";
                                    }
                            }
                            else
                            {
                                if($i < date("j"))
                                {
                                    //fill past date with gray
                                    echo "<td class='gray'>".$i."</td>";
                                }
                                else
                                {
                                    if($hasEvent == 1)
                                    {
                                        //highlight date that has event
                                        echo "<td class='event'><a href='http://google.com'>".$i."</td>";
                                    }
                                    else
                                    {
                                        //Normal date
                                        echo "<td>".$i."</td>";
                                    }
                                }
                            }
                        }
                        
                        //echo "<td>".$i."</td>";
                        //echo "<td><a href='".$_SERVER['PHP_SELF']."?month=".$monthstring."&day=".$daystring."&year=".$year."&v=true'>".$i."</td>";
                        
                        
                    }
                    
                echo "</tr>";    
            ?>
        </table>
    <br>

    //Print Event List
    <?php
    $sql = "SELECT id, title, date, time, link FROM Event ORDER BY date";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table><tr class='blue'><th colspan='3' >Upcoming Events</th><tr>";
        echo "<tr class='violet'><th class='date'>Date</th><th class='time'>Time</th><th class='title'>Title</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
         
         $time = strtotime($row["date"]);
         
         //print future year's events
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
             //print future month's events
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
                 //print future date's events
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