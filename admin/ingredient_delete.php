<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

$id = (int) $_POST['id'];

if ($id <= 0) {
    echo json_encode(['status' => 'error']);
    exit;
}

$del = mysqli_query($conn, "
    DELETE FROM ingredients WHERE id = $id
");

if ($del) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}

