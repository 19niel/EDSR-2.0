<?php
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

// Pull layout month tracking arguments securely from request variables
$monthFilter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'current';

// Enforce status filter condition 230 (Won Project) and ignore soft deleted assets
$whereClause = "WHERE is_deleted = 0 AND accStatus = 230"; 
$currentYear = date('Y');

if ($monthFilter === 'current') {
    $whereClause .= " AND MONTH(callDate) = MONTH(CURRENT_DATE()) AND YEAR(callDate) = '$currentYear'";
} elseif (in_array($monthFilter, ['Q1', 'Q2', 'Q3', 'Q4'])) {
    if ($monthFilter === 'Q1') $whereClause .= " AND MONTH(callDate) IN (1, 2, 3)";
    if ($monthFilter === 'Q2') $whereClause .= " AND MONTH(callDate) IN (4, 5, 6)";
    if ($monthFilter === 'Q3') $whereClause .= " AND MONTH(callDate) IN (7, 8, 9)";
    if ($monthFilter === 'Q4') $whereClause .= " AND MONTH(callDate) IN (10, 11, 12)";
    $whereClause .= " AND YEAR(callDate) = '$currentYear'";
} elseif ($monthFilter === 'custom') {
    $dateFrom = isset($_GET['dateFrom']) ? mysqli_real_escape_string($conn, trim($_GET['dateFrom'])) : '';
    $dateTo = isset($_GET['dateTo']) ? mysqli_real_escape_string($conn, trim($_GET['dateTo'])) : '';
    
    if (!empty($dateFrom) && !empty($dateTo)) {
        $whereClause .= " AND callDate BETWEEN '$dateFrom' AND '$dateTo'";
    } elseif (!empty($dateFrom)) {
        $whereClause .= " AND callDate >= '$dateFrom'";
    } elseif (!empty($dateTo)) {
        $whereClause .= " AND callDate <= '$dateTo'";
    }
} elseif ($monthFilter !== 'all' && preg_match('/^\d{2}$/', $monthFilter)) {
    $monthVal = intval($monthFilter);
    $whereClause .= " AND MONTH(callDate) = $monthVal AND YEAR(callDate) = '$currentYear'";
}

// Build query to select counts grouped strictly by cleaned team values
$query = "SELECT TRIM(UPPER(team)) as team_name, COUNT(*) as total_count 
          FROM encoded 
          $whereClause 
          AND team IS NOT NULL 
          AND TRIM(team) != ''
          GROUP BY TRIM(UPPER(team))";

$result = mysqli_query($conn, $query);

// Setup baseline payload map structure
$response = [    
    'makati' => 0,
    'qc'     => 0,
    'manila' => 0,
    'total'  => 0,
    'success'=> true
];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $team = strtoupper(trim($row['team_name']));
        $count = intval($row['total_count']);
        
        if ($team === 'MAKATI') {
            $response['makati'] = $count;
        } elseif ($team === 'QC') {
            $response['qc'] = $count;
        } elseif ($team === 'MANILA') {
            $response['manila'] = $count;
        }
    }
    
    // Aggregated data total summary tally calculation
    $response['total'] = $response['makati'] + $response['qc'] + $response['manila'];
} else {
    $response['success'] = false;
}

echo json_encode($response);
?>