<?php
require_once './performance_functions/user_functions.php';
require_once './performance_functions/call_functions.php';
require_once './performance_functions/utility_functions.php';

// Ensure dates start on a Thursday and skip weekends
function getLastThursday($date) {
    $dayOfWeek = date('N', strtotime($date)); // 1 (Monday) to 7 (Sunday)
    $daysToSubtract = ($dayOfWeek >= 4) ? ($dayOfWeek - 4) : ($dayOfWeek + 3);
    return date('Y-m-d', strtotime("-$daysToSubtract days", strtotime($date)));
}

// Get the full workweek (Thursday to Wednesday)
function getFullWorkWeek($start_date) {
    $all_dates = [];
    $currentDate = $start_date;
    for ($i = 0; $i < 7; $i++) {
        $dayOfWeek = date('N', strtotime($currentDate));
        if ($dayOfWeek != 6 && $dayOfWeek != 7) { // Skip Saturday and Sunday
            $all_dates[] = $currentDate;
        }
        $currentDate = date('Y-m-d', strtotime('+1 day', strtotime($currentDate)));
    }
    return $all_dates;
}

// Get the start and end dates from the form submission
$callDateStart = isset($_POST['callDateStart']) ? $_POST['callDateStart'] : null;
$callDateEnd = isset($_POST['callDateEnd']) ? $_POST['callDateEnd'] : null;
$scopeSelect = isset($_POST['scope']) ? $_POST['scope'] : 'daily';


if ($callDateStart && $callDateEnd) {
    $startDate = date('Y-m-d', strtotime($callDateStart));
    $endDate = date('Y-m-d', strtotime($callDateEnd));

    switch ($scopeSelect) {
        case 'weekly':
            // Find the last Thursday before the start date
            $startDate = getLastThursday($startDate);
            // Generate full workweeks until the end date
            $dateRange = [];
            while (strtotime($startDate) <= strtotime($endDate)) {
                $dateRange = array_merge($dateRange, getFullWorkWeek($startDate));
                $startDate = date('Y-m-d', strtotime('+1 week', strtotime($startDate)));
            }
            break;

        case 'monthly':
            // Align start date to the first day of the month
            $startDate = date('Y-m-01', strtotime($startDate));
            $dateRange = [];

            // Loop through each month until end date
            while (strtotime($startDate) <= strtotime($endDate)) {
                // Add the full month to dateRange (as list of dates)
                $endOfMonth = date('Y-m-t', strtotime($startDate));
                $current = $startDate;
                while (strtotime($current) <= strtotime($endOfMonth) && strtotime($current) <= strtotime($endDate)) {
                    $dateRange[] = $current;
                    $current = date('Y-m-d', strtotime('+1 day', strtotime($current)));
                }

                // Move to the next month
                $startDate = date('Y-m-d', strtotime('+1 month', strtotime($startDate)));
            }
            break;

        case 'yearly':
            // Align start date to January 1st of the starting year
            $startDate = date('Y-01-01', strtotime($startDate));
            $dateRange = [];

            // Loop through each year until the end date
            while (strtotime($startDate) <= strtotime($endDate)) {
                $endOfYear = date('Y-12-31', strtotime($startDate));
                $current = $startDate;

                // Add each day in the year to dateRange, but do not go past $endDate
                while (strtotime($current) <= strtotime($endOfYear) && strtotime($current) <= strtotime($endDate)) {
                    $dateRange[] = $current;
                    $current = date('Y-m-d', strtotime('+1 day', strtotime($current)));
                }

                // Move to January 1st of the next year
                $startDate = date('Y-01-01', strtotime('+1 year', strtotime($startDate)));
            }
            break;


        default: // Daily (default behavior)
            $dateRange = [];
            $currentDate = $startDate;
            while (strtotime($currentDate) <= strtotime($endDate)) {
                if (!in_array(date('N', strtotime($currentDate)), [6, 7])) { // Skip weekends
                    $dateRange[] = $currentDate;
                }
                $currentDate = date('Y-m-d', strtotime('+1 day', strtotime($currentDate)));
            }
            break;
    }
} else {
    // Default: Use last Thursday and workweek if no dates provided
    $lastThursday = getLastThursday(date('Y-m-d'));
    $dateRange = getFullWorkWeek($lastThursday);
}



