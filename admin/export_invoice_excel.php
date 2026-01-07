<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

$from = $_GET['from'] ?? date('Y-m-d');
$to = $_GET['to'] ?? date('Y-m-d');

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=hoa_don_$from-$to.xls");
header("Pragma: no-cache");
header("Expires: 0");

$sql = "
    SELECT id, customer_name, phone, address, total, created_at
    FROM orders
    WHERE status = 3
      AND DATE(created_at) BETWEEN '$from' AND '$to'
    ORDER BY created_at ASC
";
$rs = mysqli_query($conn, $sql);

$totalAll = 0;

echo "
<style>
    body { font-family: Arial, sans-serif; }
    .title {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        color: #6f4e37;
    }
    .sub {
        text-align: center;
        font-size: 13px;
        color: #555;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 15px;
    }
    th {
        background: #6f4e37;
        color: #fff;
        font-weight: bold;
        text-align: center;
        padding: 8px;
        border: 1px solid #333;
    }
    td {
        border: 1px solid #333;
        padding: 6px;
    }
    .right { text-align: right; }
    .center { text-align: center; }
    .total-row {
        background: #f5f5f5;
        font-weight: bold;
        font-size: 14px;
    }
</style>

<div class='title'>☕ KIOH CAFE</div>
<div class='sub'>BÁO CÁO HOÁ ĐƠN BÁN HÀNG</div>
<div class='sub'>Từ ngày <b>$from</b> đến <b>$to</b></div>

<table>
<tr>
    <th>ID</th>
    <th>Khách hàng</th>
    <th>SĐT</th>
    <th>Địa chỉ</th>
    <th>Tổng tiền (đ)</th>
    <th>Ngày đặt</th>
</tr>
";

while ($row = mysqli_fetch_assoc($rs)) {
    $totalAll += $row['total'];

    echo "
    <tr>
        <td class='center'>#{$row['id']}</td>
        <td>{$row['customer_name']}</td>
        <td class='center'>{$row['phone']}</td>
        <td>{$row['address']}</td>
        <td class='right'>" . number_format($row['total']) . "</td>
        <td class='center'>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>
    </tr>
    ";
}

echo "
<tr class='total-row'>
    <td colspan='4' class='right'>TỔNG DOANH THU</td>
    <td class='right'>" . number_format($totalAll) . "</td>
    <td></td>
</tr>
</table>

<br>
<div class='sub'>Xuất lúc: " . date('d/m/Y H:i:s') . "</div>
";

exit;
