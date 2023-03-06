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
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
                                Back to Dashboard
                            </a>
                        </div>
                    </li>
                </ul>
                <div class="d-flex float-end">
                    <a class="btn btn-danger btn-sharp mx-2 text-white" href="logout.php">Log out</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-5 my-5 px-5 py-5">
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12 mx-auto border shadow mx-5 my-5 px-5 py-5">
                <h1>Edit user data</h1>
                <h6 class="text-danger">Only change values that you want to update!</h6>
                <form class="form" action="userEditReq.php" method="post">
                    <?php
                    $id = $_GET['edit'];
                    $sql = "SELECT * FROM profile WHERE ID=$id";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    mysqli_close($conn);
                    ?>
                    <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
                    <div>
                        <label class="form-label mt-3" for="name">Name:</label>
                        <input class="form-control" type="text" id="name" name="name"
                            value="<?php echo $row['Name']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="email">Email:</label>
                        <input class="form-control" type="email" id="email" name="email"
                            value="<?php echo $row['Email']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="password">Password:</label>
                        <input class="form-control" type="password" id="password" name="password"
                            value="<?php echo $row['Password']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="mobile">Mobile:</label>
                        <input class="form-control" type="text" id="mobile" name="mobile"
                            value="<?php echo $row['Mobile']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="age">Age:</label>
                        <input class="form-control" type="text" id="age" name="age" value="<?php echo $row['Age']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="height">Height:</label>
                        <input class="form-control" type="text" id="height" name="height"
                            value="<?php echo $row['Height']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="weight">Weight:</label>
                        <input class="form-control" type="text" id="weight" name="weight"
                            value="<?php echo $row['Weight']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="sex">Sex:</label>
                        <input class="form-control" type="text" id="sex" name="sex" value="<?php echo $row['Sex']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="ref_code">Ref Code:</label>
                        <input class="form-control" type="text" id="ref_code" name="ref_code"
                            value="<?php echo $row['RefCode']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="friend_ref_code">Friend Ref Code:</label>
                        <input class="form-control" type="text" id="friend_ref_code" name="friend_ref_code"
                            value="<?php echo $row['FriendRefCode']; ?>">
                    </div>
                    <div>
                        <label class="form-label mt-3" for="status">Status:</label>
                        <input class="form-control" type="text" id="status" name="status"
                            value="<?php echo $row['Status']; ?>">
                    </div>
                    <div>
                        <input class="btn btn-danger float-end mt-5" name="update" type="submit" value="Update Profile">
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>