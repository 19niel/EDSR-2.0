<?php
// Include database connection
include('db_conn.php');

// Query to get all managers and their last login timestamp
$sql = "SELECT name, MAX(log_at) AS log_at 
        FROM users 
        WHERE category = 'Manager'
        AND is_deleted = 0
        GROUP BY name 
        ORDER BY log_at DESC";

$managerList = mysqli_query($conn, $sql);

// Check for errors
if (!$managerList) {
    die("Query failed: " . mysqli_error($conn));
}
?>
