<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

include 'db_connect.php';

if (isset($_POST['message_id'])) {
    $message_id = intval($_POST['message_id']);
    
    $sql = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting message']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Message ID not provided']);
}

$conn->close();
?>