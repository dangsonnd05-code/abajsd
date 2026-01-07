<?php

$host = getenv("MYSQLHOST") ?: "enterbeam.proxy.rlwy.net";
$port = getenv("MYSQLPORT") ?: "19722";
$user = getenv("MYSQLUSER") ?: "root";
$pass = getenv("MYSQLPASSWORD") ?: "uoDMfeibNOBCcxsiiEVSLhIJtypTfRwn";
$db   = getenv("MYSQLDATABASE") ?: "railway";

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("❌ Kết nối DB thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
