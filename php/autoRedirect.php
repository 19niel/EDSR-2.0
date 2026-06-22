<?php
// Ensure session is active so variables can be read across files
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db_conn.php');

if (isset($_COOKIE['e-dsr-user'])) {
    // Escaping the cookie data to protect against SQL Injection vulnerabilities
    $coockieUser = mysqli_real_escape_string($conn, $_COOKIE['e-dsr-user']);
    $sql1 = "SELECT * FROM users WHERE user_id = '$coockieUser' AND is_deleted = 0";
    $result1 = mysqli_query($conn, $sql1);

    if ($result1 && mysqli_num_rows($result1) > 0) {
        while ($qResult = mysqli_fetch_array($result1)) {
            $id = $qResult['id'];
            $name = $qResult['name'];
            $username = $qResult['user_id'];
            $password = $qResult['password'];
            $category = $qResult['category'];
            $stat = $qResult['stat'];
            $dept = $qResult['dept'];
            $is_download_restricted = $qResult['is_download_restricted'];
            
            // 🔒 Save the role inside the session so layouts can access it
            $_SESSION['category'] = $category;
        }
    } else {
        // Cookie exists but user was not found or is deleted in the DB
        header("Location: ../index.php?error=invalid_session");
        exit();
    }
} else {
    // No cookie found. Redirect cleanly using PHP headers instead of breaking out to JS
    header("Location: ../index.php?error=auth_required");
    exit();
}
?>