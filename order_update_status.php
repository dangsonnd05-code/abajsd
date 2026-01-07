<?php
require_once __DIR__ . '/../connect.php';

$order_id = (int) $_POST['order_id'];
$new_status = $_POST['status'];

// Lấy trạng thái cũ
$rs = mysqli_query($conn, "
    SELECT status 
    FROM orders 
    WHERE id = $order_id
");
$order = mysqli_fetch_assoc($rs);

$old_status = $order['status'];

/* =============================
   NẾU HUỶ → HOÀN KHO
============================= */
if ($new_status === 'cancelled' && $old_status !== 'cancelled') {

    // Lấy danh sách sản phẩm trong đơn
    $items = mysqli_query($conn, "
        SELECT product_id, qty
        FROM order_items
        WHERE order_id = $order_id
    ");

    while ($item = mysqli_fetch_assoc($items)) {
        $product_id = (int) $item['product_id'];
        $qty = (int) $item['qty'];

        // CỘNG LẠI STOCK
        mysqli_query($conn, "
            UPDATE products
            SET stock = stock + $qty
            WHERE id = $product_id
        ");
    }
}

// Cập nhật trạng thái đơn
mysqli_query($conn, "
    UPDATE orders
    SET status = '$new_status'
    WHERE id = $order_id
");

header('Location: dashboard.php#orders');
exit;
