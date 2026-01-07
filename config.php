<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ===== DATABASE CONFIG ===== */
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'web_cafe';

/* ===== MYSQLI CONNECT ===== */
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die("Lỗi kết nối DB: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

/* ===== SESSION ===== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
