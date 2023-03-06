<?php
require_once('conn.php');
session_start();
if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM adminUser WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $actID = $_GET['actID'];
        $actTable = $_GET['actTable'];
        $sql = "SELECT * FROM $actTable WHERE id = $actID";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            die("Row not found");
        }
        $row = $result->fetch_assoc();
        $conn->close();
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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Activity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow">
        <div class="container">
            <a class="navbar-brand" href="#">TM Heart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="adminDashboard.php">Go back to
                            dashboard</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <a class="btn btn-danger text-white" href="logout.php">Logout</a>
                </span>
            </div>
        </div>
    </nav>
    <div class="container m-5 p-5">
        <form action="activityEditReq.php" method="POST">
            <input type="hidden" name="Act_id" value="<?= $row['ID'] ?>">
            <input type="hidden" name="ActTable" value="<?= $actTable ?>">
            <label>Activity ID:</label>
            <input type="text" name="ActivityID" value="<?= $row['ActivityID'] ?>"><br>
            <label>HBStart:</label>
            <input type="text" name="HBStart" value="<?= $row['HBStart'] ?>"><br>
            <label>HBEnd:</label>
            <input type="text" name="HBEnd" value="<?= $row['HBEnd'] ?>"><br>
            <label>HBEnd1m:</label>
            <input type="text" name="HBEnd1m" value="<?= $row['HBEnd1m'] ?>"><br>
            <label>HBEnd2m:</label>
            <input type="text" name="HBEnd2m" value="<?= $row['HBEnd2m'] ?>"><br>
            <label>Drop1m:</label>
            <input type="text" name="Drop1m" value="<?= $row['Drop1m'] ?>"><br>
            <label>Drop2m:</label>
            <input type="text" name="Drop2m" value="<?= $row['Drop2m'] ?>"><br>
            <label>RecTime:</label>
            <input type="text" name="RecTime" value="<?= $row['RecTime'] ?>"><br>
            <label>RecRate:</label>
            <input type="text" name="RecRate" value="<?= $row['RecRate'] ?>"><br>
            <label>MaxRate:</label>
            <input type="text" name="MaxRate" value="<?= $row['MaxRate'] ?>"><br>
            <label>Status:</label>
            <input type="text" name="Status" value="<?= $row['Status'] ?>"><br>
            <label>Goal:</label>
            <input type="text" name="Goal" value="<?= $row['Goal'] ?>"><br>
            <label>Date:</label>
            <input type="text" name="Date" value="<?= $row['Date'] ?>"><br>
            <label>Time:</label>
            <input type="text" name="Time" value="<?= $row['Time'] ?>"><br>
            <input type="submit" value="Update">
        </form>
    </div>
</body>