<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = $_POST['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $user_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?success=Task added successfully");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
}

$users = $conn->query("SELECT id, name FROM users WHERE role = 'user'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .task-container {
            background: white;
            border: 1px solid #a52a2a;
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            margin-top: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .task-header {
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
        .success-msg {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="task-container">
    <h2 class="task-header"><i class="fa fa-plus-circle"></i> Add Task</h2>

    <?php if (!empty($error)): ?>
        <p class="error-msg"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST">
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

    <div class="text-center mt-3">
        <a href="admin_dashboard.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>