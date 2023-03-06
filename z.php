<?php
require_once('conn.php');
session_start();
if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM adminUser WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $id = $_GET['user_id'];
        // todo: also need to select activity id later
        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
        } else {
            $query = "SELECT MIN(date) as min_date, MAX(date) as max_date FROM user_" . $id . "_hr";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
            $start_date = $row['min_date'];
            $end_date = $row['max_date'];
        }
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
    <title>User Chart by Date</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- ! chart js -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.1/dist/chartjs-adapter-moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"
        integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.0/chartjs-plugin-zoom.min.js"
        integrity="sha512-B6F98QATBNaDHSE7uANGo5h0mU6fhKCUD+SPAY7KZDxE8QgZw9rewDtNiu3mbbutYDWOKT3SPYD8qDBpG2QnEg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .hoverYellow:hover {
            background-color: royalblue !important;
            color: white !important;
            font-weight: 600 !important;
        }

        .container-fluid {
            padding-right: 0 !important;
            padding-left: 0 !important;
            margin-right: auto !important;
            margin-left: auto !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow">
        <div class="container">
            <a class="navbar-brand" href="#">TM Heart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
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
    <div class="container mt-5">
        <form action="z.php" method="get">
            <input type="hidden" name="user_id" value="<?php echo $id ?>" required>
            <div class="row mt-3">
                <div class="col-lg-5 col-md-4 col-sm-12">
                    <label class="form-label" for="start_date">Start Date:</label>
                    <input class="form-control" type="date" id="start_date" name="start_date" required>
                </div>
                <div class="col-lg-5 col-md-4 col-sm-12">
                    <label class="form-label" for="end_date">End Date:</label>
                    <input class="form-control" type="date" id="end_date" name="end_date" required>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12 mt-2">
                    <br>
                    <button class="btn btn-success" type="submit">Get Analysis</button>
                </div>
        </form>
    </div>
    <div class="container-fluid mt-4">
        <?php
        $sql = "SELECT DISTINCT Date FROM user_" . $id . "_hr WHERE Date BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
        $result = $conn->query($sql);
        $data = array();
        while ($row = mysqli_fetch_array($result)) {
            $data[] = $row;
        }
        ?>
        <?php foreach ($data as $row): ?>
            <div class="overflow-y-scroll mt-4" style="max-height: 65vh;">
                <?php $date = $row['Date']; ?>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-title bg-primary h6 p-2 text-white rounded">
                                <?php echo $date; ?>
                            </div>
                            <div class="card-body">
                                <?php
                                $table_name = "user_{$id}_hb";
                                $sql = "SELECT hb, time FROM $table_name WHERE date = '$date'";
                                $result = $conn->query($sql);
                                $data = $result->fetch_all(MYSQLI_ASSOC);
                                $hb = array_column($data, 'hb');
                                $time = array_map(function ($value) {
                                    $timestamp = strtotime($value);
                                    return date('Y-m-d H:i:s', $timestamp);
                                }, array_column($data, 'time'));
                                ?>
                                <canvas id="myChart_<?php echo str_replace('-', '_', $date); ?>"></canvas>
                                <div class="mt-2">
                                    <?php
                                    $download_function_name = 'download_' . str_replace('-', '_', $date);
                                    ?>
                                    <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                        onclick="<?php echo $download_function_name; ?>()">
                                        <i class="bi bi-download"></i>
                                    </button>
                                    <?php
                                    $resetZoom_function_name = 'resetZoom_' . str_replace('-', '_', $date);
                                    ?>
                                    <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                        onclick="<?php echo $resetZoom_function_name; ?>()">
                                        <i class="bi bi-zoom-out"></i>
                                    </button>
                                    <a href="chartBar.php?date=<?php echo $date; ?>&table=<?php echo $table_name; ?>"
                                        target="_blank" class="btn btn-sm btn-secondary text-white float-end me-2">
                                        <i class="bi bi-bar-chart-line-fill"></i>
                                    </a>
                                    <a href="chartLine.php?date=<?php echo $date; ?>&table=<?php echo $table_name; ?>"
                                        target="_blank" class="btn btn-sm btn-secondary text-white float-end me-2">
                                        <i class="bi bi-graph-up"></i>
                                    </a>
                                </div>
                            </div>
                            <script>
                                let myChart_<?php echo str_replace('-', '_', $date); ?> = new Chart(document.getElementById('myChart_<?php echo str_replace('-', '_', $date); ?>'), {
                                    type: 'line',
                                    options: {
                                        animation: false,
                                        spanGaps: true,
                                        elements: {
                                            point: {
                                                radius: 0
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            },
                                            x: {
                                                type: "time",
                                                time: {
                                                    tooltipFormat: 'DD MMMM YYYY HH:mm',
                                                    displayFormats: {
                                                        millisecond: "H:mm:ss.SSS",
                                                        second: "H:mm:ss",
                                                        minute: "H:mm",
                                                        hour: "H:mm",
                                                        day: "D",
                                                        month: "MM",
                                                        year: "YY",
                                                        // millisecond: "H:mm:ss.SSS",
                                                        // second: "H:mm:ss",
                                                        // minute: "D MM H:mm",
                                                        // hour: "D MM YY H:mm",
                                                        // day: "D MM YY",
                                                        // month: "D MM YY",
                                                        // year: "D MM YY",
                                                    }
                                                }
                                            }
                                        },
                                        plugins: {
                                            zoom: {
                                                pan: {
                                                    enabled: true,
                                                    mode: "x",
                                                    modifierKey: "ctrl",
                                                },
                                                zoom: {
                                                    wheel: {
                                                        enabled: true,
                                                    },
                                                    drag: {
                                                        enabled: true,
                                                    },
                                                    pinch: {
                                                        enabled: true,
                                                    },
                                                    mode: "x",
                                                },
                                            }
                                        }
                                    },
                                    data: {
                                        labels: <?php echo json_encode($time); ?>,
                                        datasets: [{
                                            label: 'Heart Rate',
                                            data: <?php echo json_encode($hb); ?>,
                                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                            borderColor: 'rgba(255, 99, 132, 1)',
                                            borderWidth: 0.5
                                        }]
                                    }
                                });
                                function <?php echo $download_function_name; ?>() {
                                    const imageLink = document.createElement('a');
                                    const canvas = document.getElementById('myChart');
                                    imageLink.download = 'chart_hb_vs_time.png';
                                    imageLink.href = canvas.toDataURL('image/png', 1);
                                    imageLink.click();
                                }
                                function <?php echo $resetZoom_function_name; ?>() {
                                    myChart_<?php echo str_replace('-', '_', $date); ?>.resetZoom();
                                }
                            </script>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <?php
                        $actTable = "user_{$id}_hr";
                        $query = "SELECT * FROM user_" . $id . "_hr WHERE date='" . $date . "' ORDER BY id";
                        $result = mysqli_query($conn, $query);
                        $data = array();
                        while ($row = mysqli_fetch_array($result)) {
                            $data[] = $row;
                        }
                        ?>
                        <?php foreach ($data as $row): ?>
                            <div class="card mb-2">
                                <div class="card-title text-center bg-primary text-white rounded py-1 px-3">
                                    <span class="h6 float-end">Date:
                                        <?= $row['Date'] ?? "-" ?>
                                    </span>
                                    <span class="h6 float-start">Activity:
                                        <?= $row['ActivityID'] ?? "-" ?>
                                    </span>
                                    <span class="h6 float-none">ID:
                                        <?= $row['ID'] ?? "-" ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center gap-2 mb-2">
                                        <div
                                            class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                            <span>
                                                <div>
                                                    <?= $row['HBStart'] ?? "-" ?>
                                                </div>
                                                <small> HBStart </small>
                                            </span>
                                        </div>
                                        <div
                                            class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                            <span>
                                                <div>
                                                    <?= $row['HBEnd'] ?? "-" ?>
                                                </div>
                                                <small> HBEnd </small>
                                            </span>
                                        </div>
                                        <div
                                            class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                            <span>
                                                <div>
                                                    <?= $row['HBEnd1m'] ?? "-" ?>
                                                </div>
                                                <small> HBEnd1m </small>
                                            </span>
                                        </div>
                                        <div
                                            class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                            <span>
                                                <div>
                                                    <?= $row['HBEnd2m'] ?? "-" ?>
                                                </div>
                                                <small> HBEnd2m </small>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row text-center gap-2 mb-2">
                                        <div
                                            class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                            <span>
                                                <div>
                                                    <?= $row['Drop1m'] ?? "-" ?>
                                                </div>
                                                <small> Drop1m </small>
                                            </span>
                                        </div>
                                        <div
                                            class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                            <span>
                                                <div>
                                                    <?= $row['Drop2m'] ?? "-" ?>
                                                </div>
                                                <small> Drop2m </small>
                                            </span>
                                        </div>
                                        <div
                                            class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                            <span>
                                                <div>
                                                    <?= $row['RecTime'] ?? "-" ?>
                                                </div>
                                                <small> RecTime </small>
                                            </span>
                                        </div>
                                        <div
                                            class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                            <span>
                                                <div>
                                                    <?= $row['RecRate'] ?? "-" ?>
                                                </div>
                                                <small> RecRate </small>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-3 col-md-3 col-sm-3 float-start">MaxRate</div>
                                        <div class="col-lg-9 col-md-9 col-sm-9 float-start h6">
                                            <?= $row['MaxRate'] ?? "-" ?>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-3 col-md-3 col-sm-3 float-start">Goal</div>
                                        <div class="col-lg-9 col-md-9 col-sm-9 float-start h6">
                                            <?= $row['Goal'] ?? "-" ?>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-lg-3 col-md-3 col-sm-3 float-start">Status</div>
                                        <div class="col-lg-9 col-md-9 col-sm-9 float-start h6">
                                            <?= $row['Status'] ?? "-" ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3 mx-3">
                                    <a href="activityDel.php?actID=<?php echo $row['ID']; ?>&actTable=<?php echo $actTable; ?>"
                                        class="btn btn-sm btn-danger text-white float-end me-2">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <a href="activityEdit.php?actID=<?php echo $row['ID']; ?>&actTable=<?php echo $actTable; ?>"
                                        class="btn btn-sm btn-warning text-white float-end me-2">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>