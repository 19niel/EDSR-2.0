<?php
include('db_conn.php');

if (isset($_COOKIE['e-dsr-user'])) {
    $coockieUser = $_COOKIE['e-dsr-user'];
    $sql1 = "SELECT * FROM users WHERE user_id = '$coockieUser' AND is_deleted = 0";
    $result1 = mysqli_query($conn, $sql1);

    while ($qResult = mysqli_fetch_array($result1)) {
        $id = $qResult['id'];
        $name = $qResult['name'];
        $username = $qResult['user_id'];
        $password = $qResult['password'];
        $category = $qResult['category'];
        $stat = $qResult['stat'];
        $dept = $qResult['dept'];
        $is_download_restricted = $qResult['is_download_restricted'];
    }
} else {
    echo '<script>
            window.location.href = "../index.php";
            alert("Please log in to access the welcome page!");
          </script>';
}

?>