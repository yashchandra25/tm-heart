<?php
require_once("conn.php");
function test_input($data)
{

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$email = test_input($_POST["email"]);
$password = test_input($_POST["password"]);
$stmt = mysqli_prepare($conn, "SELECT * FROM adminUser WHERE email = ? AND password = ?");
mysqli_stmt_bind_param($stmt, "ss", $email, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    session_start();
    $_SESSION['email'] = $email;
    echo $_SESSION['email'];
    header("Location: adminDashboard.php");
} else {
    header("Location: adminLoginFail.html");
}
mysqli_close($conn);
?>