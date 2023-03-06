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
<?php
function test_input($data)
{

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$search = test_input($_POST['search']) ?? "";
$search = mysqli_real_escape_string($conn, $search);
$query = "SELECT * FROM profile WHERE Name LIKE '%$search%' OR Email LIKE '%$search%' OR Mobile LIKE '%$search%' OR Age LIKE '%$search%' OR Height LIKE '%$search%' OR Weight LIKE '%$search%' OR Sex LIKE '%$search%' OR Status LIKE '%$search%' OR RefCode LIKE '%$search%' OR FriendRefCode LIKE '%$search%' OR Date LIKE '%$search%'";
$result = $conn->query($query);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Results</title>
    <link rel="icon" href="./favicon/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"
        integrity="sha256-ZwqZIVdD3iXNyGHbSYdsmWP//UBokj2FHAxKuSBKDSo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top shadow">
        <div class="container-fluid">
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
                                Back to dashboard
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="my-1 py-1 ms-1 me-1">
                            <!-- search feature -->
                            <form action="searchUser.php" method="post" class="d-flex" role="search">
                                <input id="search" class="form-control me-2" type="hidden" name="search"
                                    placeholder="Search User" aria-label="Search" />
                                <button id="searchButton" class="btn btn-outline-danger" type="submit">
                                    Show all users
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
    <div class="container-fluid mt-4">
        <table id="myTable" class="table table-striped-columns">
            <thead>
                <tr>
                    <th>Report</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Last Act. Goal</th>
                    <th>Last Act. Status</th>
                    <th>Age</th>
                    <th>Height</th>
                    <th>Weight</th>
                    <th>Sex</th>
                    <th>Active</th>
                    <th>Ref Code</th>
                    <th>Applied Ref Code</th>
                    <th>Date</th>
                    <th>Edit</th>
                    <th>Del</th>
                </tr>
            </thead>
            <?php
            while ($row = $result->fetch_assoc()) {
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
                $date = $row["Date"] ?? "-";
                $query2 = "SELECT * FROM user_" . $id . "_hr ORDER BY ID DESC LIMIT 1";
                $result2 = $conn->query($query2);
                $row2 = $result2->fetch_assoc();
                $goal = $row2["Goal"] ?? "-";
                $goalStatus = $row2["Status"] ?? "-";
                ?>
                <tr>
                    <td>
                        <a href="userReport.php?report=<?php echo $id; ?>"><?php echo $name; ?></a>
                    </td>
                    <td>
                        <?php echo $email; ?>
                    </td>
                    <td>
                        <?php echo $mobile; ?>
                    </td>
                    <td>
                        <?php echo $goalStatus; ?>
                    </td>
                    <td>
                        <?php echo $goal; ?>
                    </td>
                    <td>
                        <?php echo $age; ?>
                    </td>
                    <td>
                        <?php echo $height; ?>
                    </td>
                    <td>
                        <?php echo $weight; ?>
                    </td>
                    <td>
                        <?php echo $sex; ?>
                    </td>
                    <td>
                        <?php echo $status; ?>
                    </td>
                    <td>
                        <?php echo $refCode; ?>
                    </td>
                    <td>
                        <?php echo $friendRefCode; ?>
                    </td>
                    <td>
                        <?php echo $date; ?>
                    </td>
                    <td>
                        <a href="userEdit.php?edit=<?php echo $id; ?>">Edit</a>
                    </td>
                    <td>
                        <a href="delForm.php?del=<?php echo $id; ?>">Del</a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>