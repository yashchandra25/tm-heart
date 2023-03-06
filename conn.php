<?php
$conn = mysqli_connect('localhost:3306', 'root', '', 'hackveda_tmheart');

// $host = 'localhost';
// $user = 'trust373_heart';
// $pass = 'AhN$K@qKMzi(';
// $dbname = 'trust373_heart';

// $conn = new mysqli($host, $user, $pass, $dbname);


// $conn = mysqli_connect('hackveda.in', 'hackveda_yashchandra', 'Tz+aZwCrgjv,', 'hackveda_tmheart');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>