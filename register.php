<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    
    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .register-container {
            background: white;
            border: 1px solid #a52a2a;
            border-radius: 10px;
            padding: 30px;
            max-width: 400px;
            margin: auto;
            margin-top: 80px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
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
        .login-link {
            text-align: center;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2><i class="fa fa-user-plus"></i> Register</h2>
    
    <?php if (!empty($error)): ?>
        <p class="error-msg"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label"><i class="fa fa-user"></i> Full Name</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
            </div>
        </div>

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

        <button type="submit" class="btn btn-brown w-100"><i class="fa fa-user-check"></i> Register</button>
    </form>

    <a href="login.php" class="login-link">Already have an account? Login</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>