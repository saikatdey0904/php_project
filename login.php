<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Foodie Hunt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            perspective: 1000px;
        }
        .login-card {
            position: relative;
            width: 100%;
            max-width: 400px;
            height: 550px;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }
        .login-card.flipped {
            transform: rotateY(180deg);
        }
        .login-form, .admin-login-form {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .admin-login-form {
            transform: rotateY(180deg);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #ff2769;
            margin-bottom: 10px;
        }
        .form-control:focus {
            border-color: #ff2769;
            box-shadow: 0 0 0 0.25rem rgba(255, 39, 105, 0.25);
        }
        .btn-primary {
            background-color: #ff2769;
            border-color: #ff2769;
        }
        .btn-primary:hover {
            background-color: #e62160;
            border-color: #e62160;
        }
        .form-check-input:checked {
            background-color: #ff2769;
            border-color: #ff2769;
        }
        a {
            color: #ff2769;
        }
        a:hover {
            color: #e62160;
        }
        .flip-button {
            text-align: center;
            margin-top: 10px;
            cursor: pointer;
            color: #b10165;
            font-size: 20px;
            padding: 10px;
            transition: all 0.3s ease;
        }
        .flip-button:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- User Login Form -->
            <div class="login-form">
                <div class="login-header">
                    <h2>Foodie Hunt</h2>
                    <p>Welcome back! Please login to continue</p>
                </div>
                <form action="login_process.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                    <div class="text-center">
                        <a href="#" class="text-decoration-none">Forgot password?</a>
                        <p class="mt-3 mb-0">Don't have an account? <a href="register.php" class="text-decoration-none">Sign up</a></p>
                    </div>
                </form>
                
</body>
</html>