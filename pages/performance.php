<?php
include('../php/autoRedirect.php');
include('../php/employeeList.php');
include('../php/dates.php');

$scopeSelect = "daily";
$businessUnit = "all";
$department = "all";
$accExec = "all";

if (isset($_POST['updatePerformance'])) {
    $scopeSelect = $_POST['scope'] ?? $scopeSelect;
    $businessUnit = $_POST['businessUnit'] ?? $businessUnit;
    $department = $_POST['department'] ?? $department;
    $accExec = $_POST['accExec'] ?? $accExec;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <link rel="stylesheet" href="/e-dsr/css/sidebar.css">
        <link rel="stylesheet" href="../css/performance.css">
        <title>E-DSR - Welcome Page</title>
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.27/jspdf.plugin.autotable.js"></script>
    </head>
    <body>
        <?php include('header.php'); ?>

        <div class="container-fluid">
            <div class="row">
                <main class="col-12 col-md-10 mx-auto px-4">
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap">
                        <h3 class="m-0">Performance</h3>
                        <div>
                            <button id="downloadPdfBtn" type="button" class="btn btn-primary">Download</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePerformanceModal">Filter</button>
                        </div>
                    </div>

                    <div class="container">
                        <div class="bg-white align-items-center pb-2 my-5">
                            <div class="card-header text-center bg-white">
                                <h2 class="card-title mb-0">
                                    <?php
                                    switch ($scopeSelect) {
                                        case "daily":
                                            echo "Daily Calls Percentage";
                                            break;
                                        case "weekly":
                                            echo "Weekly Calls Percentage";
                                            break;
                                        case "monthly":
                                            echo "Monthly Calls Percentage";
                                            break;
                                        case "yearly":
                                            echo "Yearly Calls Percentage";
                                            break;
                                    }
                                    ?>
                                </h2>
                            </div>
                            <div style="position: relative; width:100%; height: clamp(200px, 60vh, 600px); " class="d-flex justify-content-center align-items-center">
                                <canvas id="performanceChart"></canvas>
                            </div>


                        </div>
                    </div>

                    <div>
                        <!-- Modal -->
                        <?php include('./modals/performanceFilter.php') ?>

                        <div class="row">
                            <div class="col-12 card-header text-center bg-white">
                                <h2 class="sticky-header">SUMMARY OF E-DSR</h2>
                            </div>
                            <div class="col-12 container-fluid align-items-center table-responsive">
                                <table class="table table-sm table-striped align-middle" id="table">
                                    <caption>SUMMARY OF E-DSR</caption>
                                    <?php include 'performance_table.php'; ?>
                                </table>
                            </div>
                        </div>
                    </div> 
                </main>
            </div>
        </div>
    </body>

    <script type="text/javascript" src="../js/downloadPerformance.js"></script>
    <script>
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const labels = <?= json_encode($labels) ?>;        
        const sets = <?= json_encode($sets) ?>;
        const numDatasets = sets.length;
        const data = <?= json_encode($result2DArray) ?>;

        const datasets = sets.map((set, index) => ({
            label: set,
            data: data[index],
            borderColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 1)`,
            backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.2)`,
            borderWidth: 1,
        }));

        new Chart(ctx, {
            type: 'bar',
            data: { labels, datasets },
            options: { responsive: true, scales: { y: { beginAtZero: true } } },
        });
    </script>
</html>
