<?php
include('db_conn.php');
include('userList.php');

if (isset($_POST['addUser'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $department = $_POST['department'];
    $category = $_POST['category'];
    $logAt = "CURRENT_TIMESTAMP";
    $subDepartment = $_POST['subDepartment'];
    $role = $_POST['role'];
    $logAt = "CURRENT_TIMESTAMP";
    $passwordChange = $_POST['passwordChange'];
    $status = "New";
    $branch = $_POST['branch'];

    $sql = "INSERT INTO users (name, user_id, 
            password, dept, category, handled, authority, log_at, pass_change, 
            stat, branch)
            VALUES ('$name', '$username', '$hashedPassword', 
            '$department', '$category', '$subDepartment', '$role',  '$logAt', '$passwordChange', 
            '$status', '$branch')";
    $addUserResult = mysqli_query($conn, $sql);

    if ($addUserResult) {
        echo '<script>
                alert("User Added.");
                window.location.href = "/e-dsr/pages/user.php";
              </script>';
        exit();
    } else {
        // Display an error message or log the error
        echo "Error: " . mysqli_error($conn);
    }
}
?>