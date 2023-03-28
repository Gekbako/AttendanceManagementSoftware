<?php
    session_start();
    session_unset();
    session_destroy(); //Destroy the variables on the web-server holding username and ID
    echo "<script>alert('You have been logged out!');document.location='login.php'</script>"; //Alert and redirect to login
    //header("location: login.php");
?>
