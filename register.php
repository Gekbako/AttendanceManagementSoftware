<?php
    require ("header.php");
?>
<body>
    <div class="register">
        <form method="post">
            <h2>Create an account:</h2>
            <label>Firstname:</label><br>
            <input type="text" name="fName"><br>
            <label>Surname:</label><br>
            <input type="text" name="sName"><br>
            <label>Username:</label><br>
            <input type="text" name="userN" required><br>
            <label>Password:</label><br>
            <input type="password" name="pass" required><br>
            <label>Confirm Password:</label><br>
            <input type="password" name="cPass" required><br><br>
            <input type="submit" name="submit" value="Register">
        </form>
        <?php
            if (!empty($_POST["userN"]) and !empty($_POST["pass"])) { //Check whether username and password are not null
                if ($_POST["pass"] == $_POST["cPass"]) { //Check whether password check matches the password
                    require("functions.php");
                    $conn = connect("localhost", "gregus.j", "jakubdb", "gregus.j"); //Establish connection with database
                    $result = $conn->query("SELECT * FROM AMS_users WHERE Username='" . $_POST['userN'] . "';"); //Search table with users for users with given username
                    if ($result->num_rows == 0) { //If the given username is not yet taken
                        $conn->query("INSERT INTO AMS_users (Firstname,Surname,Username,Password) 
        VALUES('" . $_POST["fName"] . "','" . $_POST["sName"] . "','" . $_POST["userN"] . "','" . $_POST["pass"] . "');"); //Add new user to the database
                        echo "<script>alert('You have successfully registered, now please login!');document.location='login.php'</script>";//Alert and redirect to login
                    } else echo "This username already exists!";
                }
                else echo "Password and confirmation password must match!";
            }
            echo "<p>Already have an account? "."<a href='login.php'>Login!</a></p>"; //Reference login
        ?>
    </div>
</body>