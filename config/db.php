<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','web_cafe');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) die("Lỗi kết nối DB");

mysqli_set_charset($conn,'utf8mb4');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
