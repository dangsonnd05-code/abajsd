<?php
session_start();

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    exit('ERROR');
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;

echo 'OK';
