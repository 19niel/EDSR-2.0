<?php
include('db_conn.php');

$encodedId = $_GET['id'] ?? NULL;

if (!$encodedId) {
    echo json_encode(['success' => false, 'message' => 'Missing target entry ID']);
    exit();
}

// 1. Fetch the master encoded record first to avoid ANY column name collisions
$masterQuery = "SELECT * FROM encoded WHERE id = ? AND is_deleted = 0";
$stmt = $conn->prepare($masterQuery);
$stmt->bind_param('i', $encodedId);
$stmt->execute();
$masterResult = $stmt->get_result();
$data = $masterResult->fetch_assoc();
$stmt->close();

// 2. If the record exists, fetch its child product loop profiles explicitly
if ($data) {
    $products = [];
    
    $productQuery = "SELECT * FROM product_details WHERE encodedID = ?";
    $stmt2 = $conn->prepare($productQuery);
    $stmt2->bind_param('i', $encodedId);
    $stmt2->execute();
    $productResult = $stmt2->get_result();
    
    while ($pRow = $productResult->fetch_assoc()) {
        $products[] = $pRow;
    }
    $stmt2->close();

    // Nest the arrays cleanly 
    $data['products'] = $products;

    // Output clean JSON - contact details will remain perfectly intact
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => 'No matching records found']);
}

$conn->close();
?>