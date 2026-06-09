<?php
include('db_conn.php');
include('autoRedirect.php');

// Construct WHERE clause based on user role
$whereConditions = ["reminderDate IS NOT NULL"];

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



$condition = 'WHERE ' . implode(" AND ", $whereConditions);

// Fetch reminders from the database with accStatus name
$sqlReminder = "
    SELECT e.reminderDate, e.accName, c.category_name 
    FROM encoded e
    LEFT JOIN categories c ON e.accStatus = c.id
    $condition
    ORDER BY e.reminderDate
";

$reminderResult = mysqli_query($conn, $sqlReminder);

$events = [];
while ($row = mysqli_fetch_assoc($reminderResult)) {
    // Group events by reminderDate
    $date = $row['reminderDate'];
    $eventTitle = $row['accName']; // Remove category_name from the title
    $accountStatus = $row['category_name']; // category_name is the account status

    // If this date isn't already in the events array, add it
    if (!isset($events[$date])) {
        $events[$date] = [];
    }

    // Add the event with status to the array for this date
    $events[$date][] = [
        'title' => $eventTitle,
        'status' => $accountStatus, // Add account status here
    ];
}


// Return the grouped events as a JSON response
echo json_encode($events);
?>
