<?php
require_once('conn.php');
session_start();
if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM adminUser WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $id = $_GET['report'];
        // todo: also need to select activity id later
        $query = "SELECT MAX(date) FROM user_" . $id . "_hr";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        $date = $row[0];
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
    <title>User Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
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
    <div class="conainer-fluid mt-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-title h6 bg-primary text-white rounded py-2 px-3">
                        Date:
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
                        <canvas id="myChart"></canvas>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download()">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                onclick="resetZoomMyChart()">
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
                        <script>
                            // const moment = require('moment');
                            // const Chart = require('chart.js/auto');
                            // require('chartjs-adapter-moment')(moment);
                            const myChart = new Chart(document.getElementById('myChart'), {
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
                            function resetZoomMyChart() {
                                myChart.resetZoom();
                            };
                            function download() {
                                const imageLink = document.createElement('a');
                                const canvas = document.getElementById('myChart');
                                imageLink.download = 'chart_hb_vs_time.png';
                                imageLink.href = canvas.toDataURL('image/png', 1);
                                imageLink.click();
                            };
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?php
                $actTable = "user_{$id}_hr";
                $query = "SELECT * FROM user_" . $id . "_hr WHERE date='" . $date . "' ORDER BY id DESC LIMIT 1";
                $result = mysqli_query($conn, $query);
                $data = array();
                while ($row = mysqli_fetch_array($result)) {
                    $data[] = $row;
                }
                ?>
                <?php foreach ($data as $row): ?>
                    <div class="card">
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
                                <div class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                    <span>
                                        <div>
                                            <?= $row['HBStart'] ?? "-" ?>
                                        </div>
                                        <small> HBStart </small>
                                    </span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                    <span>
                                        <div>
                                            <?= $row['HBEnd'] ?? "-" ?>
                                        </div>
                                        <small> HBEnd </small>
                                    </span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                    <span>
                                        <div>
                                            <?= $row['HBEnd1m'] ?? "-" ?>
                                        </div>
                                        <small> HBEnd1m </small>
                                    </span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                    <span>
                                        <div>
                                            <?= $row['HBEnd2m'] ?? "-" ?>
                                        </div>
                                        <small> HBEnd2m </small>
                                    </span>
                                </div>
                            </div>
                            <div class="row text-center gap-2 mb-2">
                                <div class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                    <span>
                                        <div>
                                            <?= $row['Drop1m'] ?? "-" ?>
                                        </div>
                                        <small> Drop1m </small>
                                    </span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                    <span>
                                        <div>
                                            <?= $row['Drop2m'] ?? "-" ?>
                                        </div>
                                        <small> Drop2m </small>
                                    </span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
                                    <span>
                                        <div>
                                            <?= $row['RecTime'] ?? "-" ?>
                                        </div>
                                        <small> RecTime </small>
                                    </span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 border border-secondary hoverYellow rounded mx-auto">
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
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-title text-center py-2 bg-primary text-white rounded h6">Highs vs Time</div>
                    <div class="card-body">
                        <?php
                        $table_name = "user_{$id}_hb";
                        $sql = "SELECT MAX(HB) AS highest_hb, DATE(Date) AS hb_date FROM $table_name GROUP BY DATE(Date)";
                        $result = $conn->query($sql);
                        $data = $result->fetch_all(MYSQLI_ASSOC);
                        $highest_hb = array_column($data, 'highest_hb');
                        $hb_date1 = array_map(function ($value) {
                            $timestamp = strtotime($value);
                            return date('Y-m-d H:i:s', $timestamp);
                        }, array_column($data, 'hb_date'));
                        ?>
                        <canvas id="highvstime"></canvas>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-dark text-white float-end me-2" onclick="change()">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download()">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                onclick="resetZoomChart()">
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
                        <script>
                            const highvstime = new Chart(document.getElementById('highvstime'), {
                                type: 'bar',
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
                                                    minute: "D-MM H:mm",
                                                    hour: "D-MM H:mm",
                                                    day: "D-MM-YY",
                                                    month: "MM-YY",
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
                                    labels: <?php echo json_encode($hb_date1); ?>,
                                    datasets: [{
                                        label: 'Heart Rate',
                                        data: <?php echo json_encode($highest_hb); ?>,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 0.5
                                    }]
                                }
                            });
                            function resetZoomChart() {
                                highvstime.resetZoom();
                            };
                            function download() {
                                const imageLink = document.createElement('a');
                                const canvas = document.getElementById('highvstime');
                                imageLink.download = 'chart_highs_vs_time.png';
                                imageLink.href = canvas.toDataURL('image/png', 1);
                                imageLink.click();
                            };
                            function change() {
                                highvstime.config.type = highvstime.config.type === "line" ? "bar" : "line";
                                highvstime.update("none");
                            }
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-title text-center py-2 bg-primary text-white rounded h6">Lows vs Time</div>
                    <div class="card-body">
                        <?php
                        $table_name = "user_{$id}_hb";
                        $sql = "SELECT MIN(HB) AS lowest_hb, DATE(Date) AS hb_date FROM $table_name GROUP BY DATE(Date)";
                        $result = $conn->query($sql);
                        $data = $result->fetch_all(MYSQLI_ASSOC);
                        $lowest_hb = array_column($data, 'lowest_hb');
                        $hb_date2 = array_map(function ($value) {
                            $timestamp = strtotime($value);
                            return date('Y-m-d H:i:s', $timestamp);
                        }, array_column($data, 'hb_date'));
                        ?>
                        <canvas id="lowsvstime"></canvas>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-dark text-white float-end me-2" onclick="change1()">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download1()">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                onclick="resetZoomChart1()">
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
                        <script>
                            const lowsvstime = new Chart(document.getElementById('lowsvstime'), {
                                type: 'bar',
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
                                                    minute: "D-MM H:mm",
                                                    hour: "D-MM H:mm",
                                                    day: "D-MM-YY",
                                                    month: "MM-YY",
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
                                    labels: <?php echo json_encode($hb_date2); ?>,
                                    datasets: [{
                                        label: 'Heart Rate',
                                        data: <?php echo json_encode($lowest_hb); ?>,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 0.5
                                    }]
                                }
                            });
                            function resetZoomChart1() {
                                lowsvstime.resetZoom();
                            };
                            function download1() {
                                const imageLink = document.createElement('a');
                                const canvas = document.getElementById('lowsvstime');
                                imageLink.download = 'chart_lows_vs_time.png';
                                imageLink.href = canvas.toDataURL('image/png', 1);
                                imageLink.click();
                            };
                            function change1() {
                                lowsvstime.config.type = lowsvstime.config.type === "line" ? "bar" : "line";
                                lowsvstime.update("none");
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-title text-center py-2 bg-primary text-white rounded h6">Crossed Highs vs Time
                    </div>
                    <div class="card-body">
                        <?php
                        $table_name = "user_{$id}_hb";
                        $sql = "SELECT COUNT(*) AS high_count, DATE(time) AS hb_date FROM $table_name WHERE status = 'HIGH' GROUP BY DATE(time)";
                        $result = $conn->query($sql);
                        $data = $result->fetch_all(MYSQLI_ASSOC);
                        $high_count = array_column($data, 'high_count');
                        $hb_date3 = array_map(function ($value) {
                            $timestamp = strtotime($value);
                            return date('Y-m-d H:i:s', $timestamp);
                        }, array_column($data, 'hb_date'));
                        ?>
                        <canvas id="crossedHighsvstime"></canvas>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-dark text-white float-end me-2" onclick="change2()">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download3()">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                onclick="resetZoomChart2()">
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
                        <script>
                            const crossedHighsvstime = new Chart(document.getElementById('crossedHighsvstime'), {
                                type: 'bar',
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
                                                    minute: "D-MM H:mm",
                                                    hour: "D-MM H:mm",
                                                    day: "D-MM-YY",
                                                    month: "MM-YY",
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
                                    labels: <?php echo json_encode($hb_date3); ?>,
                                    datasets: [{
                                        label: 'Heart Rate',
                                        data: <?php echo json_encode($high_count); ?>,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 0.5
                                    }]
                                }
                            });
                            function resetZoomChart2() {
                                crossedHighsvstime.resetZoom();
                            };
                            function download3() {
                                const imageLink = document.createElement('a');
                                const canvas = document.getElementById('crossedHighsvstime');
                                imageLink.download = 'chart_crossed_highs_vs_time.png';
                                imageLink.href = canvas.toDataURL('image/png', 1);
                                imageLink.click();
                            };
                            function change2() {
                                crossedHighsvstime.config.type = crossedHighsvstime.config.type === "line" ? "bar" : "line";
                                crossedHighsvstime.update("none");
                            }
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-title text-center py-2 bg-primary text-white rounded h6">Crossed Lows vs Time
                    </div>
                    <div class="card-body">
                        <?php
                        $table_name = "user_{$id}_hb";
                        $sql = "SELECT COUNT(*) AS low_count, DATE(time) AS hb_date FROM $table_name WHERE status = 'LOW' GROUP BY DATE(time)";
                        $result = $conn->query($sql);
                        $data = $result->fetch_all(MYSQLI_ASSOC);
                        $low_count = array_column($data, 'low_count');
                        $hb_date4 = array_map(function ($value) {
                            $timestamp = strtotime($value);
                            return date('Y-m-d H:i:s', $timestamp);
                        }, array_column($data, 'hb_date'));
                        ?>
                        <canvas id="crossedLowsvstime"></canvas>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-dark text-white float-end me-2" onclick="change3()">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download4()">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                onclick="resetZoomChart3()">
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
                        <script>
                            const crossedLowsvstime = new Chart(document.getElementById('crossedLowsvstime'), {
                                type: 'bar',
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
                                                    minute: "D-MM H:mm",
                                                    hour: "D-MM H:mm",
                                                    day: "D-MM-YY",
                                                    month: "MM-YY",
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
                                    labels: <?php echo json_encode($hb_date4); ?>,
                                    datasets: [{
                                        label: 'Heart Rate',
                                        data: <?php echo json_encode($low_count); ?>,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 0.5
                                    }]
                                }
                            });
                            function resetZoomChart3() {
                                crossedLowsvstime.resetZoom();
                            };
                            function download4() {
                                const imageLink = document.createElement('a');
                                const canvas = document.getElementById('crossedLowsvstime');
                                imageLink.download = 'chart_crossed_lows_vs_time.png';
                                imageLink.href = canvas.toDataURL('image/png', 1);
                                imageLink.click();
                            };
                            function change3() {
                                crossedLowsvstime.config.type = crossedLowsvstime.config.type === "line" ? "bar" : "line";
                                crossedLowsvstime.update("none");
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-title text-center py-2 bg-primary text-white rounded h6">Drop 1m vs Time
                    </div>
                    <div class="card-body">
                        <?php
                        $table_name = "user_{$id}_hr";
                        $sql = "SELECT Drop1m, DATE(time) AS hr_time FROM $table_name";
                        $result = $conn->query($sql);
                        $data = $result->fetch_all(MYSQLI_ASSOC);
                        $Drop1m = array_column($data, 'Drop1m');
                        $hr_time = array_map(function ($value) {
                            $timestamp = strtotime($value);
                            return date('Y-m-d H:i:s', $timestamp);
                        }, array_column($data, 'hr_time'));
                        ?>
                        <canvas id="drop1mvstime"></canvas>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-dark text-white float-end me-2" onclick="change4()">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download5()">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                onclick="resetZoomChart4()">
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
                        <script>
                            const drop1mvstime = new Chart(document.getElementById('drop1mvstime'), {
                                type: 'bar',
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
                                                    minute: "D-MM H:mm",
                                                    hour: "D-MM H:mm",
                                                    day: "D-MM-YY",
                                                    month: "MM-YY",
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
                                    labels: <?php echo json_encode($hr_time); ?>,
                                    datasets: [{
                                        label: 'Heart Rate',
                                        data: <?php echo json_encode($Drop1m); ?>,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 0.5
                                    }]
                                }
                            });
                            function resetZoomChart4() {
                                drop1mvstime.resetZoom();
                            };
                            function download5() {
                                const imageLink = document.createElement('a');
                                const canvas = document.getElementById('drop1mvstime');
                                imageLink.download = 'chart_drop_1m_vs_time.png';
                                imageLink.href = canvas.toDataURL('image/png', 1);
                                imageLink.click();
                            };
                            function change4() {
                                drop1mvstime.config.type = drop1mvstime.config.type === "line" ? "bar" : "line";
                                drop1mvstime.update("none");
                            }
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-title text-center py-2 bg-primary text-white rounded h6">Drop 2m vs Time
                    </div>
                    <div class="card-body">
                        <?php
                        $table_name = "user_{$id}_hr";
                        $sql = "SELECT Drop2m, DATE(time) AS hr_time FROM $table_name";
                        $result = $conn->query($sql);
                        $data = $result->fetch_all(MYSQLI_ASSOC);
                        $Drop2m = array_column($data, 'Drop2m');
                        $hr_time1 = array_map(function ($value) {
                            $timestamp = strtotime($value);
                            return date('Y-m-d H:i:s', $timestamp);
                        }, array_column($data, 'hr_time'));
                        ?>
                        <canvas id="drop2mvstime"></canvas>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-dark text-white float-end me-2" onclick="change5()">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download6()">
                                <i class="bi bi-download"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary text-white float-end me-2"
                                onclick="resetZoomChart5()">
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
                        <script>
                            const drop2mvstime = new Chart(document.getElementById('drop2mvstime'), {
                                type: 'bar',
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
                                                    minute: "D-MM H:mm",
                                                    hour: "D-MM H:mm",
                                                    day: "D-MM-YY",
                                                    month: "MM-YY",
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
                                    labels: <?php echo json_encode($hr_time1); ?>,
                                    datasets: [{
                                        label: 'Heart Rate',
                                        data: <?php echo json_encode($Drop2m); ?>,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 0.5
                                    }]
                                }
                            });
                            function resetZoomChart5() {
                                drop2mvstime.resetZoom();
                            };
                            function download6() {
                                const imageLink = document.createElement('a');
                                const canvas = document.getElementById('drop2mvstime');
                                imageLink.download = 'chart_drop_2m_vs_time.png';
                                imageLink.href = canvas.toDataURL('image/png', 1);
                                imageLink.click();
                            };
                            function change5() {
                                drop2mvstime.config.type = drop2mvstime.config.type === "line" ? "bar" : "line";
                                drop2mvstime.update("none");
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-title text-center py-2 bg-primary text-white rounded h6">RecTime vs Time
                </div>
                <div class="card-body">
                    <?php
                    $table_name = "user_{$id}_hr";
                    $sql = "SELECT RecTime, DATE(time) AS hr_time FROM $table_name";
                    $result = $conn->query($sql);
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    $RecTime = array_column($data, 'RecTime');
                    $hr_time2 = array_map(function ($value) {
                        $timestamp = strtotime($value);
                        return date('Y-m-d H:i:s', $timestamp);
                    }, array_column($data, 'hr_time'));
                    ?>
                    <canvas id="rectimevstime"></canvas>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-dark text-white float-end me-2" onclick="change6()">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download7()">
                            <i class="bi bi-download"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="resetZoomChart6()">
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
                    <script>
                        const rectimevstime = new Chart(document.getElementById('rectimevstime'), {
                            type: 'bar',
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
                                                minute: "D-MM H:mm",
                                                hour: "D-MM H:mm",
                                                day: "D-MM-YY",
                                                month: "MM-YY",
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
                                labels: <?php echo json_encode($hr_time2); ?>,
                                datasets: [{
                                    label: 'Heart Rate',
                                    data: <?php echo json_encode($RecTime); ?>,
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 0.5
                                }]
                            }
                        });
                        function resetZoomChart6() {
                            rectimevstime.resetZoom();
                        };
                        function download7() {
                            const imageLink = document.createElement('a');
                            const canvas = document.getElementById('rectimevstime');
                            imageLink.download = 'chart_RecTime_vs_time.png';
                            imageLink.href = canvas.toDataURL('image/png', 1);
                            imageLink.click();
                        };
                        function change6() {
                            rectimevstime.config.type = rectimevstime.config.type === "line" ? "bar" : "line";
                            rectimevstime.update("none");
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-title text-center py-2 bg-primary text-white rounded h6">RecTime vs Time
                </div>
                <div class="card-body">
                    <?php
                    $table_name = "user_{$id}_weight";
                    $sql = "SELECT Weight, DATE(time) AS weight_time FROM $table_name";
                    $result = $conn->query($sql);
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    $Weight = array_column($data, 'Weight');
                    $weight_time = array_map(function ($value) {
                        $timestamp = strtotime($value);
                        return date('Y-m-d H:i:s', $timestamp);
                    }, array_column($data, 'weight_time'));
                    ?>
                    <canvas id="weightvstime"></canvas>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-dark text-white float-end me-2" onclick="change7()">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="download8()">
                            <i class="bi bi-download"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary text-white float-end me-2" onclick="resetZoomChart7()">
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
                    <script>
                        const weightvstime = new Chart(document.getElementById('weightvstime'), {
                            type: 'bar',
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
                                                minute: "D-MM H:mm",
                                                hour: "D-MM H:mm",
                                                day: "D-MM-YY",
                                                month: "MM-YY",
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
                                labels: <?php echo json_encode($weight_time); ?>,
                                datasets: [{
                                    label: 'Weight',
                                    data: <?php echo json_encode($Weight); ?>,
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 0.5
                                }]
                            }
                        });
                        function resetZoomChart7() {
                            weightvstime.resetZoom();
                        };
                        function download8() {
                            const imageLink = document.createElement('a');
                            const canvas = document.getElementById('weightvstime');
                            imageLink.download = 'chart_weight_vs_time.png';
                            imageLink.href = canvas.toDataURL('image/png', 1);
                            imageLink.click();
                        };
                        function change7() {
                            weightvstime.config.type = weightvstime.config.type === "line" ? "bar" : "line";
                            weightvstime.update("none");
                        }
                    </script>
                </div>
            </div>
        </div>
</body>

</html>