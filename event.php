<?php
    //Display header
    require ("header.php");
    //Check whether the user is logged in
    require ("loggedCheck.php");

    //Check whether class's ID is set in the URL
    if (isset($_GET['cID'])){
        require ("functions.php");
        //Establish connection with database
        $conn = connect("localhost","gregus.j","jakubdb","gregus.j");
        //Search table for class with such ID
        $resultC = $conn->query("SELECT * FROM AMS_classes WHERE Class_ID='" .$_GET['cID']. "';");
        //Check whether event with such ID exists
        if ($resultC->num_rows > 0){
            //Get event info from table as array
            $rowC = $resultC->fetch_assoc();
            //Declare variables according the data in the table
            $cName = $rowC['Class_name'];
            $cDate = date_format(date_create($rowC['Class_date']),'d/m/Y');
            $cStart = $rowC['Class_start'];
            $cEnd = $rowC['Class_end'];
            $cComm = $rowC['Class_comment'];
        }
    }
    //If class's ID is not set, redirect to home screen
    else header("location: home.php?year=".$_GET['year']."&week=".$_GET['week']);
?>
<body>
    <div class="attend">
        <h2>Attendance of this event</h2>
        <ol>
            <?php
                //Search table for attendance on the event with such ID
                $qAttend = "SELECT * FROM AMS_attendance WHERE Class_ID='".$_GET['cID']."' ORDER BY Student_name;";
                $resultA = $conn->query($qAttend);
                //Check whether such event exists
                if ($resultA->num_rows > 0) {
                    //List the attendees
                    while ($rowA = $resultA->fetch_assoc()) {
                        echo "<li>" . $rowA['Student_name'] . "</li>";
                    }
                }
                else echo "<h3>No attendance yet uploaded</h3>"
            ?>
        </ol>
    </div>
    <div class="event">
        <nav>
            <a id="back" href='<?php echo 'home.php?year='.$_SESSION['year'].'&week='.$_SESSION['week']; ?>'>Back</a>
            <a id='eDelete' onClick="javascript: return confirm('Please confirm deletion!');" href='deleteEvent.php?year=<?php echo $_GET['year']."&week=".$_GET['week']."&cID=".$_GET['cID']; ?>'>Delete Event</a>
        </nav>
        <h2><?php echo $cName.", ".$cDate.", ".$cStart.", ".$cEnd; ?></h2>
        <h4>Comment: <?php echo $cComm; ?></h4>
        <form name="eAttendance" method="post" enctype="multipart/form-data">
            <label>Attendance of the event:</label><br><br>
            <input type="file" name="eAttendance" required>
            <input type="submit" name="submitUpload" value="Upload File">
        </form>
        <a href='<?php echo "event.php?year=".$_GET['year']."&week=".$_GET['week']."&cID=".$_GET['cID']?>'>Reload Page</a>
    </div>
</body>
<?php
    //Check whether upload button was pressed
    if (isset($_POST['submitUpload'])) {
        //Any errors during uploading of the file
        $fileError = $_FILES["eAttendance"]['error'];
        //Size of the file
        $fileSize = $_FILES["eAttendance"]['size'];
        //Temporary filename in directory on the web-server
        $fileTmpName = $_FILES["eAttendance"]['tmp_name'];
        //The location where the file will be placed
        $fileDir = "uploadedFiles/" . basename($_FILES["eAttendance"]["name"]);
        //File extension
        $fileType = strtolower(pathinfo($fileDir,PATHINFO_EXTENSION));
        //Check whether the file is csv
        if ($fileType == 'csv'){
            //Check for any errors during file uploading
            if($fileError === 0){
                //Check whether the size of the file is less than 1 MB
                if ($fileSize < 1000000){
                    //Permissions for the folder, files can be read, written
                    chmod("uploadedFiles/",0777);
                    //Move the file from temporary directory to folder with code of the web-app
                    move_uploaded_file($fileTmpName,$fileDir);
                    //Open the uploaded file
                    $file = fopen($fileDir, "r");
                    //Check whether the file can be opened
                    if ($file !== FALSE) {
                        //Fetch the rows from the file
                        while (($line = fgetcsv($file)) !== FALSE){
                            //Take the first element of the row which is Student_name
                            $student = ($line[0]);
                            //Search the attendance table for attendance of such student on this event
                            $q="SELECT * FROM AMS_attendance WHERE Student_name='" . $student . "' AND Class_ID='".$_GET['cID']."';";
                            $resultU = $conn->query($q);
                            //Check there is no such attendance entry
                            if ($resultU->num_rows == 0) {
                                //Insert attendance of the student of this event into the table
                                $conn->query("INSERT INTO AMS_attendance (Student_name,Class_ID) VALUES('" . $student . "','".$_GET['cID']."');");
                            }
                        }
                        fclose($file);
                        echo "<script>alert('File with attendance successfully uploaded!');</script>";
                    }
                }
                else echo "File is too large!";
            }
            else echo "Error while uploading your file!";
        }
        else echo "Only .csv files are allowed to be uploaded!";
    }
?>