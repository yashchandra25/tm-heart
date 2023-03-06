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
    <title>Dashboard</title>
    <link rel="icon" href="./favicon/favicon.ico" />
    <link rel="stylesheet" href="./css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top shadow">
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
                                Back to main dashboard
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="my-1 py-1 ms-1 me-1">
                            <!-- search feature -->
                            <form action="searchUser.php" method="post" class="d-flex" role="search">
                                <input id="search" class="form-control me-2" type="search" name="search"
                                    placeholder="Search User" aria-label="Search" />
                                <button id="searchButton" class="btn btn-outline-danger" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
                <div class="d-flex float-end">
                    <a class="btn btn-info btn-sharp mx-2 text-white" href="addUser.php">Add user</a>
                    <a class="btn btn-danger btn-sharp mx-2 text-white" href="logout.php">Log out</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <div class="row" id="data">
            <?php
            // fetch all users data
            $query = "SELECT * FROM profileuser";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                // while runs this block of code for each user's id
                $id = $row["ID"] ?? "-";
                $name = $row["Name"] ?? "-";
                $email = $row["Email"] ?? "-";
                $mobile = $row["Mobile"] ?? "-";
                $age = $row["Age"] ?? "-";
                $height = $row["Height"] ?? "-";
                $weight = $row["Weight"] ?? "-";
                $sex = $row["Sex"] ?? "-";
                $status = $row["Status"] ?? "-";
                $refCode = $row["RefCode"] ?? "-";
                $friendRefCode = $row["FriendRefCode"] ?? "-";
                $Date = $row["Date"] ?? "-";
                // fetch Goal and Status for each user's id from profile
                $query2 = "SELECT * FROM user_{$id}_hr ORDER BY ID DESC LIMIT 1";
                $result2 = $conn->query($query2);
                $row2 = $result2->fetch_assoc();
                $goal = $row2["Goal"] ?? "-";
                $goalStatus = $row2["Status"] ?? "-";
                ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3 p-1">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="card-title">
                                <h5>
                                    <?php echo $name; ?>
                                </h5>
                                <h6>
                                    <?php echo $email; ?>
                                </h6>
                                <h6>
                                    <?php echo $mobile; ?>
                                </h6>
                            </div>
                            <div class="card-text mt-3">
                                <div class="row text-center">
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <span>
                                            <div>
                                                <?php echo $age; ?>
                                            </div>

                                            <small>
                                                Age
                                            </small>
                                        </span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <span>
                                            <div>
                                                <?php echo $height; ?>
                                            </div>
                                            <small>
                                                Height
                                            </small>
                                        </span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <span>
                                            <div>
                                                <?php echo $weight; ?>
                                            </div>
                                            <small>
                                                Weight
                                            </small>
                                        </span>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <span>
                                            <div>
                                                <?php echo $sex; ?>
                                            </div>
                                            <small>
                                                Sex
                                            </small>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-1 mt-3 bg-danger text-white border text-center my-2 py-1">
                                    <?php echo $goal; ?>
                                </div>
                                <div class="p-1 bg-primary text-white border text-center my-2 py-1">
                                    <?php echo $goalStatus; ?>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <small class="">Account created on:
                                    <?php echo $Date; ?>
                                </small>
                            </div>
                            <div class="row mt-3 text-center">
                                <div class="col-lg-4 col-md-4 my-1 col-sm-4">
                                    <a href="userDel.php?del=<?php echo $id; ?>"
                                        class="text-white btn btn-dark btn-sm">Delete</a>
                                </div>
                                <div class="col-lg-4 col-md-4 my-1 col-sm-4">
                                    <a href="userEdit.php?edit=<?php echo $id; ?>"
                                        class="text-white btn btn-warning btn-sm">Edit</a>
                                </div>
                                <div class="col-lg-4 col-md-4 my-1 col-sm-4">
                                    <a href="userReport.php?report=<?php echo $id; ?>"
                                        class="btn text-white btn-success btn-sm">Report</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } // end of while loop
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>