<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Cấm truy cập");
}

$kq = mysqli_query($conn, "SELECT * FROM users WHERE role='user'");
?>

<div class="container mt-5">


    <h2 class="mb-4">👤 Quản lý khách hàng</h2>

    <div class="card shadow p-4 bg-white">

        <table class="table table-bordered table-hover text-center">
            <tr>
                <th width="60">ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th width="100">Xoá</th>
            </tr>

            <?php while ($u = mysqli_fetch_assoc($kq)): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <a href="#" class="btn btn-danger btn-sm" onclick="deleteUser(<?= $u['id'] ?>)">
                            🗑 Xoá
                        </a>

                    </td>
                </tr>
            <?php endwhile; ?>

        </table>

    </div>
</div>