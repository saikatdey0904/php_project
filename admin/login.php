<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // After successful login verification, add this before the redirect
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_profile'] = $user['profile_image'] ? '../uploads/' . $user['profile_image'] : '../uploads/admin_profiles/default-profile.png';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Foodie Hunt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            height: 100vh;
        }
        
        .login-container {
            background:rgb(0, 34, 97); /* Primary color */
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
            background: linear-gradient(135deg,#3c081e 60%, #3c081e 100%);
            border-radius: 50%;
            width: 170px;
            height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .login-logo img {
            width: 120px;
            height: auto;
        }
        
        .login-form h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            background: #fff;
        }
        
        .login-btn {
            width: 100%;
            padding: 1rem;
            background: #fff;
            color: #e40754;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .login-btn:hover {
            background: #7cff02;
        }
        
        .error-message {
            color: #ff0000;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .register-link {
            text-align: center;
            margin-top: 1rem;
        }
        
        .register-link a {
            color: #fff;
            text-decoration: none;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div class="loader-overlay" id="loaderOverlay">
        <dotlottie-player src="https://lottie.host/a18cd5e1-12c2-4546-b9bf-f9551c1f22f7/YccWyHwHTv.lottie" background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
    </div>
    <div class="login-container">
        <div class="login-logo">
            <img src="../uploads/IMG_20250425_112659.png-removebg-preview.png" alt="Foodie Hunt Logo">
        </div>
        <form class="login-form" method="POST" action="">
            <h2>Admin Login</h2>
            <?php if(isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
            <div class="register-link">
                <a href="register.php">Register as Admin</a>
            </div>
        </form>
    </div>
    <script>
        window.addEventListener('load', function() {
            document.getElementById('loaderOverlay').style.display = 'none';
        });
    </script>
</body>
</html>