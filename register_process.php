<?php
session_start();
include 'admin/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirm_password) {
        echo "<script>
            alert('Passwords do not match!');
            window.location.href='register.php';
        </script>";
        exit();
    }

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<script>
            alert('Email already exists!');
            window.location.href='register.php';
        </script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $sql = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$hashed_password')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Registration successful! Please login.');
            window.location.href='login.php';
        </script>";
    } else {
        echo "<script>
            alert('Registration failed! Please try again.');
            window.location.href='register.php';
        </script>";
    }
}

mysqli_close($conn);
?>