$firstDate = $dateRange[0] ?? date('Y-m-d'); // Fallback to today if index 0 is missing
$currentDayNumber = (int) date('j', strtotime($firstDate));
$weekNumber = intdiv($currentDayNumber, 7);
// Insert Table Headers


// Calculate and display weekly and monthly reports for each unit

if ($department == 'all') {
    if ($businessUnit == 'all') {
        $unit_list;
    } else {
        $unit_list = [$businessUnit];
    }
} else {
    $unit_list = $departmentBusinessUnits[$department];
}

// Preload all necessary data before loops
$allHolidays = fetchAllHolidays($conn);
$allEvents = fetchAllEvents($conn);
$allLeaves = fetchAllLeaves($conn);
$allCalls = fetchAllCallCounts($conn);

require_once 'performance_table_header.php';


foreach ($unit_list as $unit) {
    $userList = fetchUserList($conn, $unit, ['Assistant Manager', 'User', 'Account Executive', 'Sales Executive']);
    $userList->data_seek(0); // Reset pointer to the first row
    $rowCount = $userList->num_rows;
    if ($rowCount !== 0) {
        $totalCols = 2 + count($sets) + 3 + count($callNatureHeaders) + 2;
        echo "<tr><td class='names' colspan='". $totalCols ."' style='text-align: start;'>" . htmlspecialchars($unit) . "</td></tr>";

        $execUnit = ($unit === "OP RISO") ? "OP MFP(SOUTH)" : 
                    (($unit === "BRANCH - CEBU" || $unit === "BRANCH - DUMAGUETE" || $unit === "BRANCH - GENSAN") ? "BRANCH - CEBU" : $unit);
        
        $executiveList = fetchUserList($conn, $execUnit, ['General Manager', 'Assistant Manager', 'Senior Manager', 'Manager', 'Team Leader', 'Sales Executive (Supervisor)']);
        $executiveName = $executiveList->fetch_assoc()['name'] ?? '';
        
        echo "<tr><td class='names' rowspan='{$rowCount}' style='text-align: start;'>{$executiveName}</td>";
        
        while ($userRow = $userList->fetch_assoc()) {
            $userName = htmlspecialchars($userRow['name']);
            echo "<td class='names' style='text-align: start;'>{$userName}</td>";

            $labels[] = $userName;
            
            $total = $target = 0;
            $thursdaysArray = [];
            $totalDays = count($dateRange); // Number of days in the date range
            
            switch ($scopeSelect) {
                case 'yearly':
                    foreach ($dateRange as $date) {
                        $year = date('Y', strtotime($date));
                        if (!in_array($year, $thursdaysArray)) {
                            $thursdaysArray[] = $year;
                        }
                        
                    }

                    foreach ($thursdaysArray as $index => $year) {
                        $start = new DateTime($year . "-01-01");
                        $end = clone $start;
                        $end->modify('last day of december this year'); // Year end date                        
                        
                        $yearlyTotal = 0;  // Total calls in the year
                        $yearlyTarget = 0; // Target working hours for the year
                        
                        while (strtotime($start->format('Y-m-d')) <= strtotime($end->format('Y-m-d'))) {
                            
                            $currentDate = $start->format('Y-m-d');
                            $dayOfWeek = date('N', strtotime($currentDate));
                            if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                                $start->modify('+1 day');
                                $totalDays -= 1;
                                continue;
                            }

                            $branch = $userRow['branch'];

                            // Holiday check (Skip counting if it's a holiday)
                            if (isset($allHolidays[$currentDate]["All"]) || isset($allHolidays[$currentDate][$branch])) {
                                $start->modify('+1 day');
                                $totalDays -= 1;
                                continue;
                            }

                            // Event check (Reduce target hours accordingly)
                            $event = $allEvents[$currentDate][$userName] ?? null;
                            if ($event) {
                                $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                                $yearlyTotal += $callCount;
                                $yearlyTarget += (8 - $event['duration']);
                                $totalDays = ($event['duration'] <= 4) ? $totalDays - 0.5 : $totalDays - 1; // Adjust total days based on event duration
                                $start->modify('+1 day');
                                continue;
                            }

                            // Leave check (Skip counting if the user is on leave)
                            $leaveStatus = $allLeaves[$currentDate][$userName] ?? null;
                            if ($leaveStatus !== null) {
                                $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                                $yearlyTotal += $callCount;

                                // Extract leave duration and type safely
                                $leaveRaw = floatval($leaveStatus); // Get leave duration as a float
                                $leaveType = ($leaveRaw == 1.0) ? 'Leave' : (($leaveRaw == 0.5) ? 'Half-day' : 'Unknown');

                                // Convert to working hours: Full day = 8, Half day = 4
                                $leaveDuration = ($leaveRaw == 1.0) ? 8 : (($leaveRaw == 0.5) ? 4 : 0);
                                $yearlyTarget += (8 - $leaveDuration); // Reduce target hours for the leave

                                $totalDays = ($leaveRaw == 1.0) ? $totalDays - 1 : $totalDays - 0.5; // Adjust total days based on leave duration

                                $start->modify('+1 day');
                                continue;
                            }

                            $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                            $yearlyTotal += $callCount;
                            $yearlyTarget += 8; // Regular workday: Add Calls and Target Hours
                            $start->modify('+1 day');
                        }
                        echo "<td class='dayCalls' style='background-color: #92d050'>$yearlyTotal</td>";
                        $total += $yearlyTotal;
                        $target += $yearlyTarget;
                        $result[] = $yearlyTotal;
                    }
                    break;

                case 'monthly':
                    foreach ($dateRange as $date) {
                        $monthYear = date('F Y', strtotime($date));
                        if (!in_array($monthYear, $thursdaysArray)) {
                            $thursdaysArray[] = $monthYear;
                        }
                    }

                    foreach ($thursdaysArray as $index => $monthYear) {
                        $start = new DateTime($monthYear . "-01");
                        $end = clone $start;
                        $end->modify('last day of this month'); // Month end date
                        

                        $monthlyTotal = 0;  // Total calls in the month
                        $monthlyTarget = 0; // Target working hours for the month
                        
                        while (strtotime($start->format('Y-m-d')) <= strtotime($end->format('Y-m-d'))) {
                            
                            $currentDate = $start->format('Y-m-d');
                            $dayOfWeek = date('N', strtotime($currentDate));
                            if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                                $start->modify('+1 day');
                                $totalDays -= 1;
                                continue;
                            }

                            $branch = $userRow['branch'];

                            // Holiday check (Skip counting if it's a holiday)
                            if (isset($allHolidays[$currentDate]["All"]) || isset($allHolidays[$currentDate][$branch])) {
                                $start->modify('+1 day');
                                $totalDays -= 1;
                                continue;
                            }

                            // Event check (Reduce target hours accordingly)
                            $event = $allEvents[$currentDate][$userName] ?? null;
                            if ($event) {
                                $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                                $monthlyTotal += $callCount;
                                $monthlyTarget += (8 - $event['duration']);
                                $totalDays = ($event['duration'] <= 4) ? $totalDays - 0.5 : $totalDays - 1; // Adjust total days based on event duration
                                $start->modify('+1 day');
                                continue;
                            }

                            // Leave check (Skip counting if the user is on leave)
                            $leaveStatus = $allLeaves[$currentDate][$userName] ?? null;
                            if ($leaveStatus !== null) {
                                $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                                $monthlyTotal += $callCount;

                                // Extract leave duration and type safely
                                $leaveRaw = floatval($leaveStatus); // Get leave duration as a float
                                $leaveType = ($leaveRaw == 1.0) ? 'Leave' : (($leaveRaw == 0.5) ? 'Half-day' : 'Unknown');

                                // Convert to working hours: Full day = 8, Half day = 4
                                $leaveDuration = ($leaveRaw == 1.0) ? 8 : (($leaveRaw == 0.5) ? 4 : 0);
                                $monthlyTarget += (8 - $leaveDuration); // Reduce target hours for the leave

                                $totalDays = ($leaveRaw == 1.0) ? $totalDays - 1 : $totalDays - 0.5; // Adjust total days based on leave duration

                                $start->modify('+1 day');
                                continue;
                            }

                            $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                            $monthlyTotal += $callCount;
                            $monthlyTarget += 8; // Regular workday: Add Calls and Target Hours
                            $start->modify('+1 day');
                        }
                        echo "<td class='dayCalls' style='background-color: #92d050'>$monthlyTotal</td>";
                        $total += $monthlyTotal;
                        $target += $monthlyTarget;
                        $result[] = $monthlyTotal;
                    }
                    break;

                
                case 'weekly':
                    foreach ($dateRange as $date) {
                        if (date('N', strtotime($date)) == 4) { // Find all Thursdays
                            $thursdaysArray[] = $date;
                        }
                    }

                    foreach ($thursdaysArray as $index => $date) {
                        $start = new DateTime($date);
                        $end = clone $start;
                        $end->modify('+6 day'); // Week end date
                        
                        $weeklyTotal = 0;  // Total calls in the week
                        $weeklyTarget = 0; // Target working hours for the week
                        
                        while (strtotime($start->format('Y-m-d')) <= strtotime($end->format('Y-m-d'))) {
                            
                            $currentDate = $start->format('Y-m-d');
                            $dayOfWeek = date('N', strtotime($currentDate));
                            if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                                $start->modify('+1 day');
                                continue;
                            }
                            $branch = $userRow['branch'];

                            // Holiday check (Skip counting if it's a holiday)
                            if (isset($allHolidays[$currentDate]["All"]) || isset($allHolidays[$currentDate][$branch])) {
                                $start->modify('+1 day');
                                $totalDays -= 1;
                                continue;
                            }

                            // Event check (Reduce target hours accordingly)
                            $event = $allEvents[$currentDate][$userName] ?? null;
                            if ($event) {
                                $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                                $weeklyTotal += $callCount;
                                $weeklyTarget += (8 - $event['duration']);
                                $totalDays = ($event['duration'] <= 4) ? $totalDays - 0.5 : $totalDays - 1; // Adjust total days based on event duration
                                $start->modify('+1 day');
                                continue;
                            }

                            // Leave check (Skip counting if the user is on leave)
                            $leaveStatus = $allLeaves[$currentDate][$userName] ?? null;
                            if ($leaveStatus !== null) {
                                $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                                $weeklyTotal += $callCount;

                                $leaveRaw = floatval($leaveStatus); // Get leave duration as a float
                                $leaveType = ($leaveRaw == 1.0) ? 'Leave' : (($leaveRaw == 0.5) ? 'Half-day' : 'Unknown');

                                $leaveDuration = ($leaveRaw == 1.0) ? 8 : (($leaveRaw == 0.5) ? 4 : 0);
                                $weeklyTarget += (8 - $leaveDuration); // Reduce target hours for the leave

                                $totalDays = ($leaveRaw == 1.0) ? $totalDays - 1 : $totalDays - 0.5; // Adjust total days based on leave duration

                                $start->modify('+1 day');
                                continue;
                            }

                            // Regular Workday: Add Calls and Target Hours
                            $callCount = $allCalls[$currentDate][$userName]['Total'] ?? 0;
                            $weeklyTotal += $callCount;
                            $weeklyTarget += 8;
                            
                            $start->modify('+1 day');
                        }

                        // Display Weekly Totals
                        
                        echo "<td class='dayCalls' style='background-color: #92d050'>$weeklyTotal</td>";
                        $total += $weeklyTotal;
                        $target += $weeklyTarget;
                        $result[] = $weeklyTotal;
                    }
                    
                    break;

                default:
                    foreach ($dateRange as $date) {
                        $branch = $userRow['branch'];
                        if (isset($allHolidays[$date]["All"]) || isset($allHolidays[$date][$branch])) {
                            echo "<td class='dayCalls' style='background-color: #92d050'>Holiday</td>";
                            $callCount = 0;
                            $result[] = $callCount;
                            $totalDays -= 1; // Decrease total days for holidays
                            continue;
                        }

                        $event = $allEvents[$date][$userName] ?? null;
                        if ($event) {
                            $callCount = $allCalls[$date][$userName]['Total'] ?? 0;
                            $total += $callCount;
                            $target += (8 - $event['duration']);
                            $result[] = $callCount;
                            echo "<td class='dayCalls' style='background-color: #92d050'>{$event['type']} {$event['duration']}/hrs<br>Calls: $callCount</td>";

                            $totalDays = ($event['duration'] <= 4) ? $totalDays - 0.5 : $totalDays - 1; // Adjust total days based on event duration
                            
                            continue;
                        }

                        $leaveStatus = $allLeaves[$date][$userName]?? null;
                        if ($leaveStatus !== null) {
                            $callCount = $allCalls[$date][$userName]['Total'] ?? 0;
                            $total += $callCount;

                            // Extract leave duration and type safely
                            $leaveRaw = floatval($leaveStatus); // Get leave duration as a float
                            $leaveType = ($leaveRaw == 1.0) ? 'Leave' : (($leaveRaw == 0.5) ? 'Half-day' : 'Unknown');

                            // Convert to working hours: Full day = 8, Half day = 4
                            $leaveDuration = ($leaveRaw == 1.0) ? 8 : (($leaveRaw == 0.5) ? 4 : 0);

                            $target += (8 - $leaveDuration);
                            
                            $result[] = $callCount;

                            $totalDays = ($leaveRaw == 1.0) ? $totalDays - 1 : $totalDays - 0.5; // Adjust total days based on leave duration

                            // Corrected echo statement
                            echo "<td class='dayCalls' style='background-color: #92d050'>{$leaveType}<br>Calls: $callCount</td>";
                            continue;
                        }

                        $callCount = $allCalls[$date][$userName]['Total'] ?? 0;
                        $total += $callCount;
                        $target += 8;

                        echo "<td class='dayCalls' style='background-color: #92d050'>$callCount</td>";
                        $result[] = $callCount;
                        
                        
                        continue;


                    }
                    

                    break;
            }

            
            $completionRatio = calculateCompletionRatio($total, $target);
            if (!empty($result) && !empty($labels)) {
                $result2DArray = splitArrayInto2D($result, count($labels));
            }

            echo "<td>{$total}</td><td>{$target}</td><td class='achievement' style='background-color: #8db3e2'>" . number_format($completionRatio, 0) . "%</td>";

            $totalCourtesyVisitCount = 0; // Initialize the variable for courtesy visit count
            

            foreach ($callNatureHeaders as $nature) {
                if ($nature === 'Courtesy Visit') {  // Check if the current nature is "Courtesy Visit"
                    $totalCourtesyVisitCount = 0; // Reset the count for "Courtesy Visit"

                    foreach ($dateRange as $date) {
                        $totalCourtesyVisitCount += $allCalls[$date][$userName][$nature] ?? 0; // Sum the values for "Courtesy Visit"
                    }

                    echo "<td class='natureCalls'>$totalCourtesyVisitCount</td>"; // Output the total count for "Courtesy Visit"
                } else {
                    // For other types of call nature, calculate the total count
                    $totalNatureCount = 0;
                    foreach ($dateRange as $date) {
                        $totalNatureCount += $allCalls[$date][$userName][$nature] ?? 0;
                    }
                    echo "<td class='natureCalls'>$totalNatureCount</td>"; // Output the count for other call natures
                }
            }

            // Calculate the target for Courtesy Visit (2 visits per day)
            $visitTarget = 2 * $totalDays; // 2 visits per day * number of days
            
            $courtesyVisitTotalRatio = calculateCompletionRatio($totalCourtesyVisitCount, $total); // Total ratio
            $courtesyVisitTargetRatio = calculateCompletionRatio($totalCourtesyVisitCount, $visitTarget); // Target ratio

            echo "<td class='achievement' style='background-color: #8db3e2'>" . number_format($courtesyVisitTargetRatio, 0) . "%</td>";
            echo "<td class='achievement' style='background-color: #8db3e2'>" . number_format($courtesyVisitTotalRatio, 0) . "%</td>";

            // Close the table row after displaying the data for this user
            echo "</tr>";

        }
    }
}
?>