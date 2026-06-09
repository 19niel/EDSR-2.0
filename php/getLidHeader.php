<?php
// Ensure a database connection is active before this script runs
// (Since db_conn.php is typically included in your main routing, we check or adapt)
include_once('db_conn.php'); 

$displayLID = '';

if (isset($_GET['id'])) {
    $encodeId = intval($_GET['id']);

    // Query to pull the precise LID reference string
    $lidQuery = "SELECT LID FROM encoded WHERE id = ? LIMIT 1";
    $lidStmt = mysqli_prepare($conn, $lidQuery);

    if ($lidStmt) {
        mysqli_stmt_bind_param($lidStmt, "i", $encodeId);
        mysqli_stmt_execute($lidStmt);
        $lidResult = mysqli_stmt_get_result($lidStmt);

        if ($lidRow = mysqli_fetch_assoc($lidResult)) {
            // Format it inside parentheses if it exists
            $displayLID = !empty($lidRow['LID']) ? '(' . htmlspecialchars($lidRow['LID']) . ')' : '';
        }
        mysqli_stmt_close($lidStmt);
    }
}

// Output the value right where this file gets included
echo $displayLID;
?>