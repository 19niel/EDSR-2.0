<?php
header('Content-Type: application/json');
include('db_conn.php');

$period = isset($_GET['period']) ? mysqli_real_escape_string($conn, trim($_GET['period'])) : 'all';

// Base tracking calculation structure
$query = "SELECT SUM(CAST(NULLIF(proposedPrice, '') AS DECIMAL(10,2))) AS total_sales 
          FROM encoded 
          WHERE is_deleted = 0 AND accStatus = 230";

// 🎯 FILTER SWITCH: Query conditions now bind directly to progressDate column layers
if ($period === 'current') {
    // Limits dataset exclusively to the current active calendar month bounds based on progressDate
    $query .= " AND MONTH(progressDate) = MONTH(CURRENT_DATE()) AND YEAR(progressDate) = YEAR(CURRENT_DATE())";
} else if ($period !== 'all' && is_numeric($period)) {
    // Limits datasets to specific selected operational month numeric indexing strings
    $monthVal = intval($period);
    $query .= " AND MONTH(progressDate) = $monthVal AND YEAR(progressDate) = YEAR(CURRENT_DATE())";
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
    echo json_encode([
        'success' => false,
        'message' => 'Failed to read tracking matrix values: ' . mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>