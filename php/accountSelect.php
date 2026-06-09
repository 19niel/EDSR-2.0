<?php
include('db_conn.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM encoded WHERE id = '$id' AND is_deleted = 0";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            header('Content-Type: application/json');
            echo json_encode($row);
        } else {
            echo json_encode((object) []);
        }
    } else {
        // Log or echo the SQL error
        echo "SQL Error: " . mysqli_error($conn);
    }
} else {
    echo json_encode((object) []);
}

mysqli_close($conn);
?>