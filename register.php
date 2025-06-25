<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Foodie Hunt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            padding: 15px;
        }
        .register-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .register-header h2 {
            color: #ff2769;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        .form-control {
            padding: 8px 12px;
            font-size: 0.95rem;
        }
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        .btn-primary {
            padding: 8px 12px;
            font-size: 1rem;
            background-color: #ff2769;
            border-color: #ff2769;
        }
        .btn-primary:hover {
            background-color: #e62160;
            border-color: #e62160;
        }
        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }
        .form-check-label {
            color: #ff2769;
        }
        .form-check-input:checked {
            background-color: #ff2769;
            border-color: #ff2769;
        }
        .text-center p, .text-center a {
            color: #ff2769;
        }
        a:hover {
            color: #e62160;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-form">
            <div class="register-header">
                <h2>Foodie Hunt</h2>
                <p>Create your account</p>
            </div>
            <form action="register_process.php" method="POST">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label" for="terms">I agree to the Terms & Conditions</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
                <div class="text-center">
                    <p class="mb-0">Already have an account? <a href="login.php" class="text-decoration-none">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>