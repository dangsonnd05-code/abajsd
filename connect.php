<?php

$host = getenv("MYSQLHOST") ?: "centerbeam.proxy.rlwy.net";
$port = getenv("MYSQLPORT") ?: "19722";
$user = getenv("MYSQLUSER") ?: "root";
$pass = getenv("MYSQLPASSWORD") ?: "uoDMfeibNOBCcxsiieVSLhIJtypTfRwn";
$db   = getenv("MYSQLDATABASE") ?: "railway";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // echo "✅ Kết nối DB thành công";

} catch (PDOException $e) {
    die("❌ Lỗi kết nối DB: " . $e->getMessage());
}
