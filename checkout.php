<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    echo "<h3 class='text-center mt-5'>Giỏ hàng trống</h3>";
    exit;
}

/* ===== XỬ LÝ ĐẶT HÀNG ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $user_id = $_SESSION['user']['id'];

    $total = 0;
    foreach ($_SESSION['cart'] as $id => $qty) {
        $rs = mysqli_query($conn, "SELECT price FROM products WHERE id=$id");
        $p = mysqli_fetch_assoc($rs);
        $total += $p['price'] * $qty;
    }

    mysqli_query($conn, "
    INSERT INTO orders (user_id, customer_name, phone, address, total, status)
VALUES ($user_id, '$name', '$phone', '$address', $total, 'paid')
");
    $order_id = mysqli_insert_id($conn);

    foreach ($_SESSION['cart'] as $id => $qty) {
        $rs = mysqli_query($conn, "SELECT price FROM products WHERE id=$id");
        $p = mysqli_fetch_assoc($rs);

        mysqli_query($conn, "
            INSERT INTO order_items (order_id, product_id, price, quantity)
            VALUES ($order_id, $id, {$p['price']}, $qty)
        ");
    }
    /* ===== TRỪ NGUYÊN LIỆU ===== */
    $items = mysqli_query($conn, "
    SELECT p.recipe, oi.quantity
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = $order_id
");

    $conn->begin_transaction();

    try {

        // Lấy đơn hàng
        $orderId = $order_id; // sau khi insert orders

        $items = mysqli_query($conn, "
        SELECT product_id, quantity
        FROM order_items
        WHERE order_id = $orderId
    ");

        while ($i = mysqli_fetch_assoc($items)) {

            // ❗ TRỪ KHO
            mysqli_query($conn, "
            UPDATE products
            SET stock = stock - {$i['quantity']}
            WHERE id = {$i['product_id']}
        ");

            // ❗ GHI LỊCH SỬ XUẤT KHO (nếu muốn)
            mysqli_query($conn, "
            INSERT INTO stock_history
            (ingredient_id, change_qty, type)
            VALUES ({$i['product_id']}, {$i['quantity']}, 'out')
        ");
        }
        $conn->commit();

    } catch (Exception $e) {
        $conn->rollback();
        die('Lỗi checkout');
    }

    unset($_SESSION['cart']);

    echo "<script>
        alert('Đặt hàng thành công!');
        location.href='index.php';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5" style="max-width: 900px">

        <h3 class="mb-4">Thanh toán</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $id => $qty):
                    $rs = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
                    $p = mysqli_fetch_assoc($rs);
                    $sum = $p['price'] * $qty;
                    $total += $sum;
                    ?>
                    <tr>
                        <td><?= $p['name'] ?></td>
                        <td><?= $qty ?></td>
                        <td class="text-end"><?= number_format($sum) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h5 class="fw-bold mb-4">
            Tổng tiền: <?= number_format($total) ?> đ
        </h5>

        <form method="post">
            <div class="mb-3">
                <input type="text" name="customer_name" class="form-control form-control-lg" placeholder="Họ tên"
                    required>
            </div>

            <div class="mb-3">
                <input type="text" name="phone" class="form-control form-control-lg" placeholder="Số điện thoại"
                    required>
            </div>

            <div class="mb-4">
                <input type="text" name="address" class="form-control form-control-lg" placeholder="Địa chỉ" required>
            </div>

            <button class="btn btn-success btn-lg w-100">
                Xác nhận đặt hàng
            </button>
        </form>

    </div>
</body>

</html>