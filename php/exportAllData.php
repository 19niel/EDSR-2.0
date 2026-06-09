<?php
include('db_conn.php');
session_start();
//test
$category = $_SESSION['category'] ?? '';
$name = $_SESSION['name'] ?? '';
$dept = $_SESSION['dept'] ?? '';

$accountExecutive = $_GET['accountExecutiveSearch'] ?? '';
$accountName = $_GET['accountName'] ?? '';
$callDate = $_GET['callDate'] ?? '';
$callDateStart = $_GET['callDateStart'] ?? date('Y-m-01');
$callDateEnd = $_GET['callDateEnd'] ?? date('Y-m-t');

$whereConditions = [];

if ($category == 'Manager') {
    if ($name == 'Ron Cabrera') {
        $whereConditions[] = "dept IN ('OP Sales - MFP/RISO', 'OP Consumables', 'OP Sales - PP')";
    } else {
        $whereConditions[] = "dept LIKE '%" . mysqli_real_escape_string($conn, $dept) . "%'";
    }
    if (!empty($accountExecutive)) {
        $whereConditions[] = "accExec LIKE '%" . mysqli_real_escape_string($conn, $accountExecutive) . "%'";
    }
}

if ($category == 'Admin' || $category == 'VP') {
    if (!empty($accountExecutive)) {
        $whereConditions[] = "accExec LIKE '%" . mysqli_real_escape_string($conn, $accountExecutive) . "%'";
    }
}

if ($category == 'User') {
    $whereConditions[] = "accExec LIKE '%" . mysqli_real_escape_string($conn, $name) . "%'";
}

if (!empty($accountName)) {
    $whereConditions[] = "accName LIKE '%" . mysqli_real_escape_string($conn, $accountName) . "%'";
}

if (!empty($callDate)) {
    $whereConditions[] = "callDate = '" . mysqli_real_escape_string($conn, $callDate) . "'";
}

if (!empty($callDateStart) && !empty($callDateEnd)) {
    $whereConditions[] = "callDate BETWEEN '" . mysqli_real_escape_string($conn, $callDateStart) . "' AND '" . mysqli_real_escape_string($conn, $callDateEnd) . "'";
}

$whereConditions[] = "is_deleted = 0";

$condition = implode(" AND ", $whereConditions);
$sql = "SELECT * FROM encoded";
if (!empty($condition)) {
    $sql .= " WHERE $condition";
}
$sql .= " ORDER BY id DESC";

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
        $row['callNature'], $row['accStatus'], $row['actionFollow'], $row['whatTranspired']
    ]);
}

fclose($output);
exit;
