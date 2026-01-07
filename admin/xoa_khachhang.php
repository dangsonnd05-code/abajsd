<?php
session_start();
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error']);
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

mysqli_query($conn, "DELETE FROM users WHERE id=$id AND role='user'");

echo json_encode(['status' => 'success']);
exit;
