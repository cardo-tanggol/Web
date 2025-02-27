<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$tasks = $conn->query("SELECT id, title, description, status FROM tasks WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .dashboard-container {
            background: white;
            border: 1px solid #a52a2a;
            border-radius: 10px;
            padding: 30px;
            max-width: 800px;
            margin: auto;
            margin-top: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .dashboard-header {
            color: #a52a2a;
            text-align: center;
            margin-bottom: 20px;
        }
        .table thead {
            background: #a52a2a;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f9f1f1;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .status-pending {
            background-color: #ffcc00;
            color: #5a3a00;
        }
        .status-completed {
            background-color: #28a745;
            color: white;
        }
        .btn-logout {
            background-color: #a52a2a;
            color: white;
            border: none;
        }
        .btn-logout:hover {
            background-color: #8b1a1a;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2 class="dashboard-header"><i class="fa fa-tasks"></i> User Dashboard</h2>
    <h4 class="text-center">Welcome, <?= htmlspecialchars($name) ?>!</h4>
    <p class="text-center"><i class="fa fa-user"></i> Role: <strong>User</strong></p>

    <h5 class="mt-4"><i class="fa fa-list"></i> Your Tasks</h5>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Task Title</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $tasks->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td>
                        <span class="status-badge <?= $task['status'] == 'pending' ? 'status-pending' : 'status-completed' ?>">
                            <?= ucfirst($task['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="logout.php" class="btn btn-logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>