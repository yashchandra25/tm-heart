<?php
session_start();
if (isset($_SESSION['email'])) {
    require_once('conn.php');
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM profile WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo $_SESSION['email'];
        echo "<br>";
        echo "<a href='logout.php'>Logout</a>";
    } else {
        header("Location: index.html");
    }
} else {
    header("Location: index.html");
}