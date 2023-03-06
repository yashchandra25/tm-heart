<?php
$mysqli = mysqli_connect('localhost:3306', 'root', '', 'hackveda_tmheart');
$actTable = $_POST['ActTable'];
$Act_id = $_POST['Act_id'];
$sql = "UPDATE $actTable SET
        ActivityID = '{$_POST['ActivityID']}',
        HBStart = '{$_POST['HBStart']}',
        HBEnd = '{$_POST['HBEnd']}',
        HBEnd1m = '{$_POST['HBEnd1m']}',
        HBEnd2m = '{$_POST['HBEnd2m']}',
        Drop1m = '{$_POST['Drop1m']}',
        Drop2m = '{$_POST['Drop2m']}',
        RecTime = '{$_POST['RecTime']}',
        RecRate = '{$_POST['RecRate']}',
        MaxRate = '{$_POST['MaxRate']}',
        Status = '{$_POST['Status']}',
        Goal = '{$_POST['Goal']}',
        Date = '{$_POST['Date']}',
        Time = '{$_POST['Time']}'
        WHERE id = $Act_id";
if ($mysqli->query($sql) === TRUE) {
    echo "<h1>Data updated successfully</h1><br>";
    echo "<h1><a href='adminDashboard.php'>Back to dashboard</a></h1>";
} else {
    echo "<h1>Error updating data</h1><br>";
    echo "<h1><a href='adminDashboard.php'>Back to dashboard</a></h1><br>";
    echo "Error: " . $mysqli->error;
}
$mysqli->close();
?>