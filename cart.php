<?php
session_start();
require 'connect.php';
/* ===== AJAX CART ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    // ❌ Xóa sản phẩm
    if ($action === 'remove' && $id > 0) {
        unset($_SESSION['cart'][$id]);
        exit;
    }

    // ✏️ Nhập số lượng trực tiếp
    if ($action === 'setQty' && $id > 0) {
        $qty = max(1, (int) $_POST['qty']);
        $_SESSION['cart'][$id] = $qty;
        exit;
    }

    // ➕ ➖ tăng giảm
    if ($action === 'update' && $id > 0) {
        $delta = (int) $_POST['delta'];
        $_SESSION['cart'][$id] = max(1, ($_SESSION['cart'][$id] ?? 1) + $delta);
        exit;
    }
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='text-center text-muted'>Giỏ hàng trống</div>";
    exit;
}

$total = 0;

foreach ($_SESSION['cart'] as $id => $qty) {

    $id = (int) $id;
    $qty = (int) $qty;

    $rs = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");

    if (!$rs || mysqli_num_rows($rs) == 0) {
        continue; // ❗ nếu sp không tồn tại → bỏ qua
    }

    $p = mysqli_fetch_assoc($rs);

    $sum = $p['price'] * $qty;
    $total += $sum;
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <div>
            <b><?= htmlspecialchars($p['name']) ?></b><br>
            <?= number_format($p['price']) ?> đ
        </div>

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-secondary" onclick="updateQty(<?= $id ?>, -1)">−</button>

            <input type="number" min="1" value="<?= $qty ?>" class="form-control form-control-sm text-center"
                style="width:60px" onchange="setQty(<?= $id ?>, this.value)">

            <button class="btn btn-sm btn-outline-secondary" onclick="updateQty(<?= $id ?>, 1)">+</button>

            <button class="btn btn-sm btn-danger" onclick="removeItem(<?= $id ?>)">✖</button>
        </div>
    </div>

    <?php
}
?>

<div class="fw-bold text-end">
    Tổng tiền: <?= number_format($total) ?> đ
</div>