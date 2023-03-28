<?php
    require ("loggedCheck.php");
    $date = new DateTime; //Crete DateTime object
    if (isset($_GET['year']) and isset($_GET['week'])) {
        $date->setISODate($_GET['year'], $_GET['week']);
    }
    else {
        $date->setISODate($date->format('o'), $date->format('W'));
    }
    $year = $date->format('o');
    $week = $date->format('W');
?>
<body>
    <div class="calen">
        <nav>
            <a href='<?php echo $_SERVER['PHP_SELF'].'?year='.$year.'&week='.($week-1); ?>'>Prev</a>
            <?php echo $date->format('Y')." ";?>
            <a href='<?php echo $_SERVER['PHP_SELF'].'?year='.$year.'&week='.($week+1); ?>'>Next</a>
        </nav>
        <table>
                <?php //Display table with calendar
                    require ("functions.php");
                    $conn = connect("localhost","gregus.j","jakubdb","gregus.j");//Establish connection with database
                    while ($week == $date->format('W')){
                        $_SESSION['year'] = $year; //Variable on web-server holds the year shown in the calendar
                        $_SESSION['week'] = $week; //Variable on web-server holds the week shown in the calendar
                        echo "<tr>";
                        echo "<td>" . $date->format('D j.n.') . "</td>"; //Display day
                        $result = $conn->query("SELECT * FROM AMS_classes WHERE User_ID='" .$_SESSION['uID']. "' 
AND Class_date='". $date->format('Y-m-d') ."' ORDER BY Class_start;"); //Search for events on that day
                        for($i=0;$i<=7;$i++){
                            if ($row = $result->fetch_assoc()){ //Get event from database
                                $cName = $row['Class_name'];
                                $cID = $row['Class_ID'];
                                echo "<td><a href='event.php?year=".$year."&week=".$week."&cID=".$cID."'>".$cName."</a></td>"; //Redirect to page with event info
                            }
                            else echo "<td></td>";
                        }
                        echo "</tr>";
                        $date->modify('+1 day');
                    }
                ?>
        </table>
    </div>
</body>
