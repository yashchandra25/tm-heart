<?php
require_once('conn.php');
session_start();
if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM adminUser WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // $_SESSION['active'] = true;
    } else {
        header("Location: index.html");
    }
} else {
    header("Location: index.html");
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User</title>
    <link rel="icon" href="./favicon/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-light shadow">
        <div class="container">
            <a class="navbar-brand" href="index.html">TM Heart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <div class="my-1 py-1">
                            <a href="adminDashboard.php" class="nav-link">
                                Go Back to Dashboard
                            </a>
                        </div>
                    </li>
                </ul>
                <div class="d-flex float-end">
                    <a href="addAdmin.php" class="btn btn-primary btn-sharp mx-2 text-white">Add New Admin</a>
                    <a class="btn btn-danger btn-sharp mx-2 text-white" href="logout.php">Log out</a>
                </div>
            </div>
        </div>
    </nav>
    <section id="main-section" class="min-vh-100 d-flex align-items-center">
        <div class="container my-5">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-6 border rounded mx-auto py-5 px-5 shadow">
                    <h1 class="fw-semibold">Add new user</h1>
                    <form action="addUserReq.php" method="post">
                        <label class="form-label" for="name">Name:</label>
                        <input class="form-control" type="text" id="name" name="name" required><br><br>

                        <label class="form-label" for="email">Email:</label>
                        <input class="form-control" type="email" id="email" name="email" required><br><br>

                        <label class="form-label" for="password">Password:</label>
                        <input class="form-control" type="password" id="password" name="password" required><br><br>

                        <label class="form-label" for="mobile">Mobile:</label>
                        <input class="form-control" type="text" id="mobile" name="mobile" required><br><br>

                        <label class="form-label" for="age">Age:</label>
                        <input class="form-control" type="text" id="age" name="age" required><br><br>

                        <label class="form-label" for="height">Height:</label>
                        <input class="form-control" type="text" id="height" name="height" required><br><br>

                        <label class="form-label" for="weight">Weight:</label>
                        <input class="form-control" type="text" id="weight" name="weight" required><br><br>

                        <label class="form-label" for="sex">Sex:</label>
                        <select class="form-control" id="sex" name="sex" required>
                            <option value="M">M</option>
                            <option value="F">F</option>
                            <option value="O">O</option>
                        </select><br><br>

                        <label class="form-label" for="friendRefCode">Friend's Referral Code:</label>
                        <input class="form-control" type="text" id="friendRefCode" name="friendRefCode"><br><br>
                        <a href="adminDashboard.php" class="btn btn-dark">Cancel</a>
                        <input type="submit" class="float-end btn btn-primary" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>