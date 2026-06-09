<?php
// Ensure no hidden errors or notices pollute our clean JSON stream output
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

// Pull month tracking constraints securely
$monthFilter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'current';

// Compile date constraints matching filter states using callDate
$whereClause = "WHERE is_deleted = 0"; 
if ($monthFilter === 'current') {
    $currentMonth = date('m');
    $currentYear = date('Y');
    // 🎯 Changed from created_at to callDate
    $whereClause .= " AND MONTH(callDate) = '$currentMonth' AND YEAR(callDate) = '$currentYear'";
} elseif ($monthFilter !== 'all' && preg_match('/^\d{2}$/', $monthFilter)) {
    $currentYear = date('Y');
    // 🎯 Changed from created_at to callDate
    $whereClause .= " AND MONTH(callDate) = '$monthFilter' AND YEAR(callDate) = '$currentYear'";
}

// 🎯 Hitting your exact table 'encoded'
$query = "SELECT accCat, COUNT(*) as total_count FROM encoded $whereClause GROUP BY accCat";
$result = mysqli_query($conn, $query);

$response = [
    'existing' => 0,
    'new' => 0,
    'total' => 0,
    'success' => true
];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Handle variations in formatting securely (e.g., "Existing", "new", "EXISTING")
        $category = strtoupper(trim($row['accCat']));
        $count = intval($row['total_count']);
        
        if ($category === 'EXISTING') {
            $response['existing'] = $count;
        } elseif ($category === 'NEW') {
            $response['new'] = $count;
        }
    }
    
    // Calculate the absolute sum total explicitly
    $response['total'] = $response['existing'] + $response['new'];
} else {
    // If the SQL query drops out completely, flag it so the JS console catches it cleanly
    $response['success'] = false;
    $response['error_message'] = mysqli_error($conn);
}

// Clear out output buffers to make sure only pure clean JSON prints out
if (ob_get_length()) ob_clean();

echo json_encode($response);
mysqli_close($conn);
?>