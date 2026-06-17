<?php
// Includes necessary PHP scripts for handling various functionalities
include ('../php/autoRedirect.php'); // Redirects based on session or user state
include ('../php/performanceList.php'); // Fetches performance data for employees
include ('../php/employeeList.php'); // Fetches the list of employees
include ('../php/db_conn.php'); // Handles database connection
include ('../php/addLeave.php'); // Manages adding leave requests

// Set the default table to 'leave' unless a specific table is passed via the 'table' query parameter
$table = 'leave';
if (isset($_GET['table'])) {
    $table = $_GET['table'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="E-DSR Leave Data Records">

    <script>
    (function(){
        var t = localStorage.getItem('edsr-theme') || 'light';
        document.documentElement.setAttribute('data-theme', t);
        document.documentElement.setAttribute('data-bs-theme', t);
        window.EDSR_THEME = t;
    })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="../css/theme.css" />
    <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />
    <link rel="stylesheet" href="/e-dsr/css/table.css" />
    
    <title>E-DSR - Leave Data</title>
    
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif !important;
        }
        .sticky-header {
            position: sticky;
            top: 0;
            background-color: var(--surface, #fff);
            z-index: 100;
        }
        .main-content-card {
            background: var(--surface, #fff);
            border-radius: 12px;
            border: 1px solid var(--border-color, #dee2e6);
        }
    </style>
</head>

<body>
    <?php include ('header.php'); ?>
    
    <div class="container-fluid py-4">
        <div class="row">

            <main class="col-12 col-xl-11 mx-auto px-4">
                
                <div class="d-flex justify-content-between align-items-center pb-4 mb-4 border-bottom flex-wrap gap-3">
                    <div>
                        <h3 class="m-0 fw-bold tracking-tight text-dark">Leave Data</h3>
                        <p class="text-muted small m-0 mt-1">Manage system entries, configure tracking parameters, and manage timeline lists.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary px-3 fw-medium shadow-sm rounded-3 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#eventModal">
                            Event/Training
                        </button>
                        <button type="button" class="btn btn-primary px-3 fw-medium shadow-sm rounded-3 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#holidayModal">
                            Add Holiday
                        </button>
                        <button type="button" class="btn btn-primary px-3 fw-medium shadow-sm rounded-3 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#leaveModal">
                            Add Leave
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle px-3 fw-medium shadow-sm rounded-3" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Table Data
                            </button>
                            <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="leaveData.php?table=leave">Leave</a></li>
                                <li><a class="dropdown-item" href="leaveData.php?table=holiday">Holiday</a></li>
                                <li><a class="dropdown-item" href="leaveData.php?table=event">Event/Training</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php include('./modals/addLeave.php') ?>
                <?php include('./modals/addHoliday.php') ?>
                <?php include('./modals/addEvent.php') ?>

                <div class="main-content-card shadow-sm overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table id="largeTable" class="table table-hover align-middle m-0 modern-table">
                            <?php include 'leaveTable.php'; // Dynamic table data ?>
                        </table>
                    </div>
                </div>
                
            </main>
        </div>
    </div>
</body>
</html>