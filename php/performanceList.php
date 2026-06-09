<?php
include('db_conn.php');
include('autoRedirect.php');
include('dates.php');

$whereConditionsPerformance = [];
$datesArray = [];
$deptPerformance = mysqli_real_escape_string($conn, $_POST['deptPerformance'] ?? '');
$callDateStartPerformance = mysqli_real_escape_string($conn, $_POST['callDateStartPerformance'] ?? '');
$callDateEndPerformance = mysqli_real_escape_string($conn, $_POST['callDateEndPerformance'] ?? '');

if ($category == 'Manager') {
    $whereConditionsPerformance[] = "dept LIKE '%$dept%'";
}

if ($category == 'User') {
    $whereConditionsPerformance[] = "accExec LIKE '%$name%'";
}

if (!empty($deptPerformance)) {
    $whereConditionsPerformance[] = "dept LIKE '%$deptPerformance%'";
}

if (!empty($callDateStartPerformance) && !empty($callDateEndPerformance)) {
    $whereConditionsPerformance[] = "callDate BETWEEN '$callDateStartPerformance' AND '$callDateEndPerformance'";
    $dateCondition = "callDate BETWEEN '$callDateStartPerformance' AND '$callDateEndPerformance'";
} else {
    $whereConditionsPerformance[] = "callDate BETWEEN '$min_date' AND '$max'";
    $dateCondition = "callDate BETWEEN '$min_date' AND '$max'";
}

$whereConditionsPerformance[] = "is_deleted = 0";

$dateSql = "SELECT DISTINCT callDate FROM encoded WHERE $dateCondition";
$dateQuery = mysqli_query($conn, $dateSql);
if ($dateQuery) {
    while ($row = mysqli_fetch_assoc($dateQuery)) {
        $datesArray[] = $row['callDate'];
    }
} else {
    echo "Error fetching dates: " . mysqli_error($conn);
    exit;
}

if (empty($datesArray)) {
    echo "No dates found for the given condition.";
    exit;
}

$conditionPerformance = implode(" AND ", $whereConditionsPerformance);
$performanceSql = "SELECT
            accExec AS AccountExecutive,
            " . implode(', ', array_map(function ($date) {
    return "SUM(CASE WHEN callDate = '$date' THEN 1 ELSE 0 END) AS '$date'";
}, $datesArray)) . "
        FROM
            encoded
        WHERE
            $conditionPerformance
        GROUP BY
            accExec;";

$accountResultPerformance = mysqli_query($conn, $performanceSql);
if (!$accountResultPerformance) {
    echo "Error executing performance query: " . mysqli_error($conn);
    exit;
}

$rows = []; // Initialize an array to store all rows
?>
