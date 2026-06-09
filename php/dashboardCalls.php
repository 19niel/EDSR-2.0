<?php
// callTypeData.php

// Include necessary files
include('db_conn.php');
include('autoRedirect.php');
include('dates.php');

// Prepare an array with default counts
$callTypeCounts = array(
    "courtesyVisit"    => 0,
    "messageCall"      => 0,
    "virtualMeeting"   => 0,
    "scheduledMeeting" => 0
);

// Construct WHERE conditions to filter by today's date and user category
$whereConditions = ["DATE(callDate) BETWEEN CURDATE() - INTERVAL 5 DAY AND CURDATE()"]; // Ensures we fetch only today's data

if ($category == 'Manager') {
    $whereConditions[] = "dept LIKE '%$dept%'";
    if (!empty($accountExecutive)) {
        $whereConditions[] = "accExec LIKE '%$accountExecutive%'";
    }
}

if ($category == 'Admin' || $category == 'VP') {
    if (!empty($accountExecutive)) {
        $whereConditions[] = "accExec LIKE '%$accountExecutive%'";
    }
}

if ($category == 'User') {
    $whereConditions[] = "accExec LIKE '%$name%'";
}


// Combine conditions into a single WHERE clause
$condition = implode(" AND ", $whereConditions);

// Write a query that counts records grouped by callNature only for today's callDate.
$sql = "SELECT LOWER(TRIM(callNature)) AS callNature, COUNT(*) AS count 
        FROM encoded 
        WHERE $condition
        GROUP BY callNature";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Convert callNature to lowercase
        $type  = strtolower(trim($row['callNature']));
        $count = (int)$row['count'];

        // Debugging Log
        error_log("Type: $type | Count: $count");

        // Map DB callType values to our keys
        if ($type === "courtesy visit") {
            $callTypeCounts["courtesyVisit"] = $count;
        } elseif ($type === "message/call") {
            $callTypeCounts["messageCall"] = $count;
        } elseif ($type === "virtual meeting") {
            $callTypeCounts["virtualMeeting"] = $count;
        } elseif ($type === "face to face meeting") {
            $callTypeCounts["scheduledMeeting"] = $count;
        } elseif ($type === "email") {
            $callTypeCounts["email"] = $count;
        }
    }
}

// Return the counts as JSON.
header('Content-Type: application/json');
echo json_encode($callTypeCounts);

// Optionally close the database connection
// mysqli_close($conn);
?>
