<?php
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

$monthFilter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'current';

$whereClause = "WHERE is_deleted = 0";
$currentYear = date('Y');

if ($monthFilter === 'current') {
    $whereClause .= " AND MONTH(deliveryDate) = MONTH(CURRENT_DATE()) AND YEAR(deliveryDate) = '$currentYear'";
} elseif (in_array($monthFilter, ['Q1', 'Q2', 'Q3', 'Q4'])) {
    if ($monthFilter === 'Q1') $whereClause .= " AND MONTH(deliveryDate) IN (1, 2, 3)";
    if ($monthFilter === 'Q2') $whereClause .= " AND MONTH(deliveryDate) IN (4, 5, 6)";
    if ($monthFilter === 'Q3') $whereClause .= " AND MONTH(deliveryDate) IN (7, 8, 9)";
    if ($monthFilter === 'Q4') $whereClause .= " AND MONTH(deliveryDate) IN (10, 11, 12)";
    $whereClause .= " AND YEAR(deliveryDate) = '$currentYear'";
} elseif ($monthFilter === 'custom') {
    $dateFrom = isset($_GET['dateFrom']) ? mysqli_real_escape_string($conn, trim($_GET['dateFrom'])) : '';
    $dateTo = isset($_GET['dateTo']) ? mysqli_real_escape_string($conn, trim($_GET['dateTo'])) : '';
    
    if (!empty($dateFrom) && !empty($dateTo)) {
        $whereClause .= " AND deliveryDate BETWEEN '$dateFrom' AND '$dateTo'";
    } elseif (!empty($dateFrom)) {
        $whereClause .= " AND deliveryDate >= '$dateFrom'";
    } elseif (!empty($dateTo)) {
        $whereClause .= " AND deliveryDate <= '$dateTo'";
    }
} elseif ($monthFilter !== 'all' && preg_match('/^\d{2}$/', $monthFilter)) {
    $monthVal = intval($monthFilter);
    $whereClause .= " AND MONTH(deliveryDate) = $monthVal AND YEAR(deliveryDate) = '$currentYear'";
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