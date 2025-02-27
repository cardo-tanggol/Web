<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $hashed_password, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;
            header("Location: " . ($role == 'admin' ? "admin_dashboard.php" : "user_dashboard.php"));
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background: white;
            border: 1px solid #a52a2a;
            border-radius: 10px;
            padding: 30px;
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            color: #a52a2a;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-brown {
            background-color: #a52a2a;
            color: white;
            border: none;
        }
        .btn-brown:hover {
            background-color: #8b1a1a;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #a52a2a;
        }
        .form-control:focus {
            box-shadow: 0 0 5px #a52a2a;
            border-color: #a52a2a;
        }
        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .input-group-text {
            background: #a52a2a;
            color: white;
            border: 1px solid #a52a2a;
        }
        .register-link {
            text-align: center;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2><i class="fa fa-user-circle"></i> Login</h2>
    
    <?php if (!empty($error)): ?>
        <p class="error-msg"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label"><i class="fa fa-envelope"></i> Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-at"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fa fa-lock"></i> Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-key"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-brown w-100"><i class="fa fa-sign-in-alt"></i> Login</button>
    </form>

    <a href="register.php" class="register-link">Don't have an account? Register</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>