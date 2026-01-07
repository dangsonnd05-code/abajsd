<?php
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /web_cafe/login.php");
    exit;
}
?>

<div class="header">
<h1>Quản lý sản phẩm</h1>
<a href="them.php">➕ Thêm</a>
</div>

<div class="container">
<table>
<tr>
<th>ID</th><th>Tên</th><th>Giá</th><th>Hình</th><th>Hành động</th>
</tr>
<?php
require_once '../config.php';

$kq = mysqli_query($conn,"SELECT * FROM sanpham");
while($r = mysqli_fetch_assoc($kq)){
?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= $r['ten_sp'] ?></td>
<td><?= number_format($r['gia']) ?></td>
<td><img src="../images/<?= $r['hinh'] ?>" width="60"></td>
<td>
<a href="sua.php?id=<?= $r['id'] ?>">Sửa</a> |
<a href="xoa.php?id=<?= $r['id'] ?>">Xoá</a>
</td>
</tr>
<?php } ?>
</table>
</div>
