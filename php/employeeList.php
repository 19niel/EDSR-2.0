<?php
include('db_conn.php');
include('../php/autoRedirect.php');

$employee_list = "SELECT * FROM users WHERE is_deleted = 0";
$employee_list_result = mysqli_query($conn, $employee_list);

$user_list = "SELECT name FROM users WHERE category = 'user' AND is_deleted = 0";
$user_list_result = mysqli_query($conn, $user_list);

// Fetch results into an array
$user_list_array = [];
while ($row = mysqli_fetch_assoc($user_list_result)) {
    $user_list_array[] = $row['name']; // Store only the 'name' field
}


$branch = "SELECT DISTINCT branch FROM users WHERE is_deleted = 0";
$branchResult = mysqli_query($conn, $branch);

$departmentBusinessUnits = [
    'OP Sales - PP' => ['PP SALES'],
    'OP Sales - MFP/RISO' => ['OP MFP(NORTH)', 'OP MFP(SOUTH)', 'OP RISO', 'ENTERPRISE'],
    'OP Consumables' => ['OP CONSUMABLES SALES'],
    'Rental' => ['RENTAL SALES - MAKATI/BGC', 'RENTAL SALES - SOUTH MANILA', 'RENTAL SALES - QC/ORTIGAS'],
    'Furniture' => ['FURNITURE']
];

$unit_list = [
    'PP SALES',
    'OP MFP(NORTH)',
    'OP MFP(SOUTH)',
    'OP RISO',
    'ENTERPRISE',
    'OP CONSUMABLES SALES',
    'FURNITURE',
    'RENTAL SALES - MAKATI/BGC',
    'RENTAL SALES - SOUTH MANILA',
    'RENTAL SALES - QC/ORTIGAS',
    'BRANCH - LA UNION',
    'BRANCH - ANGELES',
    'BRANCH - CABANATUAN',
    'BRANCH - BACOLOD',
    'BRANCH - CEBU',
    'BRANCH - ILO-ILO',
    'BRANCH - CDO',
    'BRANCH - DUMAGUETE',
    'BRANCH - GENSAN',
    'FOOD AND BEVERAGES'
];


?>