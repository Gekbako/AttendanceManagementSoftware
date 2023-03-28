<?php
    require ("header.php");
    require ("loggedCheck.php");
?>
<body>
    <div class="sList">
        <a href='<?php echo 'home.php?year='.$_SESSION['year'].'&week='.$_SESSION['week']; ?>'>Back</a>
        <h2>Upload list of all students:</h2>
        <form method="post" enctype="multipart/form-data">
            <label>List of students:</label><br><br>
            <input type="file" name="sList" required>
            <input type="submit" name="submit" value="Upload File">
        </form>
        <?php
        if (isset($_POST['submit'])) { //Check whether the upload button was clicked
            $fileError = $_FILES["sList"]['error']; //Any file uploading error
            $fileSize = $_FILES["sList"]['size']; //File's size
            $fileTmpName = $_FILES["sList"]['tmp_name']; //Temporary filename in directory on the web-server
            $fileDir = "uploadedFiles/" . basename($_FILES["sList"]["name"]); //The location where the file will be
            $fileType = strtolower(pathinfo($fileDir, PATHINFO_EXTENSION)); //File extension
            if ($fileType == 'csv') { //Check for csv file
                if ($fileError === 0) { //Check for file error
                    if ($fileSize < 1000000) { //Check whether file-size is less than 1 MB
                        chmod("uploadedFiles/", 0777);
                        move_uploaded_file($fileTmpName, $fileDir); //Move the file from the temporary repository to the folder where is code
                        require ("functions.php");
                        $conn = connect("localhost","gregus.j","jakubdb","gregus.j"); //Establish connection with database
                        $file = fopen($fileDir, "r"); //Open file
                        if ($file !== FALSE) { //Check whether file can be opened
                            while (($line = fgetcsv($file)) !== FALSE){ //Get lines from the file
                                $student = ($line[0]); //Take the first element of the line
                                $q="SELECT * FROM AMS_students WHERE Student_name='" . $student . "';"; //Search for student with such name already in table
                                $result = $conn->query($q);
                                if ($result->num_rows == 0) { //Check for student with such name already in table
                                    $conn->query("INSERT INTO AMS_students (Student_name) VALUES('" . $student . "')"); //Insert student
                                }
                            }
                            fclose($file);
                            echo "<script>alert('Student list successfully uploaded!');</script>";
                        }
                    }
                    else echo "File is too large!";
                }
                else echo "Error while uploading your file!";
            }
            else echo "Only .csv files are allowed to be uploaded!";
        }
        ?>
    </div>
</body>
