<?php
header('Content-Type: application/json');
include('db_conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['target_goal'])) {
    $newTarget = floatval($_POST['target_goal']);

    if ($newTarget <= 0) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid target amount greater than 0.']);
        exit;
    }

    // Securely update or insert configuration tracking elements using prepared statements
    $stmt = mysqli_prepare($conn, "INSERT INTO dashboard_settings (setting_key, setting_value) 
                                   VALUES ('kpi_sales_target', ?) 
                                   ON DUPLICATE KEY UPDATE setting_value = ?");
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $newTarget, $newTarget);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'KPI Sales Target configuration successfully updated!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to execute query updates: ' . mysqli_error($conn)]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Statement formulation error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request delivery architecture routing.']);
}

mysqli_close($conn);
?>