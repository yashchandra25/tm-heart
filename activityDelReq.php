<?php
$mysqli = mysqli_connect('localhost:3306', 'root', '', 'hackveda_tmheart');
$actTable = $_POST['ActTable'];
$Act_id = $_POST['Act_id'];
$sql = "DELETE FROM $actTable WHERE id = $Act_id";
$mysqli->query($sql);
$mysqli->close();
echo "<h1>Deleted Successfully</h1><br>";
echo "<h1><a href='adminDashboard.php'>Back to dashboard</a></h1><br>";
exit();
?>