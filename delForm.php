<?php
require_once('conn.php');
session_start();
if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM adminUser WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $id = $_GET['del'];
        // echo $id;
    } else {
        header("Location: index.html");
    }
} else {
    header("Location: index.html");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
</head>

<body>
    <h1>Do you really want to delete this user?</h1>
    <br>
    <h1>Yes - <a href="userDel.php?del=<?php echo $id; ?>">Delete User</a></h1>
    <br>
    <h1>No - <a href="adminDashboard.php">Go back to dashboard</a></h1>
</body>

</html>