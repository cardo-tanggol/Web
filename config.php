<?php
$host = '192.168.100.244';
$db = 'task_management';
$user = 'root';
$pass = 'root';
$port = '3306';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>