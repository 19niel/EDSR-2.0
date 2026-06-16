<?php
error_reporting(0);
ini_set('display_errors', 0);

include('db_conn.php');
header('Content-Type: application/json');

// Pull layout month tracking arguments securely from request variables
$monthFilter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'current';

// Enforce status filter condition 230 (Won Project) and ignore soft deleted assets
$whereClause = "WHERE is_deleted = 0 AND accStatus = 230"; 

if ($monthFilter === 'current') {
    $currentMonth = date('m');
    $currentYear = date('Y');
    $whereClause .= " AND MONTH(callDate) = '$currentMonth' AND YEAR(callDate) = '$currentYear'";
} elseif ($monthFilter !== 'all' && preg_match('/^\d{2}$/', $monthFilter)) {
    $currentYear = date('Y');
    $whereClause .= " AND MONTH(callDate) = '$monthFilter' AND YEAR(callDate) = '$currentYear'";
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