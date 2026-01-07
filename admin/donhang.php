<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
/* ====== DELETE ORDER ====== */
if ($action === 'delete' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $rs = mysqli_query($conn, "SELECT status FROM orders WHERE id = $id");
    $order = mysqli_fetch_assoc($rs);

    if (!$order) {
        echo json_encode(['status' => 'error', 'msg' => 'Đơn hàng không tồn tại']);
        exit;
    }

    if ((int) $order['status'] !== 4) {
        echo json_encode([
            'status' => 'error',
            'msg' => 'Chỉ được xoá đơn hàng đã huỷ'
        ]);
        exit;
    }

    mysqli_query($conn, "DELETE FROM order_items WHERE order_id = $id");
    mysqli_query($conn, "DELETE FROM orders WHERE id = $id");

    echo json_encode(['status' => 'success']);
    exit;
}
$count_pending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE status = 0")
)['c'] ?? 0;

$count_preparing = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE status = 1")
)['c'] ?? 0;

$count_shipping = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE status = 2")
)['c'] ?? 0;

$count_done = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE status = 3")
)['c'] ?? 0;

$count_cancel = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) c FROM orders WHERE status = 4")
)['c'] ?? 0;
/* ====== UPDATE AJAX ====== */
if ($action === 'update' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json; charset=utf-8');

    $newStatus = (int) ($_POST['status'] ?? 0);
    $shipper = mysqli_real_escape_string($conn, $_POST['shipper_name'] ?? '');

    $delivery_time = $_POST['delivery_time'] ?? null;
    $eta_time = $_POST['eta_time'] ?? null;

    // LẤY TRẠNG THÁI CŨ
    $old = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT status FROM orders WHERE id = $id")
    );
    $oldStatus = (int) ($old['status'] ?? -1);

    mysqli_begin_transaction($conn);

    try {

        // ✅ NẾU CHUYỂN SANG HUỶ → HOÀN KHO
        if ($newStatus === 4 && $oldStatus !== 4) {
            $items = mysqli_query($conn, "
                SELECT product_id, quantity
                FROM order_items
                WHERE order_id = $id
            ");
            while ($it = mysqli_fetch_assoc($items)) {
                mysqli_query($conn, "
                    UPDATE products
                    SET stock = stock + {$it['quantity']}
                    WHERE id = {$it['product_id']}
                ");
            }
        }

        // ⏰ SET delivered_at KHI ĐÃ GIAO
        $delivered_at = $_POST['delivered_at'] ?? null;
        mysqli_query($conn, "
    UPDATE orders SET
        status = $newStatus,
        shipper_name = '$shipper',
        delivery_time = " . ($delivery_time ? "'$delivery_time'" : "NULL") . ",
        eta_time = " . ($eta_time ? "'$eta_time'" : "NULL") . ",
        delivered_at = " . ($delivered_at ? "'$delivered_at'" : "NULL") . "
    WHERE id = $id
");


        mysqli_commit($conn);
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['status' => 'error', 'msg' => 'Rollback']);
    }

    exit;
}
?>
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <small>Chờ xác nhận</small>
                <h4><?= $count_pending ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <small>Chuẩn bị</small>
                <h4><?= $count_preparing ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <small>Đang giao</small>
                <h4><?= $count_shipping ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <small>Đã giao</small>
                <h4><?= $count_done ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <small>Huỷ</small>
                <h4><?= $count_cancel ?></h4>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <h2 class="mb-4">📦 Quản lý đơn hàng</h2>

    <?php
    /* =======================
       DANH SÁCH ĐƠN HÀNG
    ======================= */
    if ($action === 'list'):
        $q = mysqli_query($conn, "
    SELECT 
    orders.id,
    orders.customer_name,
    orders.phone,
    orders.address,
    orders.total,
    orders.status,
    orders.created_at,
    users.username
FROM orders
JOIN users ON orders.user_id = users.id
ORDER BY orders.id DESC

");
        ?>

        <table class="table table-bordered table-hover">
            <tr class="table-dark">
                <th>ID</th>
                <th>Khách hàng</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Tổng</th>
                <th>Trạng thái</th>
                <th>Ngày</th>
                <th>Hành động</th>
            </tr>

            <?php while ($d = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td>#<?= $d['id'] ?></td>
                    <td><?= htmlspecialchars($d['customer_name']) ?></td>
                    <td><?= htmlspecialchars($d['phone']) ?></td>
                    <td><?= htmlspecialchars($d['address']) ?></td>
                    <td><?= number_format($d['total']) ?> đ</td>
                    <td>
                        <?php
                        $statusText = [
                            0 => 'Chờ xử lý',
                            1 => 'Đang xử lý',
                            2 => 'Hoàn thành',
                            3 => 'Huỷ'
                        ];

                        $statusMap = [
                            0 => ['text' => 'Chờ xác nhận', 'class' => 'secondary'],
                            1 => ['text' => 'Đang chuẩn bị', 'class' => 'info'],
                            2 => ['text' => 'Đang giao', 'class' => 'warning'],
                            3 => ['text' => 'Đã giao', 'class' => 'success'],
                            4 => ['text' => 'Huỷ', 'class' => 'danger']
                        ];
                        $s = $statusMap[$d['status']] ?? ['text' => 'Không rõ', 'class' => 'dark'];
                        ?>
                        <span class="badge bg-<?= $s['class'] ?>">
                            <?= $s['text'] ?>
                        </span>
                    </td>
                    <td><?= $d['created_at'] ?></td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm"
                            onclick="loadPage('/web_cafe/admin/donhang.php?action=view&id=<?= $d['id'] ?>','Chi tiết đơn hàng')">
                            Chi tiết
                        </a>
                        <a href="#" class="btn btn-warning btn-sm"
                            onclick="loadPage('/web_cafe/admin/donhang.php?action=update&id=<?= $d['id'] ?>','Cập nhật đơn hàng')">
                            Cập nhật
                        </a>
                        <?php if ($d['status'] == 4): ?>
                            <a href="#" class="btn btn-danger btn-sm" onclick="deleteOrder(<?= $d['id'] ?>)">
                                Xoá
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <?php
        /* =======================
           CHI TIẾT ĐƠN HÀNG
        ======================= */
    elseif ($action === 'view' && $id):
        $order = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT * FROM orders WHERE id = $id")
        );
        $ct = mysqli_query($conn, "
    SELECT od.*, p.name
    FROM order_items od
    JOIN products p ON od.product_id = p.id
    WHERE od.order_id = $id
");
        ?>

        <a href="#" class="btn btn-secondary mb-3" onclick="loadPage('/web_cafe/admin/donhang.php','Quản lý đơn hàng')">
            Quay lại
        </a>

        <h4>Chi tiết đơn hàng #<?= $id ?></h4>
        <div class="card mb-4">
            <div class="card-header fw-bold">
                🚚 Thông tin giao hàng
            </div>
            <div class="card-body">
                <p><b>Khách hàng:</b> <?= htmlspecialchars($order['customer_name']) ?></p>
                <p><b>Số điện thoại:</b> <?= htmlspecialchars($order['phone']) ?></p>
                <p><b>Địa chỉ:</b> <?= htmlspecialchars($order['address']) ?></p>

                <hr>

                <p>
                    <b>Shipper:</b>
                    <?= $order['shipper_name']
                        ? htmlspecialchars($order['shipper_name'])
                        : '<span class="text-muted">Chưa gán</span>' ?>
                </p>
                <p>
                    <b>Bắt đầu giao:</b>
                    <?= $order['delivery_time']
                        ? date('d/m/Y H:i', strtotime($order['delivery_time']))
                        : '<span class="text-muted">Chưa có</span>' ?>
                </p>

                <p>
                    <b>ETA (ước tính):</b>
                    <?= $order['eta_time']
                        ? date('d/m/Y H:i', strtotime($order['eta_time']))
                        : '<span class="text-muted">Chưa có</span>' ?>
                </p>

                <p>
                    <b>Khách nhận lúc:</b>
                    <?= $order['delivered_at']
                        ? date('d/m/Y H:i', strtotime($order['delivered_at']))
                        : '<span class="text-muted">Chưa giao</span>' ?>
                </p>
            </div>
        </div>
        <a href="/web_cafe/admin/print_order_excel.php?id=<?= $id ?>" class="btn btn-success mb-3">
            In phiếu giao hàng
        </a>

        <table class="table table-bordered">
            <tr class="table-secondary">
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
            </tr>
            <?php while ($r = mysqli_fetch_assoc($ct)): ?>
                <tr>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= $r['quantity'] ?></td>
                    <td><?= number_format($r['price']) ?> đ</td>
                </tr>
            <?php endwhile; ?>
        </table>

        <?php
        /* =======================
           CẬP NHẬT ĐƠN HÀNG
        ======================= */
    elseif ($action === 'update' && $id):

        $od = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT * FROM orders WHERE id=$id")
        );
        ?>
        <h4>✏️ Cập nhật đơn hàng #<?= $id ?></h4>

        <form id="updateOrderForm" class="card p-3 w-50" data-id="<?= $id ?>">

            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="0" <?= $od['status'] == 0 ? 'selected' : '' ?>>Chờ xác nhận</option>
                <option value="1" <?= $od['status'] == 1 ? 'selected' : '' ?>>Đang chuẩn bị</option>
                <option value="2" <?= $od['status'] == 2 ? 'selected' : '' ?>>Đang giao</option>
                <option value="3" <?= $od['status'] == 3 ? 'selected' : '' ?>>Đã giao</option>
                <option value="4" <?= $od['status'] == 4 ? 'selected' : '' ?>>Huỷ</option>
            </select>

            <label class="form-label mt-3">Shipper</label>
            <input type="text" name="shipper_name" class="form-control"
                value="<?= htmlspecialchars($od['shipper_name'] ?? '') ?>">

            <label class="form-label mt-3">Thời gian bắt đầu giao</label>
            <input type="datetime-local" name="delivery_time" class="form-control"
                value="<?= $od['delivery_time'] ? date('Y-m-d\TH:i', strtotime($od['delivery_time'])) : '' ?>">

            <label class="form-label mt-3">ETA – Thời gian khách nhận (ước tính)</label>
            <input type="datetime-local" name="eta_time" class="form-control"
                value="<?= $od['eta_time'] ? date('Y-m-d\TH:i', strtotime($od['eta_time'])) : '' ?>">
            <label class="form-label mt-3">Thời gian khách nhận (thực tế)</label>
            <input type="datetime-local" name="delivered_at" class="form-control"
                value="<?= $od['delivered_at'] ? date('Y-m-d\TH:i', strtotime($od['delivered_at'])) : '' ?>">

            <?php if ($od['delivered_at']): ?>
                <div class="alert alert-success mt-3">
                    ✅ Đã giao lúc:
                    <b><?= date('d/m/Y H:i', strtotime($od['delivered_at'])) ?></b>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary mt-3">Lưu</button>
        </form>
    <?php endif; ?>
</div>