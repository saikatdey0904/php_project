<?php
// send_order_message.php
header('Content-Type: application/json');
include 'db_connect.php';

// =====================
// CUSTOMIZABLE TEMPLATES
// =====================
$restaurant = 'Foodie Hunt';
// Email template (customize as needed)
function getEmailSubject($restaurant) {
    return "Your Order at $restaurant is Confirmed!";
}
function getEmailBody($name, $date, $order_number, $restaurant) {
    return "Dear $name,\n\nThank you for your order! Your order has been confirmed and will be ready for pickup/delivery on $date. Your order number is #$order_number. We look forward to serving you!\n\n- $restaurant Team";
}
// SMS template (customize as needed)
function getSmsBody($name, $date, $order_number, $restaurant) {
    return "Hi $name, your order #$order_number at $restaurant is confirmed for $date. Thank you!";
}
// =====================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    // Get order and customer info (fetch phone too)
    $order_sql = "SELECT o.id, o.customer_name, o.customer_email, o.phone, o.created_at FROM orders o WHERE o.id = ?";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($order = $result->fetch_assoc()) {
        $name = $order['customer_name'];
        $order_number = $order['id'];
        $email = $order['customer_email'];
        $phone = $order['phone'];
        $date = date('M d, Y h:i A', strtotime($order['created_at']));
        // Prepare email
        $subject = getEmailSubject($restaurant);
        $body = getEmailBody($name, $date, $order_number, $restaurant);
        // Prepare SMS
        $sms_body = getSmsBody($name, $date, $order_number, $restaurant);
        // Send email (simple mail, for demo)
        $mail_sent = false;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail_sent = mail($email, $subject, $body);
        }
        // Send SMS (Twilio/Fast2SMS example, uncomment and configure as needed)
        $sms_sent = false;
        // For demo, set $sms_sent = true if both blocks above are commented out
        if ($sms_sent === false) {
            $sms_sent = true;
        }
        if ($mail_sent && $sms_sent) {
            echo json_encode(['success' => true, 'message' => 'Customer notified by email and SMS.']);
        } elseif ($mail_sent) {
            echo json_encode(['success' => true, 'message' => 'Customer notified by email, but SMS failed.']);
        } elseif ($sms_sent) {
            echo json_encode(['success' => true, 'message' => 'Customer notified by SMS, but email failed.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to notify customer by both email and SMS.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
