<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

header('Content-Type: application/json; charset=utf-8');

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID không hợp lệ'
    ]);
    exit;
}

$sql = "DELETE FROM products WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode([
        'status' => 'success'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Không tìm thấy sản phẩm'
    ]);
}
