<?php
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

$monthFilter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'current';

$whereClause = "WHERE is_deleted = 0";

if ($monthFilter === 'current') {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $whereClause .= " AND MONTH(callDate) = '$currentMonth' AND YEAR(callDate) = '$currentYear'";
} elseif ($monthFilter !== 'all' && preg_match('/^\d{2}$/', $monthFilter)) {
    $currentYear = date('Y');
    $whereClause .= " AND MONTH(callDate) = '$monthFilter' AND YEAR(callDate) = '$currentYear'";
}

// SQL Query targeting accStatus and dynamic aggregate totals from encoded
$query = "SELECT accStatus, 
                 COUNT(*) as total_accounts, 
                 SUM(COALESCE(proposedPrice, 0)) as total_volume 
          FROM encoded 
          $whereClause 
          GROUP BY accStatus";

$result = mysqli_query($conn, $query);

// 🎯 FIXED KEY MAPPING ARRAY SCHEMAS HERE
$pipelineData = [
    '345' => ['status' => 'Qualified',   'accounts' => 0, 'volume' => 0.00],
    '346' => ['status' => 'Negotiation', 'accounts' => 0, 'volume' => 0.00],
    '230' => ['status' => 'Won',         'accounts' => 0, 'volume' => 0.00],
    '348' => ['status' => 'Lost',        'accounts' => 0, 'volume' => 0.00],
    '349' => ['status' => 'Dropped',     'accounts' => 0, 'volume' => 0.00]
];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $statusId = trim($row['accStatus']);
        
        if (array_key_exists($statusId, $pipelineData)) {
            $pipelineData[$statusId]['accounts'] = intval($row['total_accounts']);
            $pipelineData[$statusId]['volume'] = floatval($row['total_volume']);
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $pipelineData
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error_message' => mysqli_error($conn),
        'data' => $pipelineData
    ]);
}

mysqli_close($conn);
?>