<?php
require_once('conn.php');
session_start();
if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM adminUser WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['active'] = true;
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
    <nav class="navbar navbar-expand-lg shadow">
        <div class="container">
            <a class="navbar-brand" href="index.html">TM Heart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <div class="my-1 py-1">
                            <a class="btn btn-sharp mx-2" href="dashboard-dev.php">dashboard-dev</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="d-flex float-end">
                <a class="mx-2 me-3" href="adminProfile.php"><i class="bi bi-person-circle h2"></i></a>
                <a class="mx-2 me-3" href="logout.php"><i class="bi bi-box-arrow-right h2"></i></a>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <div class="row justify-content-center">
            <form action="searchUser.php" method="post">
                <div class="input-group d-flex justify-content-center">
                    <div class="col-lg-8 col-md-6 col-sm-4 p-0">
                        <div class="form-outline m-0">
                            <input type="search" name="search" class="form-control rounded-start-pill"
                                placeholder="Search users" />
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 p-0">
                        <button type="submit" name="submit" class="btn btn-dark rounded-end-circle">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container my-5">
        <div class="row">
            <?php
            // fetch all users data
            $query = "SELECT * FROM profile";
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
                $query2 = "SELECT * FROM user_" . $id . "_hr ORDER BY ID DESC LIMIT 1";
                $result2 = $conn->query($query2);
                $row2 = $result2->fetch_assoc();
                $goal = $row2["Goal"] ?? "-";
                $goalStatus = $row2["Status"] ?? "-";
                ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3 p-1">
                    <div class="card shadow">
                        <div class="card-title p-2 h6 m-1">
                            <div>
                                <?php echo $name ?? "-"; ?>
                            </div>
                            <div class="mt-1">
                                <?php echo $email ?? "-"; ?>
                            </div>
                            <div class="mt-1">
                                <?php echo $mobile ?? "-"; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row gap-1">
                                <div class="mx-auto text-center rounded col-lg-2 col-md-2 col-sm-2 ">
                                    <span>
                                        <div>
                                            <?php echo $age ?? "-"; ?>
                                        </div>

                                        <small class="text-secondary">
                                            Age
                                        </small>
                                    </span>
                                </div>
                                <div class="mx-auto text-center rounded col-lg-3 col-md-2 col-sm-2 ">
                                    <span>
                                        <div>
                                            <?php echo $height ?? "-"; ?>
                                        </div>

                                        <small class="text-secondary">
                                            Height
                                        </small>
                                    </span>
                                </div>
                                <div class="mx-auto text-center rounded col-lg-3 col-md-2 col-sm-2 ">
                                    <span>
                                        <div>
                                            <?php echo $weight ?? "-"; ?>
                                        </div>

                                        <small class="text-secondary">
                                            Weight
                                        </small>
                                    </span>
                                </div>
                                <div class="mx-auto text-center rounded col-lg-2 col-md-2 col-sm-2 ">
                                    <span>
                                        <div>
                                            <?php echo $sex ?? "-"; ?>
                                        </div>

                                        <small class="text-secondary">
                                            Sex
                                        </small>
                                    </span>
                                </div>
                            </div>
                            <div class="row mt-1 px-1 py-2 text-center">
                                <div class="rounded border bg-primary text-white">
                                    <?php echo $goalStatus ?? "-"; ?>
                                </div>
                            </div>
                            <div class="row px-1 py-2 text-center">
                                <div class="rounded border bg-danger text-white">
                                    <?php echo $goal ?? "-"; ?>
                                </div>
                            </div>
                            <div class="row small mt-1">
                                <span>Account created on:
                                    <?php echo $Date ?? "-"; ?>
                                </span>
                            </div>
                            <div class="mt-3">
                                <small class="float-start text-secondary mt-1">Status:
                                    <?php echo $status ?? "-"; ?>
                                </small>
                                <a class="me-2 btn btn-danger btn-sm float-end" href="delForm.php?del=<?php echo $id; ?>">
                                    <i class="bi bi-trash3"></i>
                                </a>
                                <a class="me-2 btn btn-warning btn-sm text-white float-end"
                                    href="userEdit.php?edit=<?php echo $id; ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a class="me-2 btn btn-success btn-sm float-end"
                                    href="userReport.php?report=<?php echo $id; ?>">
                                    <i class="bi bi-clipboard-data"></i>
                                </a>
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