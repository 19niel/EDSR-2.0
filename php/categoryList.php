<?php
include ('db_conn.php');
include ('autoRedirect.php');

$sql = "SELECT * FROM categories WHERE is_deleted = 0";
$categoryResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Account Category' AND is_deleted = 0";
$accountCategoryResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'SBU' AND is_deleted = 0";
$sbuResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Product Type' AND is_deleted = 0";
$productTypeResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Type of End-User' AND is_deleted = 0";
$endUserTypeResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Segment' AND is_deleted = 0";
$segmentResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Industry' AND is_deleted = 0";
$industryResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Source of Account' AND is_deleted = 0";
$accountSourceResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Existing System' AND is_deleted = 0";
$existingSystemResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Device Condition' AND is_deleted = 0";
$deviceConditionResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Contract Type' AND is_deleted = 0";
$contractTypeResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Terms of Payment' AND is_deleted = 0";
$paymentTermsResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Nature of Call' AND is_deleted = 0";
$callNatureResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM categories WHERE field = 'Account Status' AND is_deleted = 0";
$accountstatusResult = mysqli_query($conn, $sql);


// Pagination For Customize Page
// Set records per page
$records_per_page = 20;

// Get the current page from the URL (default is 1)
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the starting record
$start_record = ($current_page - 1) * $records_per_page;

// Count total records
$sql_count = "SELECT COUNT(*) as total FROM categories WHERE is_deleted = 0";
$result_count = mysqli_query($conn, $sql_count);
$total_records = mysqli_fetch_assoc($result_count)['total'];

// Fetch paginated records
$sql = "SELECT * FROM categories WHERE is_deleted = 0 ORDER BY id DESC LIMIT $start_record, $records_per_page";
$categoryResult = mysqli_query($conn, $sql);

// Calculate total pages
$total_pages = ceil($total_records / $records_per_page);



?>