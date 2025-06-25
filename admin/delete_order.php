<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

include 'db_connect.php';

if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete order items first
        $delete_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        if (!$delete_items) {
            throw new Exception("Failed to prepare delete items statement");
        }
        $delete_items->bind_param("i", $order_id);
        if (!$delete_items->execute()) {
            throw new Exception("Failed to delete order items");
        }
        
        // Then delete the order
        $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
        if (!$delete_order) {
            throw new Exception("Failed to prepare delete order statement");
        }
        $delete_order->bind_param("i", $order_id);
        if (!$delete_order->execute()) {
            throw new Exception("Failed to delete order");
        }
        
        // Commit transaction
        $conn->commit();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false, 
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Order ID not provided'
    ]);
}
?>