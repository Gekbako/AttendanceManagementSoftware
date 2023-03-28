<?php
    require ("loggedCheck.php");
    require ("functions.php");
    $conn = connect("localhost","gregus.j","jakubdb","gregus.j"); //Establish connection with the database
    $qDelete = "DELETE FROM AMS_classes WHERE Class_ID='".$_GET['cID']."';"; //Delete the event in the table
    $conn->query($qDelete);
    header("location: home.php?year=".$_GET['year']."&week=".$_GET['week']); //Redirect to homepage
?>