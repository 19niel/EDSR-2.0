<?php
include('db_conn.php');
include('autoRedirect.php');

$sql = "SELECT * FROM users WHERE name != '$name' AND is_deleted = 0";
$userList = mysqli_query($conn, $sql);

$sql = "SELECT * FROM users WHERE is_deleted = 0";
$userResult = mysqli_query($conn, $sql);

$userArray = array();
$userArrayManager = array(); // Initialize an empty array

// Fetch each row from the result set as an associative array
while ($row = mysqli_fetch_assoc($userResult)) {
    $userArray[] = $row; // Append the row to the array
}

$sqlManager = "SELECT name FROM users WHERE dept = '$dept' AND is_deleted = 0";
$userResultManager = mysqli_query($conn, $sqlManager);

$userArrayManager = array(); // Initialize an empty array

// Fetch each row from the result set as an associative array
while ($rowManager = mysqli_fetch_assoc($userResultManager)) {
    $userArrayManager[] = $rowManager; // Append the row to the array
}
$userArrayManagerJson = json_encode($userArrayManager);
$userArrayAdminJson = json_encode($userArray);

$userCount = mysqli_num_rows($userResult);

?>