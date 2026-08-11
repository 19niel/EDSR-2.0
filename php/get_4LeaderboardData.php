<?php
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

$monthFilter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'current';

$whereClause = "WHERE is_deleted = 0 AND accStatus IN ('345', '346')";
$currentYear = date('Y');

if ($monthFilter === 'current') {
    $whereClause .= " AND MONTH(progressDate) = MONTH(CURRENT_DATE()) AND YEAR(progressDate) = '$currentYear'";
} elseif (in_array($monthFilter, ['Q1', 'Q2', 'Q3', 'Q4'])) {
    if ($monthFilter === 'Q1') $whereClause .= " AND MONTH(progressDate) IN (1, 2, 3)";
    if ($monthFilter === 'Q2') $whereClause .= " AND MONTH(progressDate) IN (4, 5, 6)";
    if ($monthFilter === 'Q3') $whereClause .= " AND MONTH(progressDate) IN (7, 8, 9)";
    if ($monthFilter === 'Q4') $whereClause .= " AND MONTH(progressDate) IN (10, 11, 12)";
    $whereClause .= " AND YEAR(progressDate) = '$currentYear'";
} elseif ($monthFilter === 'custom') {
    $dateFrom = isset($_GET['dateFrom']) ? mysqli_real_escape_string($conn, trim($_GET['dateFrom'])) : '';
    $dateTo = isset($_GET['dateTo']) ? mysqli_real_escape_string($conn, trim($_GET['dateTo'])) : '';
    
    if (!empty($dateFrom) && !empty($dateTo)) {
        $whereClause .= " AND progressDate BETWEEN '$dateFrom' AND '$dateTo'";
    } elseif (!empty($dateFrom)) {
        $whereClause .= " AND progressDate >= '$dateFrom'";
    } elseif (!empty($dateTo)) {
        $whereClause .= " AND progressDate <= '$dateTo'";
    }
} elseif ($monthFilter !== 'all' && preg_match('/^\d{2}$/', $monthFilter)) {
    $monthVal = intval($monthFilter);
    $whereClause .= " AND MONTH(progressDate) = $monthVal AND YEAR(progressDate) = '$currentYear'";
}

// Target the top 5 Account Executives based on total active volume
$query = "SELECT accExec, 
                 SUM(COALESCE(proposedPrice, 0)) as total_amount 
          FROM encoded 
          $whereClause 
          GROUP BY accExec 
          HAVING total_amount > 0
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