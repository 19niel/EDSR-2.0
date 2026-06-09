<?php
include ('db_conn.php');

if (isset($_POST['add_category_button'])) {
    $field = $_POST['field'];
    $category = $_POST['category'];

    $insert_category_query = "INSERT INTO categories (field, category_name) VALUES ('$field','$category')";
    $insert_category_result = mysqli_query($conn, $insert_category_query);

    if ($insert_category_result) {
        echo '<script>
                window.location.href = "/e-dsr/pages/customize.php";
                alert("Added a Category.")
            </script>';
    } else {
        echo '<script>
                window.location.href = "/e-dsr/pages/customize.php";
                alert("Added a Category.")
            </script>';
    }
}
?>