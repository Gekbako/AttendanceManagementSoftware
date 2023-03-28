<?php
    require ("header.php");
    require ("loggedCheck.php");
?>
<body>
    <div class="home">
        <nav>
            <a id="nEvent" href="addEvent.php">New Event</a>
            <a id="sList" href="uploadStudents.php">Upload Student List</a>
            <a id="logout" href="logout.php">Logout</a>
        </nav>
    </div>
    <?php
        require ("calendar.php"); // Execute calendar code
    ?>
</body>
