<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
    
    include 'db_connect.php';
    
    // Get count of menu items
    $menu_sql = "SELECT COUNT(*) as menu_count FROM menu_items";
    $menu_result = $conn->query($menu_sql);
    $menu_count = $menu_result->fetch_assoc()['menu_count'];

    // Get count of gallery images
    $gallery_count = 0;
    try {
        $gallery_sql = "SELECT COUNT(*) as gallery_count FROM gallery_images";
        $gallery_result = $conn->query($gallery_sql);
        $gallery_count = $gallery_result->fetch_assoc()['gallery_count'];
    } catch (mysqli_sql_exception $e) {
        $gallery_count = 0;
    }

    // Get count of contact messages
    $message_sql = "SELECT COUNT(*) as message_count FROM contact_messages";
    $message_result = $conn->query($message_sql);
    $message_count = $message_result->fetch_assoc()['message_count'];

    // Get recent orders - Modified query to match the actual table structure
    $orders_sql = "SELECT * FROM orders ORDER BY id DESC LIMIT 5";
    $orders_result = $conn->query($orders_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodie Hunt Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin-style.css">
    <style>
    .recent-messages {
        margin-top: 2rem;
        padding: 1rem;
    }

    .message-list {
        display: grid;
        gap: 1rem;
        margin-top: 1rem;
    }

    .message-card {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .message-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .message-header h4 {
        margin: 0;
        color: #333;
    }

    .message-date {
        color: #666;
        font-size: 0.9rem;
    }

    .message-subject {
        font-weight: bold;
        margin: 0.5rem 0;
        color: #e40754;
    }

    .message-preview {
        color: #666;
        margin: 0.5rem 0;
        line-height: 1.4;
    }

    .message-footer {
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #eee;
    }

    .message-email {
        color: #666;
        font-size: 0.9rem;
    }

    .profile-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }

    .admin-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    /* Add these styles inside your existing <style> tag */
    .back-home-btn {
        background-color: #e40754;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        margin-right: 10px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .back-home-btn:hover {
        background-color: #269900;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .back-home-btn i {
        font-size: 14px;
    }

    .orders-table .delete-btn {
        background-color: #ff4444;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 5px 10px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .orders-table .delete-btn:hover {
        background-color: #cc0000;
    }

    .message-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recent-orders-section {
    margin-top: 2rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.recent-orders-section h2,
.recent-messages h2 {
    margin-bottom: 1rem;
    color:rgb(38, 153, 0);
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

/* Make the selector more specific and add !important for thead */
.orders-table thead tr th,
.orders-table thead {
    background-color: #e40754 !important;
    color: white;
    font-weight: 600;
    padding: 15px;
}

.orders-table th {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    font-weight: 600;
}

.orders-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.orders-table tbody tr:hover {
    background-color:rgb(13, 202, 236); 
}

                /* Add these new styles */
                .order-item-image {
                    width: 50px;
                    height: 50px;
                    object-fit: cover;
                    border-radius: 8px;
                }

                .message-user-info {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .message-user-image {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    object-fit: cover;
                }

                .orders-table th,
                .orders-table td {
                    padding: 15px;
                    vertical-align: middle;
                }

                /* Make table header sticky */
                .orders-table thead {
                    position: sticky;
                    top: 0;
                    z-index: 1;
                }

                /* Adjust table layout */
                .orders-table {
                    min-width: 900px;
                    margin-bottom: 1rem;
                }

                .recent-orders {
                    overflow-x: auto;
                }

                .approve-btn {
                    background-color: #269900;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    padding: 5px 10px;
                    cursor: pointer;
                    margin-right: 8px;
                    transition: background-color 0.3s;
                }

                .approve-btn:hover {
                    background-color: #1e7e34;
                }
    </style>
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar">
            <div class="admin-logo">
                <img src="../uploads/IMG_20250425_112659.png-removebg-preview.png" alt="Foodie Hunt Logo" style="width: 150px; height: auto; margin-bottom: 10px;">
                <h2>Foodie Hunt Admin</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="dashboard.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'class="active"' : ''; ?>><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="hero-editor.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'hero-editor.php') ? 'class="active"' : ''; ?>><i class="fas fa-image"></i> Hero Editor</a></li>
                <li><a href="menu-editor.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'menu-editor.php') ? 'class="active"' : ''; ?>><i class="fas fa-utensils"></i> Menu Editor</a></li>
                <li><a href="gallery-editor.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'gallery-editor.php') ? 'class="active"' : ''; ?>><i class="fas fa-images"></i> Gallery Editor</a></li>
               
            </ul>
        </nav>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <?php
                    // Get admin profile info
                    $admin_id = $_SESSION['admin_id'];
                    $profile_sql = "SELECT profile_image, username FROM admin_users WHERE id = ?";
                    $profile_stmt = $conn->prepare($profile_sql);
                    $profile_stmt->bind_param("i", $admin_id);
                    $profile_stmt->execute();
                    $profile_result = $profile_stmt->get_result();
                    $admin_data = $profile_result->fetch_assoc();
                    
                    $profile_image = $admin_data['profile_image'] ? '../uploads/' . $admin_data['profile_image'] : '../uploads/admin_profiles/default-profile.png';
                    ?>
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile" class="profile-image">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="../index.php" class="back-home-btn"><i class="fas fa-home"></i> Back to Home</a>
                    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <i class="fas fa-utensils"></i>
                    <a href="menu-editor.php" style="text-decoration: none;"><h3>Menu Items</h3></a>
                    <p><?php echo $menu_count; ?> Items</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-images"></i>
                    <a href="gallery-editor.php" style="text-decoration: none;"><h3>Gallery Images</h3></a>
                    <p><?php echo $gallery_count; ?> Images</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-envelope"></i>
                    <h3>Messages</h3>
                    <p><?php echo $message_count; ?> Messages</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-receipt"></i>
                    <h3>Total Orders</h3>
                    <?php
                    $order_count_sql = "SELECT COUNT(*) as order_count FROM orders";
                    $order_count_result = $conn->query($order_count_sql);
                    if ($order_count_result && $order_count_result->num_rows > 0) {
                        $order_count = $order_count_result->fetch_assoc()['order_count'];
                        echo '<p>' . $order_count . ' Orders</p>';
                    } else {
                        echo '<p>0 Orders</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-grid">
                    <a href="hero-editor.php" class="action-card">
                        <i class="fas fa-edit"></i>
                        <span>Edit Hero Section</span>
                    </a>
                    <a href="menu-editor.php" class="action-card">
                        <i class="fas fa-plus"></i>
                        <span>Add Menu Item</span>
                    </a>
                    <a href="gallery-editor.php" class="action-card">
                        <i class="fas fa-upload"></i>
                        <span>Upload Gallery Image</span>
                    </a>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="recent-orders-section">
                <h2>Recent Orders</h2>
                <div class="recent-orders">
                    <?php if ($orders_result && $orders_result->num_rows > 0): ?>
                        <div id="order-alert" style="display:none; margin-bottom:10px; padding:10px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:5px;"></div>
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Image</th>
                                    <th>Items</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $orders_result->fetch_assoc()): 
                                    // Get order items
                                    $order_id = $order['id'];
                                    $items_sql = "SELECT oi.*, mi.name, mi.image, mi.price 
                                                FROM order_items oi 
                                                JOIN menu_items mi ON oi.item_id = mi.id 
                                                WHERE oi.order_id = $order_id";
                                    $items_result = $conn->query($items_sql);
                                    $items = [];
                                    $item_images = [];
                                    $order_total = 0;
                                    while($item = $items_result->fetch_assoc()) {
                                        $item_price = number_format($item['price'], 2);
                                        $items[] = $item['name'] . ' x' . $item['quantity'] . ' (₹' . $item_price . ')';
                                        if (!empty($item['image'])) {
                                            $item_images[] = $item['image'];
                                        }
                                        $order_total += $item['price'] * $item['quantity'];
                                    }
                                ?>
                                    <tr id="order-row-<?php echo $order['id']; ?>">
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td>
                                            <?php foreach($item_images as $img): ?>
                                                <img src="../uploads/<?php echo htmlspecialchars($img); ?>" alt="Order Item" class="order-item-image" style="margin-right:3px;">
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars(implode(', ', $items)); ?></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td>₹<?php echo number_format($order_total, 2); ?></td>
                                        <td><?php echo isset($order['created_at']) ? date('M d, Y', strtotime($order['created_at'])) : 'N/A'; ?></td>
                                        <td>
                                            <button class="approve-btn" onclick="approveOrder(<?php echo $order['id']; ?>)">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                            <button class="delete-btn" onclick="deleteOrder(<?php echo $order['id']; ?>)">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No recent orders found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Messages Section -->
            <div class="recent-messages">
                <h2>Recent Messages</h2>
                <div id="message-alert" style="display:none; margin-bottom:10px; padding:10px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:5px;"></div>
                <div class="message-list">
                    <?php
                    $recent_messages_sql = "SELECT * FROM contact_messages ORDER BY id DESC LIMIT 5";
                    $recent_messages_result = $conn->query($recent_messages_sql);
                    
                    if ($recent_messages_result->num_rows > 0) {
                        while($message = $recent_messages_result->fetch_assoc()) {
                            echo '<div class="message-card" id="message-' . $message['id'] . '">';
                            echo '<div class="message-header">';
                            echo '<div class="message-user-info">';
                            // Removed the user image line
                            echo '<h4>' . htmlspecialchars($message['name']) . '</h4>';
                            echo '</div>';
                            echo '</div>';
                            echo '<p class="message-subject">' . htmlspecialchars($message['subject']) . '</p>';
                            echo '<p class="message-preview">' . htmlspecialchars(substr($message['message'], 0, 100)) . '...</p>';
                            echo '<div class="message-footer">';
                            echo '<span class="message-email">' . htmlspecialchars($message['email']) . '</span>';
                            echo '<button class="delete-btn" onclick="deleteMessage(' . $message['id'] . ')"><i class="fas fa-trash"></i> Delete</button>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No messages yet.</p>';
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    
    <script>
    function showMessageAlert(message) {
        const alertDiv = document.getElementById('message-alert');
        alertDiv.textContent = message;
        alertDiv.style.display = 'block';
        setTimeout(() => { alertDiv.style.display = 'none'; }, 3000);
    }
    function deleteMessage(messageId) {
        if (confirm('Are you sure you want to delete this message?')) {
            fetch('delete_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'message_id=' + messageId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the message card from the DOM
                    const messageCard = document.getElementById('message-' + messageId);
                    messageCard.remove();
                    showMessageAlert('Message deleted successfully!');
                } else {
                    showMessageAlert('Error deleting message: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessageAlert('Error deleting message');
            });
        }
    }
    </script>
    <script>
    function showOrderAlert(message) {
        const alertDiv = document.getElementById('order-alert');
        alertDiv.textContent = message;
        alertDiv.style.display = 'block';
        setTimeout(() => { alertDiv.style.display = 'none'; }, 3000);
    }
    function deleteOrder(orderId) {
        if (confirm('Are you sure you want to delete this order?')) {
            fetch('delete_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=' + orderId
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove the specific order row from the DOM
                    const orderRow = document.getElementById('order-row-' + orderId);
                    if (orderRow) {
                        orderRow.remove();
                        showOrderAlert('Order deleted successfully!');
                    } else {
                        location.reload();
                    }
                } else {
                    throw new Error(data.message || 'Failed to delete order');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showOrderAlert('Failed to delete order: ' + error.message);
            });
        }
    }

    function approveOrder(orderId) {
        // Send AJAX request to notify customer
        fetch('send_order_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'order_id=' + orderId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showOrderAlert('Order #' + orderId + ' approved! ' + data.message);
            } else {
                showOrderAlert('Order approved, but: ' + data.message);
            }
        })
        .catch(error => {
            showOrderAlert('Order approved, but failed to notify customer.');
        });
    }
    </script>
</body>
</html>
