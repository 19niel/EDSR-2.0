<?php
include 'db_conn.php';
include 'autoRedirect.php';

if (isset($_POST['leave'])) {
    $employeeName = $_POST['employeeName'];
    $leaveDuration = $_POST['leaveDuration'];
    $leaveDate = $_POST['leaveDate'];
    $addLeave = "INSERT INTO leave_status(employee_name, leave_date, leave_duration) VALUES ('$employeeName','$leaveDate','$leaveDuration')";
    $addLeaveResult = mysqli_query($conn, $addLeave);
    if ($addLeaveResult) {
        echo '<script>
                alert("User Added.");
                window.location.href = "/e-dsr/pages/leaveData.php";
              </script>';
        exit();
    }
}

if (isset($_POST['holiday'])) {
    $branch = $_POST['branch'];
    $holidayDate = $_POST['holidayDate'];

    $addHoliday = "INSERT INTO holidays(date, branch) VALUES ('$holidayDate', '$branch')";
    $addHolidayResult = mysqli_query($conn, $addHoliday);
    if ($addHolidayResult) {
        echo '<script>
                alert("Holiday Added.");
                window.location.href = "/e-dsr/pages/leaveData.php";
              </script>';
        exit();
    }
}

if (isset($_POST['event'])) {
    $employeeName = $_POST['employeeName'];
    $duration = $_POST['duration'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $addLeave = "INSERT INTO event(employee_name, type, duration, date) VALUES ('$employeeName', '$type','$duration','$date')";
    $addLeaveResult = mysqli_query($conn, $addLeave);
    if ($addLeaveResult) {
        echo '<script>
                alert("Event/Training Added.");
                window.location.href = "/e-dsr/pages/leaveData.php";
              </script>';
        exit();
    }
}
?>