<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /web_cafe/login.php");
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    die("Bạn không có quyền truy cập!");
}
