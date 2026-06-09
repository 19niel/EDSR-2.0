<?php
// Function to fetch user lists based on authority type and unit handled
function fetchUserList($conn, $unit, $authorityType)
{
    $placeholders = rtrim(str_repeat('?,', count($authorityType)), ',');
    $query = "SELECT * FROM users WHERE authority IN ($placeholders) AND handled = ? AND is_deleted = 0";
    $stmt = $conn->prepare($query);
    $params = array_merge($authorityType, [$unit]);
    $types = str_repeat('s', count($authorityType)) . 's';
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result();
}
?>