<?php
require_once('conn.php');
session_start();
if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT * FROM adminUser WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $date = $_GET['date'];
        $table = $_GET['table'];
    } else {
        header("Location: index.html");
    }
} else {
    header("Location: index.html");
}
?>
<?php
$sql = "SELECT hb, time FROM $table WHERE date = '$date'";
$result = $conn->query($sql);
$data = $result->fetch_all(MYSQLI_ASSOC);
$hb = array_column($data, 'hb');
$time = array_map(function ($value) {
    $timestamp = strtotime($value);
    return date('Y-m-d H:i:s', $timestamp);
}, array_column($data, 'time'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Chart</title>
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
</head>

<body>
    <div class="mt-4 text-center">
        <a href="chartBar.php?date=<?php echo $date; ?>&table=<?php echo $table; ?>" target="_blank"
            class="btn btn-sm btn-secondary text-white me-2">
            <i class="bi bi-bar-chart-line-fill"></i>
        </a>
        <button class="btn btn-sm btn-secondary text-white me-2" onclick="resetZoomChart()">
            <i class="bi bi-zoom-out"></i>
        </button>
        <button class="btn btn-sm btn-secondary text-white me-2" onclick="download()">
            <i class="bi bi-download"></i>
        </button>
    </div>
    <br>
    <div class="container border border-danger rounded px-2 py-2">
        <canvas id="myChart"></canvas>
    </div>
    <script>
        // const moment = require('moment');
        // const Chart = require('chart.js/auto');
        // require('chartjs-adapter-moment')(moment);
        let myChart = new Chart(document.getElementById('myChart'), {
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
        function resetZoomChart() {
            myChart.resetZoom();
        };
        function download() {
            const imageLink = document.createElement('a');
            const canvas = document.getElementById('myChart');
            imageLink.download = 'chart_hb_vs_time.png';
            imageLink.href = canvas.toDataURL('image/png', 1);
            // document.write('<img src=" ' + imageLink + ' "/>');
            imageLink.click();
        };
    </script>
</body>

</html>