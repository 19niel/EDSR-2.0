<?php
include_once('db_conn.php');

if (isset($_POST['login'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    // Hash the password for comparison
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE user_id = '$username' AND is_deleted = 0";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $qResult = mysqli_fetch_assoc($result);

        // Verify the hashed password
        if (password_verify($password, $qResult['password'])) {
            $id = $qResult['id'];
            $name = $qResult['name'];
            $category = $qResult['category'];
            $status = $qResult['stat'];

            if ($status == "New") {
                header("Location: ../firstTimeLogin.php?username=$username");
                exit(); // ✅ Prevent further execution
            }

            // Check if account is already "online"
            if ($status === "online") {
                // Log the previous session out
                $sqlLogout = "UPDATE users SET stat = 'offline' WHERE id = '$id'";
                mysqli_query($conn, $sqlLogout);
            }

            // Log in the user (set status to "online")
            $sqlLogin = "UPDATE users SET stat = 'online', log_at = CURRENT_TIMESTAMP WHERE id = '$id'";
            mysqli_query($conn, $sqlLogin);

            // Set a cookie for the session
            $cookieName = "e-dsr-user";
            $cookieValue = $username;
            $expirationTime = time() + 86400; // 86400 seconds = 1 day
            $cookiePath = "/"; // The cookie is available throughout the entire domain
            setcookie($cookieName, $cookieValue, $expirationTime, $cookiePath);

            include_once('graphData.php');

            // Redirect to the welcome page
            header("Location: /e-dsr/pages/welcome_page.php");
            exit();
        } else {
            echo '<script>
                    window.location.href = "/e-dsr/index.php";
                    alert("Login failed. Invalid username or password!!");
                  </script>';
        }
    } else {
        echo '<script>
                window.location.href = "/e-dsr/index.php";
                alert("Login failed. Invalid username or password!!");
              </script>';
    }
}

// First-time login handler
if (isset($_POST['newLogin'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's password and mark them as "online"
    $sql = "UPDATE users SET password = '$hashedPassword', stat = 'online', log_at = CURRENT_TIMESTAMP WHERE user_id = '$username'";
    $result2 = mysqli_query($conn, $sql);

    // Set a cookie for the session
    $cookieName = "e-dsr-user";
    $cookieValue = $username;
    $expirationTime = time() + 86400; // 86400 seconds = 1 day
    $cookiePath = "/"; // The cookie is available throughout the entire domain
    setcookie($cookieName, $cookieValue, $expirationTime, $cookiePath);

    include_once('graphData.php');

    // Redirect to the welcome page
    header("Location: /e-dsr/pages/welcome_page.php");
    exit();
}
?>