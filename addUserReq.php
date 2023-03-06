<?php
require_once('conn.php');
session_start();
if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM adminUser WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // $_SESSION['active'] = true;
        function test_input($data)
        {

            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = test_input($_POST["name"]);
            $email = test_input($_POST["email"]);
            $password = test_input($_POST["password"]);
            $mobile = test_input($_POST["mobile"]);
            $age = test_input($_POST["age"]);
            $height = test_input($_POST["height"]);
            $weight = test_input($_POST["weight"]);
            $sex = test_input($_POST["sex"]);
            $friendRefCode = test_input($_POST["friendRefCode"]);
            $refCode = substr(md5(uniqid(rand(), true)), 0, 8);
            $status = "Active";
            $date = date("Y-m-d");
            $time = date("Y-m-d H:i:s");
            $sql = "INSERT INTO profileUser (name, email, password, mobile, age, height, weight, sex, refCode, friendRefCode, status, date, time)
    VALUES ('$name', '$email', '$password', '$mobile', '$age', '$height', '$weight', '$sex', '$refCode', '$friendRefCode', '$status', '$date', '$time')";
            if ($conn->query($sql) === true) {
                $last_id = $conn->insert_id; // get the newly generated user ID
                $user_hb_table = "user_" . $last_id . "_hb";
                $user_hr_table = "user_" . $last_id . "_hr";
                $user_weight_table = "user_" . $last_id . "_weight";
                // Create the "user_[i]_hb" table
                $sql = "CREATE TABLE $user_hb_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            HB INT NOT NULL,
            MaxRate INT NOT NULL,
            Status text NOT NULL,
            Goal text NOT NULL,
            Date DATE NOT NULL,
            Time DATETIME NOT NULL,
            ActivityID INT NOT NULL
        )";
                $conn->query($sql);
                // Create the "user_[i]_hr" table
                $sql = "CREATE TABLE $user_hr_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ActivityID INT NOT NULL,
            HBStart INT NOT NULL,
            HBEnd INT NOT NULL,
            HBEnd1m INT NOT NULL,
            HBEnd2m INT NOT NULL,
            Drop1m INT NOT NULL,
            Drop2m INT NOT NULL,
            RecTime INT NOT NULL,
            RecRate INT NOT NULL,
            MaxRate INT NOT NULL,
            Status text NOT NULL,
            Goal text NOT NULL,
            Date DATE NOT NULL,
            StartTime DATETIME NOT NULL,
            EndTime DATETIME NOT NULL,
            Time DATETIME NOT NULL
            )";
                $conn->query($sql);
                // Create the "user_[i]_weight table
                $sql = "CREATE TABLE $user_weight_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            Weight INT NOT NULL,
            Status text NOT NULL,
            Goal text NOT NULL,
            Date date NOT NULL,
            Time datetime NOT NULL
        )";
                $conn->query($sql);
                echo '<!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Bootstrap demo</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        </head> 
        <body>
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">TM Heart</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="adminDashboard.php">Go Back to Dashboard</a>
                            </li>
                        </ul>
                        <form class="d-flex" role="search">
                            <a class="btn btn-danger" href="logout.php">Logout</a>
                        </form>
                    </div>
                </div>
            </nav>
            <h1>New user created successfully. User ID: ' . $last_id . '</h1>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
                crossorigin="anonymous"></script>
        </body>
        </html>';
            } else {
                echo '<!doctype html>
        <html lang="en">
        
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Bootstrap demo</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        </head>
        <body>
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">TM Heart</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="adminDashboard.php">Go Back to Dashboard</a>
                            </li>
                        </ul>
                        <form class="d-flex" role="search">
                            <a class="btn btn-danger" href="logout.php">Logout</a>
                        </form>
                    </div>
                </div>
            </nav>
            <h1>Unable to create new user. Error: ' . $sql . ' <br> ' . $conn->error . '</h1>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
                crossorigin="anonymous"></script>
        </body>
        </html>';
            }
            $conn->close();
        }
    } else {
        header("Location: index.html");
    }
} else {
    header("Location: index.html");
}
?>