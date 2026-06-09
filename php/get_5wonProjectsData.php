<?php
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

$monthFilter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'current';

// Filter explicitly for Closed-Won Opportunities (accStatus = '230')
$whereClause = "WHERE is_deleted = 0 AND accStatus = '230'";

if ($monthFilter === 'current') {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $whereClause .= " AND MONTH(callDate) = '$currentMonth' AND YEAR(callDate) = '$currentYear'";
} elseif ($monthFilter !== 'all' && preg_match('/^\d{2}$/', $monthFilter)) {
    $currentYear = date('Y');
    $whereClause .= " AND MONTH(callDate) = '$monthFilter' AND YEAR(callDate) = '$currentYear'";
}

// 🎯 Added clear formatting parsing rules for progressDate to keep it thin
$query = "SELECT 
            id,
            COALESCE(NULLIF(TRIM(accExec), ''), 'Unassigned') as exec_name,
            COALESCE(NULLIF(TRIM(accName), ''), 'Unknown') as client_name,
            COALESCE(DATE_FORMAT(progressDate, '%m/%d/%Y'), 'N/A') as formatted_date,
            COALESCE(proposedPrice, 0) as amount
          FROM encoded 
          $whereClause 
          ORDER BY id DESC";

$result = mysqli_query($conn, $query);
$projectsList = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $projectsList[] = [
            'id' => $row['id'], // 🎯 Captured record id
            'accExec' => $row['exec_name'],
            'accName' => ucwords(strtolower($row['client_name'])),
            'progressDate' => $row['formatted_date'],
            'proposedPrice' => floatval($row['amount'])
        ];
    }
    
    echo json_encode([
        'success' => true, 
        'data' => $projectsList
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'error_message' => mysqli_error($conn), 
        'data' => []
    ]);
}

mysqli_close($conn);
?>