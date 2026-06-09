<?php
include('db_conn.php');
include('autoRedirect.php');

// Initialize variables to avoid undefined index warnings
$accountExecutive = '';
$accountName = '';
$callDate = '';
$currentMonthStart = date('Y-m-01');
$currentMonthEnd = date('Y-m-t');

// Set the callDateStart and callDateEnd to the current month
$callDateStart = $currentMonthStart;
$callDateEnd = $currentMonthEnd;

if (isset($_GET['accountExecutiveSearch'])) {
    $accountExecutive = $_GET['accountExecutiveSearch'];
}

if (isset($_GET['accountName'])) {
    $accountName = $_GET['accountName'];
}

if (isset($_GET['callDate'])) {
    $callDate = $_GET['callDate'];
}

if (isset($_GET['callDateStart'])) {
    $callDateStart = $_GET['callDateStart'];
}

if (isset($_GET['callDateEnd'])) {
    $callDateEnd = $_GET['callDateEnd'];
}


// Construct the WHERE clause based on the form input
$whereConditions = [];

// Explicitly use table aliases 'e.' to protect column ambiguity during table joins
if ($category == 'Manager') {
    if ($name == 'Ron Cabrera') {
        $whereConditions[] = "e.dept IN ('OP Sales - MFP/RISO', 'OP Consumables', 'OP Sales - PP')";
    } else {
        $whereConditions[] = "e.dept LIKE '%$dept%'";
    }
    if (!empty($accountExecutive)) {
        $whereConditions[] = "e.accExec LIKE '%$accountExecutive%'";
    }
}

if ($category == 'Admin' || $category == 'VP') {
    if (!empty($accountExecutive)) {
        $whereConditions[] = "e.accExec LIKE '%$accountExecutive%'";
    }
}

if ($category == 'User') {
    $whereConditions[] = "e.accExec LIKE '%$name%'";
}

if (!empty($accountName)) {
    $whereConditions[] = "e.accName LIKE '%$accountName%'";
}

if (!empty($callDate)) {
    $whereConditions[] = "e.callDate = '$callDate'";
}

if (!empty($callDateStart) && !empty($callDateEnd)) {
    $whereConditions[] = "e.callDate BETWEEN '$callDateStart' AND '$callDateEnd'";
}

$whereConditions[] = "e.is_deleted = 0"; // Always include this condition

// Combine conditions with AND
$condition = implode(" AND ", $whereConditions);

// Pagination
// Set records per page
$records_per_page = 20;

// Get the current page from the URL (default is 1)
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the starting record
$start_record = ($current_page - 1) * $records_per_page;

// Count total records with the WHERE clause applied
// (Using 'e' alias matches the logic pattern built for standardizing criteria queries)
$sql_count = "SELECT COUNT(*) as total FROM encoded e";
if (!empty($condition)) {
    $sql_count .= " WHERE $condition";
}
$result_count = mysqli_query($conn, $sql_count);
$total_records = mysqli_fetch_assoc($result_count)['total'];

/*
|--------------------------------------------------------------------------
| FIXED MAIN SELECTION QUERY
|--------------------------------------------------------------------------
| We use a LEFT JOIN to map the 'accStatus' column to your categories lookup table.
| This aliasing introduces 'status_name' directly into your search loop array.
| Note: Replace 'categories' with your actual database name if it uses something else.
*/
$sql = "SELECT e.*, c.category_name AS status_name 
        FROM encoded e
        LEFT JOIN categories c ON e.accStatus = c.id";

if (!empty($condition)) {
    $sql .= " WHERE $condition";
}
$sql .= " ORDER BY e.id DESC LIMIT $start_record, $records_per_page";

// Execute the paginated query
$accountResult = mysqli_query($conn, $sql);

// Calculate total pages
$total_pages = ceil($total_records / $records_per_page);
?>