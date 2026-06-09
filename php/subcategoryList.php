<?php
include ('db_conn.php');
include ('autoRedirect.php');

$sql = "SELECT * FROM subcategories WHERE is_deleted = 0";
$subcategoryResult = mysqli_query($conn, $sql);


if (isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']); // Ensure category_id is an integer

    // Fetch subcategories dynamically
    $query = "SELECT * FROM subcategories WHERE category_id = ? AND is_deleted = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate options for the subcategory dropdown
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['subcategory_name'] . "</option>";
        }
    } else {
        // Return nothing if no subcategories are found
        echo "";
    }

    $stmt->close();
    $conn->close();
}

if (isset($_POST['account_id'])) {
    $account_id = intval($_POST['account_id']); // Ensure account_id is an integer

    // Fetch subcategories dynamically for the selected Source of Account
    $query = "SELECT * FROM subcategories WHERE category_id = ? AND is_deleted = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate options for the subcategory dropdown
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['subcategory_name'] . "</option>";
        }
    } else {
        // Return nothing if no subcategories are found
        echo "";
    }

    $stmt->close();
    $conn->close();
}

if (isset($_POST['industry_id'])) {
    $industry_id = intval($_POST['industry_id']); // Ensure industry_id is an integer

    // Fetch subcategories dynamically for the selected Industry
    $query = "SELECT * FROM subcategories WHERE category_id = ? AND is_deleted = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $industry_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate options for the subcategory dropdown
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['subcategory_name'] . "</option>";
        }
    } else {
        // Return nothing if no subcategories are found
        echo "";
    }

    $stmt->close();
    $conn->close();
}

if (isset($_POST['status_id'])) {
    $status_id = intval($_POST['status_id']); // Ensure industry_id is an integer

    // Fetch subcategories dynamically for the selected Industry
    $query = "SELECT * FROM subcategories WHERE category_id = ? AND is_deleted = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $status_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate options for the subcategory dropdown
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['subcategory_name'] . "</option>";
        }
    } else {
        // Return nothing if no subcategories are found
        echo "";
    }

    $stmt->close();
    $conn->close();
}


?>