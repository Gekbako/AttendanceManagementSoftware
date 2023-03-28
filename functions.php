<?php
    function connect($server,$user,$pass,$db){
        //Connect to the database, creates mysqli object through witch PHP communicates with SQL
        $conn = mysqli_connect($server,$user,$pass,$db);
        //Check for any connection error
        if (!$conn){
            //Report the connection error
            die("Connection error: ".mysqli_connect_error());
        }
        return($conn); //Returns mysqli object
    }
?>