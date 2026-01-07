<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /web_cafe/login.php");
    exit;
}


if ($_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo "Bạn không có quyền truy cập trang này";
    exit;
}
require_once __DIR__ . '/../config/db.php';