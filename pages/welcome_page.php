<?php
include ('../php/autoRedirect.php');
include ('../php/managerList.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />
        <link rel="stylesheet" href="/e-dsr/css/counters.css" />
        <title>Dashboard - E-DSR</title>
        <style>
            .mini-btns .card{
                height: 100%;
                justify-content: center;
            }
        </style>
    </head>
    <body>
        <?php include ('header.php'); ?>
        <div class="container-fluid">
            <div class="row">
                <main class="col-12 col-md-10 mx-auto px-4">
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <h3 class="m-0">Dashboard</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateGraphModal">Update Graph</button>
                    </div>
                    <div class="row g-3 py-3">
                        <?php include('./modals/updateGraph.php') ?>
                        <div class="col-xl-5 col-lg-6">
                            <div class="card">
                                <div class="card-header bg-white text-center">
                                    <h6 class="card-title mb-0">Reminders</h6>
                                </div>
                                <div class="card-body">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-white text-center">
                                            <h6 class="card-title mb-0">Daily Calls</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="chartContainer" style="position: relative; height: 100%; min-height: 300px;">
                                                <canvas id="barLineChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" id="managerLoginTimestamp">
                                    <div class="card">
                                        <div class="card-header bg-white text-center">
                                            <h6 class="card-title mb-0">Manager Login Timestamp</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped table-hover align-middle">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Last Log in</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = mysqli_fetch_assoc($managerList)) { // Fetch paginated user list ?>
                                                            <tr>
                                                                <td><?php echo $row['name']; ?></td>
                                                                <td><?php echo $row['log_at']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="courtesyVisit"></h4>
                                                <h6 class="mb-0 text-end">Courtesy Visit</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="messageCall"></h4>
                                                <h6 class="mb-0 text-end">Message/Call</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="virtualMeeting"></h4>
                                                <h6 class="mb-0 text-end">Virtual Meeting</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="scheduledMeeting"></h4>
                                                <h6 class="mb-0 text-end">F2F Meeting</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="email"></h4>
                                                <h6 class="mb-0 text-end">Email</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="callCountSpan"></h4>
                                                <h6 class="mb-0 text-end">Calls Made</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="actualCountSpan"></h4>
                                                <h6 class="mb-0 text-end">Account Numbers</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="actualClosedCountSpan"></h4>
                                                <h6 class="mb-0 text-end">Closed Calls</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card text-white bg-primary p-2">
                                        <div class="text-center">
                                            <div class="d-flex justify-content-between align-items-center px-3 gap-1">
                                                <h4 class="mb-0" id="conversionSpan"></h4>
                                                <h6 class="mb-0 text-end">Conversion</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="../js/barLineGraph.js"></script>
        
        <script src="../js/dashboard/dashboardCalendar.js"></script>
        <script src="../js/dashboard/dashboardCalls.js"></script>
        <script>
            var category = "<?php echo $category; ?>";
        </script>
        <script src="../js/hideElement.js"></script>
    </body>
</html>
