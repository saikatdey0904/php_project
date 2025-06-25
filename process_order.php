<?php
session_start();
include 'admin/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $notes = trim($_POST['notes']);

    // Insert order into orders table (without order_date)
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, address, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $address, $notes);
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $success = true;
        // Insert each cart item into order_items
        foreach ($_SESSION['cart'] as $item_id => $quantity) {
            // Validate item exists
            $check = $conn->prepare("SELECT id FROM menu_items WHERE id = ?");
            $check->bind_param("i", $item_id);
            $check->execute();
            $result = $check->get_result();
            if ($result->num_rows > 0) {
                $insert_item = $conn->prepare("INSERT INTO order_items (order_id, item_id, quantity) VALUES (?, ?, ?)");
                $insert_item->bind_param("iii", $order_id, $item_id, $quantity);
                $insert_item->execute();
            }
        }
        // Clear cart
        $_SESSION['cart'] = array();
        $_SESSION['order_message'] = "Order placed successfully!";
        $_SESSION['order_status'] = "success";
    } else {
        $_SESSION['order_message'] = "Failed to place order. Please try again.";
        $_SESSION['order_status'] = "danger";
    }
    $stmt->close();
    $conn->close();
    header('Location: Order.php');
    exit();
} else {
    $_SESSION['order_message'] = "Your cart is empty or invalid request.";
    $_SESSION['order_status'] = "warning";
    header('Location: Order.php');
    exit();
}
?>
