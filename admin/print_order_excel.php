<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../connect.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$id = (int) ($_GET['id'] ?? 0);
if (!$id)
    exit;

$order = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM orders WHERE id=$id")
);

$items = mysqli_query($conn, "
    SELECT p.name, od.quantity, od.price
    FROM order_items od
    JOIN products p ON od.product_id = p.id
    WHERE od.order_id = $id
");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

/* ===== HEADER ===== */
$sheet->setCellValue('A1', 'PHIẾU GIAO HÀNG');
$sheet->mergeCells('A1:D1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

/* ===== INFO ===== */
$sheet->setCellValue('A3', 'Khách hàng:');
$sheet->setCellValue('B3', $order['customer_name']);

$sheet->setCellValue('A4', 'SĐT:');
$sheet->setCellValue('B4', $order['phone']);

$sheet->setCellValue('A5', 'Địa chỉ:');
$sheet->setCellValue('B5', $order['address']);

/* ===== TABLE HEADER ===== */
$row = 7;
$sheet->setCellValue("A$row", 'Sản phẩm');
$sheet->setCellValue("B$row", 'Số lượng');
$sheet->setCellValue("C$row", 'Đơn giá');
$sheet->setCellValue("D$row", 'Thành tiền');

$sheet->getStyle("A$row:D$row")->getFont()->setBold(true);

/* ===== DATA ===== */
$total = 0;
$row++;

while ($r = mysqli_fetch_assoc($items)) {
    $sum = $r['quantity'] * $r['price'];
    $total += $sum;

    $sheet->setCellValue("A$row", $r['name']);
    $sheet->setCellValue("B$row", $r['quantity']);
    $sheet->setCellValue("C$row", $r['price']);
    $sheet->setCellValue("D$row", $sum);
    $row++;
}

/* ===== TOTAL ===== */
$sheet->setCellValue("C$row", 'Tổng tiền');
$sheet->setCellValue("D$row", $total);
$sheet->getStyle("C$row:D$row")->getFont()->setBold(true);

/* ===== AUTO WIDTH ===== */
foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

/* ===== OUTPUT ===== */
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=don_hang_$id.xlsx");

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
