<?php
session_start();
require 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$users = $conn->query("SELECT id, name FROM users WHERE role = 'user'");
$tasks = $conn->query("SELECT tasks.id, tasks.title, tasks.description, tasks.status, users.name 
                       FROM tasks JOIN users ON tasks.user_id = users.id ORDER BY tasks.id DESC");
$all_users = $conn->query("SELECT id, name, email FROM users WHERE role = 'user' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            max-width: 900px;
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
        .btn-brown {
            background-color: #a52a2a;
            color: white;
            border: none;
        }
        .btn-brown:hover {
            background-color: #8b1a1a;
        }
        .btn-danger:hover {
            background-color: #bb2d3b;
        }
        .btn-complete {
            background-color: #28a745;
            color: white;
            border: none;
        }
        .btn-complete:hover {
            background-color: #218838;
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
        .logout-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2 class="dashboard-header"><i class="fa fa-user-shield"></i> Admin Dashboard</h2>
    <h4 class="text-center">Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h4>
    <p class="text-center"><i class="fa fa-user"></i> Role: <strong>Admin</strong></p>

    <!-- Add Task Form -->
    <h5 class="mt-4"><i class="fa fa-plus-circle"></i> Assign a New Task</h5>
    <form method="POST" action="add_task.php">
        <div class="mb-3">
            <label class="form-label"><i class="fa fa-tasks"></i> Task Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter task title" required>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fa fa-align-left"></i> Description</label>
            <textarea name="description" class="form-control" placeholder="Enter task description" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fa fa-user"></i> Assign to User</label>
            <select name="user_id" class="form-control" required>
                <option value="">Select User</option>
                <?php while ($user = $users->fetch_assoc()) : ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-brown w-100"><i class="fa fa-check-circle"></i> Assign Task</button>
    </form>

    <!-- Manage Users Section -->
    <h5 class="mt-4"><i class="fa fa-users"></i> Manage Users</h5>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $all_users->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                            <i class="fa fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Task Management Section -->
    <h5 class="mt-4"><i class="fa fa-list"></i> All Tasks</h5>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $tasks->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td><?= htmlspecialchars($task['name']) ?></td>
                    <td>
                        <span class="status-badge <?= $task['status'] == 'pending' ? 'status-pending' : 'status-completed' ?>">
                            <?= ucfirst($task['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($task['status'] == 'pending') : ?>
                            <form action="mark_complete.php" method="POST" style="display:inline;">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <button type="submit" class="btn btn-complete btn-sm"><i class="fa fa-check-circle"></i> Mark Complete</button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-sm" disabled><i class="fa fa-check"></i> Completed</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="logout-container">
        <a href="logout.php" class="btn btn-brown"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>