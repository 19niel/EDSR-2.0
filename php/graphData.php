<?php
include('db_conn.php');
include('autoRedirect.php');
include('dates.php');

$callDateStart = '';
$callDateEnd = '';

if (isset($_POST['callDateStart'])) {
    $callDateStart = $_POST['callDateStart'];
}

if (isset($_POST['callDateEnd'])) {
    $callDateEnd = $_POST['callDateEnd'];
}

// Construct the WHERE clause based on the form input and date range
$whereConditions = [];

if ($category == 'Manager') {
    $whereConditions[] = "dept LIKE '%$dept%'";
    if (!empty($accountExecutive)) {
        $whereConditions[] = "accExec LIKE '%$accountExecutive%'";
    }
}

if ($category == 'Admin' || $category == 'VP') {
    if (!empty($accountExecutive)) {
        $whereConditions[] = "accExec LIKE '%$accountExecutive%'";
    }
}

if ($category == 'User') {
    $whereConditions[] = "accExec LIKE '%$name%'";
}

// Assuming 'callDateStart' and 'callDateEnd' are always provided together
if (!empty($callDateStart) && !empty($callDateEnd)) {
    $whereConditions[] = "callDate BETWEEN '$callDateStart' AND '$callDateEnd'";
}

if (empty($callDateStart) && empty($callDateEnd)) {
    $whereConditions[] = "callDate BETWEEN '$minDate' AND '$max'";
}

// Combine conditions with AND
$condition = implode(" AND ", $whereConditions);

// Add the condition to the SQL queries
$sqlBar = "SELECT callDate, COUNT(*) AS rowCount FROM encoded";
$sqlActual = "SELECT COUNT(DISTINCT accName) AS actualCount FROM encoded";
$sqlLine = "SELECT callDate, COUNT(DISTINCT accName) AS statusCount FROM encoded";
$sqlClose = "SELECT callDate, COUNT(DISTINCT accName) AS closeCount FROM encoded";

if (!empty($condition)) {
    $sqlBar .= " WHERE $condition GROUP BY callDate ORDER BY callDate";
    $sqlActual .= " WHERE $condition ORDER BY callDate";
    $sqlLine .= " WHERE $condition AND accStatus IN ('Closed', 'Delivered') GROUP BY callDate ORDER BY callDate";
    $sqlClose .= " WHERE $condition AND accStatus IN ('Closed', 'Delivered') GROUP BY callDate ORDER BY callDate";
}

// Execute the queries
$barResult = mysqli_query($conn, $sqlBar);
$actualResult = mysqli_query($conn, $sqlActual);
$lineResult = mysqli_query($conn, $sqlLine);
$closeResult = mysqli_query($conn, $sqlClose);

$data = [];
$callCount = 0;
$closedCount = 0;
$actualCount = 0;
$actualCloseCount = 0;

while ($barRow = mysqli_fetch_assoc($barResult)) {
    // Fetch a row from $lineResult
    $lineRow = mysqli_fetch_assoc($lineResult);

    // Initialize lineRow if it's null
    if ($lineRow === null) {
        $lineRow = array('statusCount' => 0);
    }

    // Fetch a row from $actualResult
    $actualRow = mysqli_fetch_assoc($actualResult);

    // Initialize actualRow if it's null
    if ($actualRow === null) {
        $actualRow = array('actualCount' => 0);
    }

    $closeRow = mysqli_fetch_assoc($closeResult);

    // Initialize actualRow if it's null
    if ($closeRow === null) {
        $closeRow = array('closeCount' => 0);
    }

    // Combine results from all three queries into a single row
    $combinedRow = array_merge($barRow, $lineRow, $actualRow, $closeRow);

    // Add the combined row to the data array
    $data[] = $combinedRow;

    // Update counts
    $callCount += $barRow['rowCount'];
    $actualCount += $actualRow['actualCount'];
    $closedCount += $lineRow['statusCount'];
    $actualCloseCount += $closeRow['closeCount'];
}



// Output JSON-encoded data
try {
    echo json_encode($data);
} catch (Exception $e) {
    echo 'Exception: ', $e->getMessage(), "\n";
}

// Close your database connection if necessary
// mysqli_close($conn);
?>