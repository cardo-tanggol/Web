<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];

    $stmt = $conn->prepare("UPDATE tasks SET status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?success=Task marked as completed");
    } else {
        header("Location: admin_dashboard.php?error=Failed to update task status");
    }
    exit;
}
?>