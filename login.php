
<?php
    require ("header.php");
?>
<body>
    <div class="login">
        <h2>Enter your credentials:</h2>
        <form method="post">
            <label>Username: </label><br>
            <input type="text" name="userN" required><br>
            <label>Password: </label><br>
            <input type="password" name="pass" required><br><br>
            <input type="submit" name="submit" value="Login">
        </form>
        <?php
            if (isset($_POST['submit'])){ //Check whether the login button was pressed
                if (!empty($_POST['userN'])){ //Check if there is input a username
                    require ("functions.php");
                    $conn = connect("localhost","gregus.j","jakubdb","gregus.j"); // establish connection with the database
                    $result = $conn->query("SELECT * FROM AMS_users WHERE Username='" .$_POST['userN']. "';");//Search table with users for user with giver username
                    if ($result->num_rows > 0){ // Check whether the username is in the table
                        $row = $result->fetch_assoc(); //Get the users personal credentials
                        if($row["Password"] == $_POST['pass']){ //Check for password, compared password set in registration with the one given
                            $_SESSION['userN'] = $_POST['userN']; //Make variable on the web-server hold the user's username
                            $_SESSION['uID'] = $row['UID']; //Make variable on the web-server hold the user's ID
                            header("location: home.php"); //If requirements above were met, the user is transmitted to home screen
                        }
                        else echo "Wrong password!";
                    }
                    else echo "Unknown username!";
                }
            }
            echo "<p>Don't have an account yet? "."<a href='register.php'>Register!</a></p>"; //Reference the register
        ?>
    </div>
</body>
