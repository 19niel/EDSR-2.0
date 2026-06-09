<?php
include('db_conn.php');
if (isset($_POST['editUser'])) {
    $editId = $_POST['editId'];
    $editName = $_POST['editName'];
    $editUsername = $_POST['editUsername'];
    $editPassword = $_POST['editPassword'];
    $editDepartment = $_POST['editDepartment'];
    $editCategory = $_POST['editCategory'];
    $editSubDepartment = $_POST['editSubDepartment'];
    $editRole = $_POST['editRole'];
    $editPasswordChange = $_POST['editPasswordChange'];
    $editBranch = $_POST['editBranch'];

    // Retrieve current password from the database
    $sql = "SELECT password FROM users WHERE id = $editId AND is_deleted = 0";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $currentPassword = $row['password'];

    // Check if the new password is empty or matches the current password
    if (!empty($editPassword) && !password_verify($editPassword, $currentPassword)) {
        // New password is provided and different from the current password
        $hashedPassword = password_hash($editPassword, PASSWORD_DEFAULT);
        $passwordUpdate = ", password = '$hashedPassword', stat = 'New'";
    } else {
        // New password is empty or same as the current password
        $passwordUpdate = "";
    }

    // Update the user information
    $sql = "UPDATE users SET name = '$editName', user_id = '$editUsername', dept = '$editDepartment', 
            category = '$editCategory', handled = '$editSubDepartment', authority = '$editRole', pass_change = '$editPasswordChange', branch = '$editBranch' $passwordUpdate
            WHERE id = $editId";
    $result = mysqli_query($conn, $sql);
    echo '<script>
            window.location.href = "/e-dsr/pages/user.php";
            alert("User Updated.")
        </script>';
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $stmt = $conn->prepare("SELECT name, user_id, password, dept, category, pass_change, branch, authority, handled FROM users WHERE id = ? AND is_deleted = 0");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($name, $user_id, $password, $dept, $category, $pass_change, $branch, $authority, $handled);
    if ($stmt->fetch()) {
        // Include userId in the array
        $userArray = array(
            'id' => $userId,
            'name' => $name,
            'user_id' => $user_id,
            'dept' => $dept,
            'category' => $category,
            'pass_change' => $pass_change,
            'branch' => $branch,
            'authority' => $authority,
            'handled' => $handled
        );
        echo json_encode($userArray);
    } else {
        echo json_encode(array('error' => 'User not found'));
    }
    $stmt->close();
} else {
    echo json_encode(array('error' => 'No ID provided'));
}
$conn->close();
?>