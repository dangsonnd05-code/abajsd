<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

$rs = mysqli_query($conn, "SELECT * FROM ingredients ORDER BY name");
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Tồn kho</th>
            <th>Đơn vị</th>
            <th>Cost</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($i = mysqli_fetch_assoc($rs)): ?>
            <tr>
                <td><?= htmlspecialchars($i['name']) ?></td>
                <td><?= $i['quantity'] ?></td>
                <td><?= $i['unit'] ?></td>
                <td><?= number_format($i['cost_per_unit']) ?> đ</td>
                <td>
                    <button class="btn btn-sm btn-danger btn-delete-ingredient" data-id="<?= $i['id'] ?>">
                        Xoá
                    </button>

                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>