<?php
include('db_conn.php');

$accountName = isset($_GET['accountName']) ? $_GET['accountName'] : '';
$accountExecutiveSearch = isset($_GET['accountExecutiveSearch']) ? $_GET['accountExecutiveSearch'] : '';

if (!empty($accountExecutiveSearch)) {
    // Filter account executives based on input
    $sql = "SELECT DISTINCT accExec FROM encoded WHERE accExec LIKE '%$accountExecutiveSearch%' AND is_deleted = 0";
} elseif (!empty($accountName)) {
    // Filter accounts by name
    $sql = "SELECT DISTINCT accName FROM encoded WHERE accName LIKE '%$accountName%' AND is_deleted = 0";
} else {
    // Default: Fetch all distinct account executives
    $sql = "SELECT DISTINCT accExec FROM encoded WHERE is_deleted = 0";
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["error" => "Database query failed."]);
    exit;
}

$accounts = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($accounts);
?>


