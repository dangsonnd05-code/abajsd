<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

$rs = mysqli_query($conn, "
    SELECT 
        COALESCE(i.name, sh.ingredient_name) AS ingredient,
        sh.change_qty,
        sh.unit,
        sh.cost_per_unit,
        sh.total_cost,
        sh.created_at
    FROM stock_history sh
    LEFT JOIN ingredients i ON i.id = sh.ingredient_id
    ORDER BY sh.created_at DESC
");
?>

<h4>Lịch sử kho</h4>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nguyên liệu</th>
            <th>Số lượng</th>
            <th>Đơn vị</th>
            <th>Cost</th>
            <th>Tổng Cost</th>
            <th>Thời gian</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($h = mysqli_fetch_assoc($rs)): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($h['ingredient']) ?>
                </td>
                <td><?= $h['change_qty'] ?></td>
                <td><?= $h['unit'] ?></td>
                <td><?= number_format($h['cost_per_unit']) ?> đ</td>
                <td><?= number_format($h['total_cost']) ?> đ</td>
                <td><?= $h['created_at'] ?></td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>