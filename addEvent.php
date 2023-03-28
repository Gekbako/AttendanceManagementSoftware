<?php
    require ("header.php");
    require ("loggedCheck.php");
    class Event //Object event
    {
        //Event's parameters
        //Event name
        public $eName;
        //Date of the event
        public $eDate;
        //Start time of the event
        public $eStart;
        //End time of the event
        public $eEnd;
        //User's ID to which the event belongs to
        public $uID;
        //Event comment
        public $eComm;

        //Function for construction event object
        function __construct($eName, $eDate, $eStart, $eEnd, $uID, $eComm)
        {
            $this->eName = $eName;
            $this->eDate = $eDate;
            $this->eStart = $eStart;
            $this->eEnd = $eEnd;
            $this->uID = $uID;
            $this->eComm = $eComm;
        }

        public function getEName(){//Get event's name
            return $this->eName;
        }
        public function getEDate(){//Get event's date
            return $this->eDate;
        }
        public function getEStart(){//Get event's start time
            return $this->eStart;
        }
        public function getEEnd(){//Get event's end time
            return $this->eEnd;
        }
        public function getUID(){//Get ID of the event's owner
            return $this->uID;
        }
        public function getEComm(){//Get event's comment
            return $this->eComm;
        }
    }
?>
<body>
    <div class="newEvent">
        <a href='<?php echo 'home.php?year='.$_SESSION['year'].'&week='.$_SESSION['week']; ?>'>Back</a>
        <h2>Enter details about the event:</h2>
        <form name="addEvent" method="post">
            <label>Name:</label><br>
            <input type="text" name="eName" required><br>
            <label>Date:</label><br>
            <input type="date" name="eDate" required><br>
            <label>Start:</label><br>
            <input type="time" name="eStart" required><br>
            <label>End:</label><br>
            <input type="time" name="eEnd" required><br>
            <label>Comment:</label><br>
            <input type="text" name="eComm"><br><br>
            <input type="submit" name="submitForm" value="Create">
        </form>
        <?php
        //Check whether create-event button was clicked
        if (isset($_POST['submitForm'])) {
            //Check whether the event details are not empty
            if (!empty($_POST['eName']) and !empty($_POST['eDate']) and !empty($_POST['eStart']) and !empty($_POST['eEnd'])) {
                //Connect to database
                require("functions.php");
                $conn = connect("localhost", "gregus.j", "jakubdb", "gregus.j");
                //Construct event object
                $nEvent = new Event($_POST['eName'], $_POST['eDate'], $_POST['eStart'], $_POST['eEnd'], $_SESSION['uID'], $_POST['eComm']);
                //Insert the event into database
                $qSQL = "INSERT INTO AMS_classes (Class_name, Class_date, Class_start, Class_end, User_ID, Class_comment) 
VALUES ('".$nEvent->getEName()."','".$nEvent->getEDate()."','".$nEvent->getEStart()."','".$nEvent->getEEnd()."','".$nEvent->getUID()."','".$nEvent->getEComm()."');";
                //Check whether event was successfully added to the database
                if ($conn->query($qSQL) === TRUE){
                    echo "<script>alert('Event successfully created!');</script>";
                }
                else{
                    echo "Error: ".$conn->error;
                }
            }
        }
        ?>
    </div>
</body>
