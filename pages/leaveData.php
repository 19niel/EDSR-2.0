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
    <!-- Include Bootstrap CSS for UI styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom stylesheets for sidebar and table -->
    <link rel="stylesheet" href="/e-dsr/css/sidebar.css" />
    <link rel="stylesheet" href="/e-dsr/css/table.css" />
    <title>E-DSR - Leave Data</title>
    <!-- Include Bootstrap JS for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Include Chart.js for graphical visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Additional inline styling for sticky headers -->
    <style>
        .sticky-header {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 100;
        }
    </style>
</head>

<body>
    <!-- Include reusable header component -->
    <?php include ('header.php'); ?>
    
    <div class="container-fluid">
        <div class="row">

            <!-- Main content area -->
            <main class="col-12 col-md-10 mx-auto px-4">
                <!-- Sticky top section for page title and action buttons -->
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap">
                    <h3 class="m-0">Leave Data</h3>
                    <div class="d-flex gap-2">
                        <!-- Button to trigger Event/Training modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal">Event/Training</button>
                        <!-- Button to trigger Add Holiday modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#holidayModal">Add Holiday</button>
                        <!-- Button to trigger Add Leave modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#leaveModal">Add Leave</button>
                        <!-- Dropdown menu to select different tables -->
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Table Data</button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="leaveData.php?table=leave">Leave</a></li>
                            <li><a class="dropdown-item" href="leaveData.php?table=holiday">Holiday</a></li>
                            <li><a class="dropdown-item" href="leaveData.php?table=event">Event/Training</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Main content area with modals and table -->
                <div>
                    <!-- Modal for Adding Leave -->
                    <?php include('./modals/addLeave.php') ?>

                    <!-- Modal for Adding Holiday -->
                    <?php include('./modals/addHoliday.php') ?>

                    <!-- Event/Training Modal -->
                    <?php include('./modals/addEvent.php') ?>

                    <!-- Table displaying the selected data -->
                    <div class="table-responsive py-3">
                        <table id="largeTable" class="table table-sm table-striped table-hover align-middle">
                            <?php include 'leaveTable.php'; // Dynamic table data ?>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
