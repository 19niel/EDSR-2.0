<?php 
include('db_conn.php');

$encodeAccountExec = $_GET['accountExec'] ?? NULL; // Handle NULL
$encodeAccountName = $_GET['accountName'] ?? NULL; // Handle NULL

$query = "SELECT * FROM encoded WHERE accExec = ? AND accName = ? AND is_deleted = 0 ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $encodeAccountExec, $encodeAccountName);
$stmt->execute();
$result = $stmt->get_result();

$data = null;

while ($row = $result->fetch_assoc()) {
    if (!$data) {
        // Extract only encoded data once
        $data = $row;
    }
}

if ($data) {
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => 'No data found']);
}
$stmt->close();
$conn->close();
?>