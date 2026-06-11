<?php
include('db_conn.php');
include('autoRedirect.php');

// Initialize variables to avoid undefined index warnings
$accountExecutive = '';
$accountName = '';
$callDate = '';
$globalSearch = '';

// Safe fallback for session parameters if not explicitly instantiated
$category = $_SESSION['category'] ?? $category ?? 'User';
$name = $_SESSION['name'] ?? $name ?? '';
$dept = $_SESSION['dept'] ?? $dept ?? ''; // Prevents manager lookup from breaking

// Capture explicit filters from GET requests
if (isset($_GET['accountExecutiveSearch'])) {
    $accountExecutive = $_GET['accountExecutiveSearch'];
}

if (isset($_GET['accountName'])) {
    $accountName = $_GET['accountName'];
}

if (isset($_GET['callDate'])) {
    $callDate = $_GET['callDate'];
}

// Check if user actively requested date ranges via Advanced Filters modal
$callDateStart = isset($_GET['callDateStart']) ? $_GET['callDateStart'] : '';
$callDateEnd = isset($_GET['callDateEnd']) ? $_GET['callDateEnd'] : '';

// Capture the global search parameter from the big search input box
if (isset($_GET['globalSearch'])) {
    $globalSearch = trim($_GET['globalSearch']);
}

// Construct the WHERE clause based on the form input
$whereConditions = [];

// Role-based restrictions mapping to table aliases 'e.'
if ($category == 'Manager') {
    if ($name == 'Ron Cabrera') {
        $whereConditions[] = "e.dept IN ('OP Sales - MFP/RISO', 'OP Consumables', 'OP Sales - PP')";
    } else {
        $whereConditions[] = "e.dept LIKE '%" . mysqli_real_escape_string($conn, $dept) . "%'";
    }
    if (!empty($accountExecutive)) {
        $whereConditions[] = "e.accExec LIKE '%" . mysqli_real_escape_string($conn, $accountExecutive) . "%'";
    }
}

if ($category == 'Admin' || $category == 'VP') {
    if (!empty($accountExecutive)) {
        $whereConditions[] = "e.accExec LIKE '%" . mysqli_real_escape_string($conn, $accountExecutive) . "%'";
    }
}

if ($category == 'User') {
    $whereConditions[] = "e.accExec LIKE '%" . mysqli_real_escape_string($conn, $name) . "%'";
}

// Structural Modal Filters
if (!empty($accountName)) {
    $whereConditions[] = "e.accName LIKE '%" . mysqli_real_escape_string($conn, $accountName) . "%'";
}

if (!empty($callDate)) {
    $whereConditions[] = "e.callDate = '" . mysqli_real_escape_string($conn, $callDate) . "'";
}

// FIXED: Date Range Filter - Only restrict queries if dates are explicitly submitted by user
if (!empty($callDateStart) && !empty($callDateEnd)) {
    $whereConditions[] = "e.callDate BETWEEN '" . mysqli_real_escape_string($conn, $callDateStart) . "' AND '" . mysqli_real_escape_string($conn, $callDateEnd) . "'";
}

// Global Search tracking
if (!empty($globalSearch)) {
    $escapedSearch = mysqli_real_escape_string($conn, $globalSearch);
    $whereConditions[] = "(e.LID LIKE '%$escapedSearch%' 
                          OR e.accName LIKE '%$escapedSearch%' 
                          OR e.projTitle LIKE '%$escapedSearch%'
                          OR e.accExec LIKE '%$escapedSearch%')";
}

$whereConditions[] = "e.is_deleted = 0"; // Always filter out deleted records

// Combine conditions with AND
$condition = implode(" AND ", $whereConditions);

// Pagination Setup
$records_per_page = 20;
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($current_page < 1) { $current_page = 1; }
$start_record = ($current_page - 1) * $records_per_page;

// Count total records matching final built criteria
$sql_count = "SELECT COUNT(*) as total FROM encoded e";
if (!empty($condition)) {
    $sql_count .= " WHERE $condition";
}
$result_count = mysqli_query($conn, $sql_count);
$total_records = mysqli_fetch_assoc($result_count)['total'] ?? 0;

// Main execution query binding category descriptive names via LEFT JOIN
$sql = "SELECT e.*, c.category_name AS status_name 
        FROM encoded e
        LEFT JOIN categories c ON e.accStatus = c.id";

if (!empty($condition)) {
    $sql .= " WHERE $condition";
}
$sql .= " ORDER BY e.id DESC LIMIT $start_record, $records_per_page";

// Execute the paginated query for array access looping in UI
$accountResult = mysqli_query($conn, $sql);

// Calculate total pages safely
$total_pages = $total_records > 0 ? ceil($total_records / $records_per_page) : 1;
?>