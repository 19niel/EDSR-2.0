<?php
include_once('db_conn.php');

if (isset($_GET['logoutid'])) {
    $username = $_GET['logoutid'];
    $cookieName = "e-dsr-user";
    $cookiePath = "/"; // Make sure the path matches the path used when setting the cookie

    $sql = "UPDATE users SET stat = 'offline', log_at = CURRENT_TIMESTAMP WHERE name = '$username';";
    $result = mysqli_query($conn, $sql);
    setcookie($cookieName, "", time() - 1, $cookiePath);
    header("location: ../index.php");
}
session_start();
session_destroy();
?>