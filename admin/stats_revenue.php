<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

$date = $_GET['date'] ?? date('Y-m-d');

/* DOANH THU */
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(total),0) total
    FROM orders
    WHERE status = 3 AND DATE(created_at) = '$date'
"))['total'] ?? 0;

/* GIÁ VỐN */
$cost = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(total_cost),0) cost
    FROM stock_history
    WHERE type='out' AND DATE(created_at)='$date'
"))['cost'] ?? 0;

$profit = $revenue - $cost;

/* SẢN PHẨM */
$productStats = mysqli_query($conn, "
    SELECT p.name,
           SUM(oi.quantity) qty,
           SUM(oi.quantity * oi.price) revenue
    FROM order_items oi
    JOIN orders o ON o.id = oi.order_id
    JOIN products p ON p.id = oi.product_id
    WHERE o.status = 3
      AND DATE(o.created_at) = '$date'
    GROUP BY p.id
");
?>


<div class="container-fluid">

    <h3 class="mb-4">Thống kê doanh thu</h3>

    <!-- DATE PICKER + BUTTON -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="date" id="revenueDate" class="form-control" value="<?= $date ?>">
        </div>

        <div class="col-md-4">
            <button id="btnApplyDate" class="btn btn-primary w-100">
                ✔ Xác nhận đổi ngày
            </button>
        </div>

        <div class="col-md-4">
            <a class="btn btn-success w-100" href="/web_cafe/admin/export_invoice_excel.php?date=<?= $date ?>"
                target="_blank">
                ⬇ Xuất hóa đơn
            </a>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <small>Doanh thu (<?= date('d/m/Y', strtotime($date)) ?>)</small>
                <h4 class="text-success"><?= number_format($revenue) ?> đ</h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <small>Giá vốn</small>
                <h4 class="text-danger"><?= number_format($cost) ?> đ</h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <small>Lợi nhuận</small>
                <h4 class="text-primary"><?= number_format($profit) ?> đ</h4>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header fw-bold">Sản phẩm đã bán</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($productStats) == 0): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Không có đơn hàng trong ngày
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php while ($p = mysqli_fetch_assoc($productStats)): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= (int) $p['qty'] ?></td>
                            <td class="text-success"><?= number_format($p['revenue']) ?> đ</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- JS -->
<script>
    const dateInput = document.getElementById('revenueDate');
    const btnApply = document.getElementById('btnApplyDate');

    btnApply.addEventListener('click', function () {
        const date = dateInput.value;
        if (!date) {
            alert('Vui lòng chọn ngày');
            return;
        }

        loadPage(
            '/web_cafe/admin/stats_revenue.php?date=' + date,
            'Doanh thu'
        );
    });
</script>