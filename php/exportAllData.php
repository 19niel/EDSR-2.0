<?php
include('db_conn.php');
session_start();
//test
$category = $_SESSION['category'] ?? '';
$name = $_SESSION['name'] ?? '';
$dept = $_SESSION['dept'] ?? '';

$accountExecutive = $_GET['accountExecutiveSearch'] ?? '';
$accountName = $_GET['accountName'] ?? '';
$callDateStart = $_GET['callDateStart'] ?? date('Y-m-01');
$callDateEnd = $_GET['callDateEnd'] ?? date('Y-m-t');

$progressDateStart = $_GET['progressDateStart'] ?? '';
$progressDateEnd = $_GET['progressDateEnd'] ?? '';
$accStatus = $_GET['accStatus'] ?? '';
$estimatedDelivery = $_GET['estimatedDelivery'] ?? '';
$globalSearch = $_GET['globalSearch'] ?? '';

$whereConditions = [];

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

if (!empty($accountName)) {
    $whereConditions[] = "e.accName LIKE '%" . mysqli_real_escape_string($conn, $accountName) . "%'";
}

if (!empty($callDateStart) && !empty($callDateEnd)) {
    $whereConditions[] = "e.callDate BETWEEN '" . mysqli_real_escape_string($conn, $callDateStart) . "' AND '" . mysqli_real_escape_string($conn, $callDateEnd) . "'";
}

if (!empty($progressDateStart) && !empty($progressDateEnd)) {
    $whereConditions[] = "e.progressDate BETWEEN '" . mysqli_real_escape_string($conn, $progressDateStart) . "' AND '" . mysqli_real_escape_string($conn, $progressDateEnd) . "'";
}

if (!empty($accStatus)) {
    $whereConditions[] = "e.accStatus = '" . mysqli_real_escape_string($conn, $accStatus) . "'";
}

if (!empty($estimatedDelivery)) {
    $whereConditions[] = "e.estimatedDelivery = '" . mysqli_real_escape_string($conn, $estimatedDelivery) . "'";
}

if (!empty($globalSearch)) {
    $escapedSearch = mysqli_real_escape_string($conn, $globalSearch);
    $whereConditions[] = "(e.LID LIKE '%$escapedSearch%' 
                          OR e.accName LIKE '%$escapedSearch%' 
                          OR e.projTitle LIKE '%$escapedSearch%'
                          OR e.accExec LIKE '%$escapedSearch%'
                          OR e.callDate LIKE '%$escapedSearch%'
                          OR e.proposedPrice LIKE '%$escapedSearch%'
                          OR e.estimatedDelivery LIKE '%$escapedSearch%'
                          OR e.progressDate LIKE '%$escapedSearch%'
                          OR c.category_name LIKE '%$escapedSearch%')";
}

$whereConditions[] = "e.is_deleted = 0";

$condition = implode(" AND ", $whereConditions);
$sql = "SELECT e.*, c.category_name AS status_name FROM encoded e LEFT JOIN categories c ON e.accStatus = c.id";
if (!empty($condition)) {
    $sql .= " WHERE $condition";
}
$sql .= " ORDER BY e.id DESC";

$result = mysqli_query($conn, $sql);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="export.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'ID', 'Account Executive', 'Account Name', 'Call Date', 'End User', 'Address', 'Area',
    'Account Category', 'Segment', 'Industry', 'Account Source', 'Contact Person',
    'Designation', 'Contact Number', 'Email Address', 'Decision Maker', 'DM Contact Number',
    'DM Designation', 'Existing System', 'Contract Type', 'Contract Start Date',
    'Contract End Date', 'Proposed System', 'Proposed Price', 'Payment Terms',
    'Call Nature', 'Account Status', 'Follow Up Action', 'What Transpired'
]);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'], $row['accExec'], $row['accName'], $row['callDate'], $row['endUser'], $row['address'],
        $row['area'], $row['accCat'], $row['segment'], $row['industry'], $row['accSource'], $row['contactPerson'],
        $row['designation'], $row['contactNumber'], $row['email'], $row['decisionMaker'], $row['dmNumber'],
        $row['dmDesignation'], $row['existingSystem'], $row['contactType'], $row['startContractDate'],
        $row['endContractDate'], $row['proposedSystem'], $row['proposedPrice'], $row['paymentTerms'],
        $row['callNature'], $row['status_name'] ?? $row['accStatus'], $row['actionFollow'], $row['whatTranspired']
    ]);
}

fclose($output);
exit;
