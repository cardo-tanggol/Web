<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$user_id = $_GET['id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];

    $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $new_name, $new_email, $user_id);

    if ($update_stmt->execute()) {
        header("Location: admin_dashboard.php?success=User updated successfully");
    } else {
        header("Location: admin_dashboard.php?error=Failed to update user");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 500px;
            margin-top: 50px;
            padding: 20px;
            background: white;
            border: 1px solid #a52a2a;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-brown {
            background-color: #a52a2a;
            color: white;
            border: none;
        }
        .btn-brown:hover {
            background-color: #8b1a1a;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-danger"><i class="fa fa-user-edit"></i> Edit User</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <button type="submit" class="btn btn-brown w-100">Update User</button>
    </form>
    <a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">Back to Dashboard</a>
</div>

</body>
</html>