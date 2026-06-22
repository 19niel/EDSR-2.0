<?php
include_once('db_conn.php');

if (isset($_POST['login'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE user_id = '$username' AND is_deleted = 0";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $qResult = mysqli_fetch_assoc($result);

        if (password_verify($password, $qResult['password'])) {
            $id = $qResult['id'];
            $name = $qResult['name'];
            $category = $qResult['category'];
            $status = $qResult['stat'];

            if ($status == "New") {
                header("Location: ../firstTimeLogin.php?username=$username");
                exit();
            }

            if ($status === "online") {
                $sqlLogout = "UPDATE users SET stat = 'offline' WHERE id = '$id'";
                mysqli_query($conn, $sqlLogout);
            }

            $sqlLogin = "UPDATE users SET stat = 'online', log_at = CURRENT_TIMESTAMP WHERE id = '$id'";
            mysqli_query($conn, $sqlLogin);

            $cookieName = "e-dsr-user";
            $cookieValue = $username;
            $expirationTime = time() + 86400;
            $cookiePath = "/"; 
            setcookie($cookieName, $cookieValue, $expirationTime, $cookiePath);

            include_once('graphData.php');

            // 🎯 DYNAMIC REDIRECT
            header("Location: " . BASE_URL . "pages/welcome_page.php");
            exit();
        } else {
            echo '<script>
                    window.location.href = "' . BASE_URL . 'index.php";
                    alert("Login failed. Invalid username or password!!");
                  </script>';
        }
    } else {
        echo '<script>
                window.location.href = "' . BASE_URL . 'index.php";
                alert("Login failed. Invalid username or password!!");
              </script>';
    }
}


// if (!defined('BASE_URL')) {
//     define('BASE_URL', 'http://192.168.3.12/e-dsr/');
// }

// First-time login handler
if (isset($_POST['newLogin'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = '$hashedPassword', stat = 'online', log_at = CURRENT_TIMESTAMP WHERE user_id = '$username'";
    $result2 = mysqli_query($conn, $sql);

    $cookieName = "e-dsr-user";
    $cookieValue = $username;
    $expirationTime = time() + 86400;
    $cookiePath = "/"; 
    setcookie($cookieName, $cookieValue, $expirationTime, $cookiePath);

    include_once('graphData.php');

    // 🎯 DYNAMIC REDIRECT
    header("Location: " . BASE_URL . "pages/bo_dashboard.php");
    exit();
}
?>