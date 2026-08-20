<?php
header('Content-Type: application/json');
include('db_conn.php');

$period = isset($_GET['period']) ? mysqli_real_escape_string($conn, trim($_GET['period'])) : 'all';

// Base tracking calculation structure
$query = "SELECT SUM(CAST(NULLIF(proposedPrice, '') AS DECIMAL(10,2))) AS total_sales 
          FROM encoded 
          WHERE is_deleted = 0 AND accStatus = 230";

$currentYear = date('Y');

// 🎯 FILTER SWITCH: Updated to handle Months, Quarters, Custom Ranges, and All Time
if ($period === 'current') {
    // Current month
    $query .= " AND MONTH(deliveryDate) = MONTH(CURRENT_DATE()) AND YEAR(deliveryDate) = '$currentYear'";
} elseif (in_array($period, ['Q1', 'Q2', 'Q3', 'Q4'])) {
    // Quarterly logic
    if ($period === 'Q1') $query .= " AND MONTH(deliveryDate) IN (1, 2, 3)";
    if ($period === 'Q2') $query .= " AND MONTH(deliveryDate) IN (4, 5, 6)";
    if ($period === 'Q3') $query .= " AND MONTH(deliveryDate) IN (7, 8, 9)";
    if ($period === 'Q4') $query .= " AND MONTH(deliveryDate) IN (10, 11, 12)";
    $query .= " AND YEAR(deliveryDate) = '$currentYear'";
} elseif ($period === 'custom') {
    // Custom date range logic
    $dateFrom = isset($_GET['dateFrom']) ? mysqli_real_escape_string($conn, trim($_GET['dateFrom'])) : '';
    $dateTo = isset($_GET['dateTo']) ? mysqli_real_escape_string($conn, trim($_GET['dateTo'])) : '';
    
    if (!empty($dateFrom) && !empty($dateTo)) {
        $query .= " AND deliveryDate BETWEEN '$dateFrom' AND '$dateTo'";
    } elseif (!empty($dateFrom)) {
        $query .= " AND deliveryDate >= '$dateFrom'";
    } elseif (!empty($dateTo)) {
        $query .= " AND deliveryDate <= '$dateTo'";
    }
} elseif ($period !== 'all' && preg_match('/^\d{2}$/', $period)) {
    // Specific month
    $monthVal = intval($period);
    $query .= " AND MONTH(deliveryDate) = $monthVal AND YEAR(deliveryDate) = '$currentYear'";
}

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalSales = floatval($row['total_sales'] ?? 0);
    
    echo json_encode([
        'success' => true,
        'totalSales' => $totalSales
    ]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>