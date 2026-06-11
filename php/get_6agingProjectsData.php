<?php
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

// 🎯 Step 1: Query the custom aging threshold distance from dashboard_settings
$thresholdDays = 60; // System baseline fallback limit if not found in DB
$settingsQuery = "SELECT setting_value FROM dashboard_settings WHERE setting_key = 'aging_days_threshold' LIMIT 1";
$settingsResult = mysqli_query($conn, $settingsQuery);

if ($settingsResult && mysqli_num_rows($settingsResult) > 0) {
    $settingsRow = mysqli_fetch_assoc($settingsResult);
    $thresholdDays = intval($settingsRow['setting_value']);
}

// 🎯 Step 2: Use the dynamic $thresholdDays variable in the DATEDIFF filter
// Fully independent of month variables to show critical unattended items from oldest to newest
$query = "SELECT 
             lid,
            COALESCE(NULLIF(TRIM(accName), ''), 'Unknown Client') as client_name,
            COALESCE(DATE_FORMAT(progressDate, '%m/%d/%Y'), 'N/A') as formatted_date
          FROM encoded 
          WHERE is_deleted = 0 
            AND progressDate IS NOT NULL 
            AND DATEDIFF(NOW(), progressDate) >= $thresholdDays
          ORDER BY progressDate ASC"; // Oldest unattended items prioritized at the top of the table

$result = mysqli_query($conn, $query);
$agingList = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $agingList[] = [
            'LID' => $row['lid'],
            'accName' => ucwords(strtolower($row['client_name'])),
            'progressDate' => $row['formatted_date']
        ];
    }
    
    echo json_encode([
        'success' => true, 
        'data' => $agingList
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