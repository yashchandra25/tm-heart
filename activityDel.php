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
        <form action="activityDelReq.php" method="POST">
            <input type="hidden" name="Act_id" value="<?= $row['ID'] ?>">
            <input type="hidden" name="ActTable" value="<?= $actTable ?>">
            <h1>Do you really want to delete this record?</h1><br>
            <input type="submit" value="Delete">
        </form>
    </div>
</body>