<?php
session_start();
include "config/db.php";

$uid=$_SESSION['user']['id'];
$total=$_SESSION['total'];

mysqli_query($conn,"INSERT INTO orders(user_id,total)
VALUES($uid,$total)");

echo "<h2>✔ Thanh toán thành công</h2>";
echo "<p>Khách hàng: ".$_SESSION['user']['name']."</p>";
echo "<p>Tổng tiền: ".number_format($total)." VNĐ</p>";
