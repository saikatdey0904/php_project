<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "foodie_hunt");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Get user from database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            
            echo "<script>
                alert('Login successful!');
                window.location.href='index.php';
            </script>";
        } else {
            echo "<script>
                alert('Invalid password!');
                window.location.href='login.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Email not found!');
            window.location.href='login.php';
        </script>";
    }
}

mysqli_close($conn);
?>