<?php
// Sort and keep only the last 5 dates
sort($dateRange);



$headerRow1 = "<thead class='bg-white text-white'><tr>
<th scope='col' rowspan='2'>Business Unit</th>
<th scope='col' rowspan='2'>Account Executive</th>";
$headerRow2 = "<tr>";

$sets = [];

switch ($scopeSelect) {
    case "weekly":
        // Generate headers for weekly scope
        $thursdaysArray = [];
        foreach ($dateRange as $date) {
            if (date('N', strtotime($date)) == 4) {
                $thursdaysArray[] = $date;
            }
        }
        $formatted_date2 = date('F j', strtotime(end($dateRange)));
        $headerRow1 .= "<th scope='col' colspan='" . (count($thursdaysArray) + 1) . "'>Weeks</th><th scope='col' colspan='2'>As of {$formatted_date2}</th>";

        foreach ($thursdaysArray as $index => $date) {
            $start = new DateTime($date);
            $end = clone $start;
            $end->modify('+6 day');
            $headerRow2 .= "<th class='days' style='background-color: #92cddc' scope='col'>Week " . ($index + 1) . "<br>" . $start->format('F-d') . " - " . $end->format('F-d') . "</th>";
            $graphDate = $start->format('F d, Y') . " - " . $end->format('F d, Y');
            $sets[] = $graphDate;
        }
        break;

    case "monthly":
        // Generate headers for monthly scope

        // Step 1: Get all unique months within the date range
        $months = [];
        foreach ($dateRange as $date) {
            $monthLabel = date('F Y', strtotime($date));
            if (!in_array($monthLabel, $months)) {
                $months[] = $monthLabel;
            }
        }

        // Step 2: Count how many months we actually have (e.g., current to-date)
        $currentMonth = count($months); // or set manually if needed

        // Step 3: Format the "As of" date
        $formatted_date = date('F j', strtotime(end($dateRange)));

        // Step 4: Generate header rows
        $headerRow1 .= "<th scope='col' colspan='" . ($currentMonth + 1) . "'>Months</th><th scope='col' colspan='2'>As of {$formatted_date}</th>";

        foreach ($months as $month) {
            $headerRow2 .= "<th class='days' style='background-color: #92cddc' scope='col'>{$month}</th>";
            $sets[] = $month;
        }
        break;


    case "yearly":
        // Step 1: Get all unique years within the date range
        $years = [];
        foreach ($dateRange as $date) {
            $yearLabel = date('Y', strtotime($date));
            if (!in_array($yearLabel, $years)) {
                $years[] = $yearLabel;
            }
        }

        // Step 2: Count how many years we have
        $currentYearCount = count($years);

        // Step 3: Format the "As of" date
        $formatted_date = date('F j', strtotime(end($dateRange)));

        // Step 4: Generate header rows
        $headerRow1 .= "<th scope='col' colspan='" . ($currentYearCount + 1) . "'>Years</th><th scope='col' colspan='2'>As of {$formatted_date}</th>";

        foreach ($years as $year) {
            $headerRow2 .= "<th class='days' style='background-color: #92cddc' scope='col'>{$year}</th>";
            $sets[] = $year;
        }
        break;


    default:
        // Generate headers for daily scope
        $formatted_date1 = date('F j', strtotime(reset($dateRange)));
        $formatted_date2 = date('F j', strtotime(end($dateRange)));
        $headerRow1 .= "<th scope='col' colspan='" . (count($dateRange) + 1) . "'>{$formatted_date1} to {$formatted_date2}</th><th scope='col' colspan='2'>As of {$formatted_date2}</th>";

        foreach ($dateRange as $date) {
            $graphDate = (new DateTime($date))->format('F j, Y');
            $formattedDate = (new DateTime($date))->format('d');
            $headerRow2 .= "<th class='days' style='background-color: #92cddc' scope='col'>{$formattedDate}</th>";
            $sets[] = $graphDate;
        }
        break;
}

$headerRow2 .= "<th class='total' style='background-color: #938953' scope='col'>Total</th><th scope='col'>Target <br>Calls</th><th scope='col'>% <br>Achievement</th>";


// Fetch the headers for call nature
$callNatureHeaders = [];

foreach ($allCalls as $date => $execs) {
    foreach ($execs as $exec => $natures) {
        foreach ($natures as $nature => $count) {
            if ($nature !== 'Total' && !in_array($nature, $callNatureHeaders)) {
                $callNatureHeaders[] = $nature;
            }
        }
    }
}

// Optional: Sort them alphabetically
sort($callNatureHeaders);
$natureColspan = count($callNatureHeaders) + 2; // +2 for CV/Target and CV/Total
$headerRow1 .= "<th scope='col' colspan='". $natureColspan . "'>Nature of Call</th></tr>";
$headerRow2 .= "";
foreach ($callNatureHeaders as $nature) {
    if ($nature === 'N/A') {
        $formattedNature = htmlspecialchars($nature);
    } else {
        $formattedNature = preg_replace('/[\/ ]/', '<br>', htmlspecialchars($nature));
    }
    $headerRow2 .= "<th scope='col'>" . $formattedNature . "</th>";
}


$headerRow2 .= "<th class='total' style='background-color: #938953' scope='col'>CV/Target</th><th class='total' style='background-color: #938953' scope='col'>CV/Total</th></tr></thead><tbody>";



echo $headerRow1 . $headerRow2;
?>