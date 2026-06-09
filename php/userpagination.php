<?php
include('db_conn.php');
include('autoRedirect.php');

$sql = "SELECT * FROM ( SELECT e.*, ROW_NUMBER() OVER (PARTITION BY accexec_id ORDER BY created_at DESC) AS rn FROM encoded e ) ranked WHERE rn = 1";
$encodedList = mysqli_query($conn, $sql);

// Define how many entries per page
$entries_per_page = 20;

// Get the current page from the query string or default to the first page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $entries_per_page;

// Get the total number of entries
$sql_total = "SELECT COUNT(*) AS total FROM users WHERE name != '$name' AND is_deleted = 0";
$result_total = mysqli_query($conn, $sql_total);
$total_rows = mysqli_fetch_assoc($result_total)['total'];
$total_pages = ceil($total_rows / $entries_per_page);

// Fetch the data for the current page, sorted by last added user
$sql_paginated = "SELECT * FROM users WHERE name != '$name' AND is_deleted = 0 ORDER BY id DESC LIMIT $entries_per_page OFFSET $offset";
$userList = mysqli_query($conn, $sql_paginated);

?>