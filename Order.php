<?php
session_start();
include 'admin/db_connect.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle add/remove items
if (isset($_POST['action'])) {
    $item_id = $_POST['item_id'];
    
    if ($_POST['action'] == 'add') {
        if (!isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id] = 1;
        } else {
            $_SESSION['cart'][$item_id]++;
        }
    } elseif ($_POST['action'] == 'remove' && isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]--;
        if ($_SESSION['cart'][$item_id] <= 0) {
            unset($_SESSION['cart'][$item_id]);
        }
    }
    // Redirect to prevent form resubmission
    header('Location: Order.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order - Foodie Hunt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            /* Gradient background: white to #73fffd */
            background: linear-gradient(150deg, #ffffff 0%, #73fffd 100%);
            min-height: 100vh;
        }

        /* Cart Item Styles */
        .cart-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .cart-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Quantity Control Styles */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            background-color: #e40754;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .quantity-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .quantity-btn:hover:not(:disabled) {
            background-color: #c20543;
        }

        /* Menu Tab Styles */
        .menu-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 8px 16px;
            border: 2px solid #e40754;
            background: none;
            color: #e40754;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .tab-btn.active,
        .tab-btn:hover {
            background: #e40754;
            color: white;
        }

        /* Menu Item Styles */
        .menu-item {
            transition: all 0.3s ease;
        }

        .menu-item.hide {
            display: none;
        }

        .back-home-btn {
            display: inline-block;
            margin: 20px 0 0 20px;
            background: #e40754;
            color: #fff !important;
            padding: 10px 22px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: background 0.3s, color 0.3s;
        }
        .back-home-btn:hover {
            background: #c20543;
            color: #fff !important;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-home-btn"><i class="fas fa-home"></i> Back to Home</a>

   

    <div class="container my-5">
        <?php if(isset($_SESSION['order_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['order_status']; ?> alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['order_message'];
                    unset($_SESSION['order_message']);
                    unset($_SESSION['order_status']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="row">
            <!-- Menu Items -->
            <div class="col-md-8">
                <h2 class="mb-4">Menu Items</h2>
                <div class="menu-tabs mb-4">
                    <button class="tab-btn active" data-category="all">All</button>
                    <?php 
                    // Fetch unique categories from menu_items table 
                    $sql = "SELECT DISTINCT category FROM menu_items ORDER BY category"; 
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $display_text = ucwords(str_replace('_', ' ', $row['category']));
                            echo '<button class="tab-btn" data-category="' . $row['category'] . '">' . $display_text . '</button>';
                        }
                    }
                    ?>
                </div>
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM menu_items";
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while($item = $result->fetch_assoc()) {
                            ?>
                            <div class="col-md-6 mb-4 menu-item" data-category="<?php echo $item['category']; ?>">
                                <div class="cart-item">
                                    <div class="d-flex align-items-center">
                                        <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             class="me-3">
                                        <div>
                                            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                            <p class="mb-2">₹<?php echo number_format($item['price'], 2); ?></p>
                                            <form method="post" class="quantity-control">
                                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" name="action" value="remove" class="quantity-btn" 
                                                        <?php echo (!isset($_SESSION['cart'][$item['id']]) || $_SESSION['cart'][$item['id']] <= 0) ? 'disabled' : ''; ?>>
                                                    -
                                                </button>
                                                <span class="mx-2">
                                                    <?php echo isset($_SESSION['cart'][$item['id']]) ? $_SESSION['cart'][$item['id']] : 0; ?>
                                                </span>
                                                <button type="submit" name="action" value="add" class="quantity-btn">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="col-12"><p>No menu items available.</p></div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Order Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Your Order</h3>
                        <?php
                        $total = 0;
                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item_id => $quantity) {
                                $sql = "SELECT * FROM menu_items WHERE id = $item_id";
                                $result = $conn->query($sql);
                                $item = $result->fetch_assoc();
                                $subtotal = $item['price'] * $quantity;
                                $total += $subtotal;
                                
                                echo '<div class="mb-3">';
                                echo '<div class="d-flex justify-content-between">';
                                echo '<span>' . $item['name'] . ' x ' . $quantity . '</span>';
                                echo '<span>$' . number_format($subtotal, 2) . '</span>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Your cart is empty</p>';
                        }
                        ?>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <h5>Total:</h5>
                            <h5>$<?php echo number_format($total, 2); ?></h5>
                        </div>

                        <form action="process_order.php" method="POST" class="delivery-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Special Instructions</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" style="background-color: #e40754; border: none;">
                                Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const menuItems = document.querySelectorAll('.menu-item');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Remove active class from all buttons
                    tabBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    btn.classList.add('active');

                    const category = btn.getAttribute('data-category');
                    
                    menuItems.forEach(item => {
                        if (category === 'all' || item.getAttribute('data-category') === category) {
                            item.classList.remove('hide');
                        } else {
                            item.classList.add('hide');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>