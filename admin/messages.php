<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Delete message if requested
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM contact_messages WHERE id = '$id'");
    $_SESSION['success_message'] = "Message deleted successfully";
    header("Location: messages.php");
    exit();
}

// Fetch all messages
$result = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY date_sent DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Contact Messages</h1>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="messages-grid">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="contact-message">
                            <div class="contact-message-header">
                                <h3><?php echo htmlspecialchars($row['subject']); ?></h3>
                                <span class="timestamp"><?php echo date('M d, Y H:i', strtotime($row['date_sent'])); ?></span>
                            </div>
                            <div class="contact-message-content">
                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                            </div>
                            <div class="contact-message-meta">
                                <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['name']); ?></p>
                                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></p>
                            </div>
                            <div class="message-actions">
                                <a href="messages.php?delete=<?php echo $row['id']; ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete this message?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-messages">
                        <i class="fas fa-inbox"></i>
                        <p>No messages yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>