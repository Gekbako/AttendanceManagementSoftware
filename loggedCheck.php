<?php
    if (!isset($_SESSION['userN'])){ // Check whether the user is logged in
        header("location: login.php"); // If not it redirects the person to login page
    }
?>