<?php
session_start();
include 'admin/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['contact_message'] = "Thank you for your message. We'll get back to you soon!";
        $_SESSION['contact_status'] = "success";
    } else {
        $_SESSION['contact_message'] = "Sorry, there was an error sending your message. Please try again.";
        $_SESSION['contact_status'] = "danger";
    }
    
    header("Location: index.php#contact");
    exit();
}
?>

mysqli_close($conn);
?>