<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

$rs = mysqli_query($conn, "
    SELECT *
    FROM orders
    WHERE user_id = $user_id
    ORDER BY id DESC
");
?>

<h3>📜 Lịch sử đơn hàng</h3>

<table class="table table-bordered">
    <tr>
        <th>Mã đơn</th>
        <th>Ngày đặt</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>

    <?php while ($o = mysqli_fetch_assoc($rs)): ?>
        <tr>
            <td>#
                <?= $o['id'] ?>
            </td>
            <td>
                <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?>
            </td>
            <td>
                <?= number_format($o['total']) ?> đ
            </td>
            <td>
                <?php
                $map = [
                    0 => 'Chờ xác nhận',
                    1 => 'Đang chuẩn bị',
                    2 => 'Đang giao',
                    3 => 'Đã giao',
                    4 => 'Huỷ'
                ];
                echo $map[$o['status']] ?? 'Không rõ';
                ?>
            </td>
            <td>
                <?php if (in_array($o['status'], [0, 1])): ?>
                    <button class="btn btn-danger btn-sm" onclick="cancelOrder(<?= $o['id'] ?>)">
                        Huỷ đơn
                    </button>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<script>
    function cancelOrder(id) {
        if (!confirm('❗ Bạn có chắc chắn muốn huỷ đơn #' + id + ' ?')) return;

        fetch('order_cancel.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ Đã huỷ đơn');
                    location.reload();
                } else {
                    alert(data.msg);
                }
            });
    }
</script>