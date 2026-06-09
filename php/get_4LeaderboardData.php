<?php
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

$monthFilter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'current';

$whereClause = "WHERE is_deleted = 0 AND accStatus IN ('345', '346')";

if ($monthFilter === 'current') {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $whereClause .= " AND MONTH(callDate) = '$currentMonth' AND YEAR(callDate) = '$currentYear'";
} elseif ($monthFilter !== 'all' && preg_match('/^\d{2}$/', $monthFilter)) {
    $currentYear = date('Y');
    $whereClause .= " AND MONTH(callDate) = '$monthFilter' AND YEAR(callDate) = '$currentYear'";
}

// Target the top 5 Account Executives based on total active volume
$query = "SELECT accExec, 
                 SUM(COALESCE(proposedPrice, 0)) as total_amount 
          FROM encoded 
          $whereClause 
          GROUP BY accExec 
          ORDER BY total_amount DESC 
          LIMIT 5";

$result = mysqli_query($conn, $query);
$leaderboard = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Handle fallback names gracefully if clean text is missing
        $name = !empty(trim($row['accExec'])) ? trim($row['accExec']) : 'Unknown Executive';
        $leaderboard[] = [
            'name' => $name,
            'amount' => floatval($row['total_amount'])
        ];
    }
    echo json_encode(['success' => true, 'data' => $leaderboard]);
} else {
    echo json_encode(['success' => false, 'error_message' => mysqli_error($conn), 'data' => []]);
}

mysqli_close($conn);
?>