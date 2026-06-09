<?php
// Function to fetch all holidays for all dates and branches
function fetchAllHolidays($conn) {
    $query = "SELECT date, branch FROM holidays WHERE is_deleted = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $holidays = [];
    while ($row = $result->fetch_assoc()) {
        $holidays[$row['date']][$row['branch']] = true;
    }

    return $holidays;
}


// Function to fetch all events for users and dates
function fetchAllEvents($conn) {
    $query = "SELECT date, employee_name, type, duration FROM event WHERE is_deleted = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[$row['date']][$row['employee_name']] = [
            'type' => $row['type'],
            'duration' => $row['duration']
        ];
    }

    return $events;
}


// Function to fetch all leave durations for users and dates
function fetchAllLeaves($conn) {
    $query = "SELECT leave_date, employee_name, leave_duration FROM leave_status WHERE is_deleted = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $leaves = [];
    while ($row = $result->fetch_assoc()) {
        $leaves[$row['leave_date']][$row['employee_name']] = $row['leave_duration'];
    }

    return $leaves;
}



// Function to fetch all call counts for users and dates
function fetchAllCallCounts($conn) {
    $query = "SELECT callDate, accExec, CallNature, COUNT(*) as call_count FROM encoded WHERE is_deleted = 0 GROUP BY callDate, accExec, callNature ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $calls = [];
    while ($row = $result->fetch_assoc()) {
        $date = $row['callDate'];
        $exec = $row['accExec'];
        $nature = $row['CallNature'];
        $count = $row['call_count'];
        $calls[$date][$exec][$nature] = $count;

        if (!isset($calls[$date][$exec]['Total'])) {
            $calls[$date][$exec]['Total'] = 0;
        }
        $calls[$date][$exec]['Total'] += $count;
    }

    return $calls;
}



?>