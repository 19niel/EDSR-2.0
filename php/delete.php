<?php
include ('db_conn.php');

if (isset($_GET['deleteUserId'])) {
  $id = intval($_GET['deleteUserId']);
  $sql = "UPDATE users SET is_deleted = 1 WHERE id = $id";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    echo '<script>
            alert("User deleted.");
            window.location.href = "/e-dsr/pages/user.php";
          </script>';
  } else {
    echo 'MySQL Error: ' . mysqli_error($conn);
  }
  exit();
}



if (isset($_GET['deleteAccountId'])) {
  $id = $_GET['deleteAccountId'];
  $sql = "UPDATE encoded SET is_deleted = 1 WHERE id = $id";
  $result = mysqli_query($conn, $sql);
  echo '<script>
                alert("Account Deleted.");
                window.location.href = "/e-dsr/pages/search.php";
              </script>';
  exit();
}

if (isset($_GET['category_id'])) {
  $id = $_GET['category_id'];
  $sql = "UPDATE categories SET is_deleted = 1 WHERE id = $id";
  $result = mysqli_query($conn, $sql);
  echo '<script>
                alert("Category Deleted.");
                window.location.href = "/e-dsr/pages/customize.php";
              </script>';
  exit();
}


?>