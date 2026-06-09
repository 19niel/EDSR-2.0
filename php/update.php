<?php
include 'db_conn.php';

if (isset($_POST['id'], $_POST['status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // SQL update query
    $sql = "UPDATE users SET stat = '$status' WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid request: Missing required parameters.";
}
?>
