<nav class="navbar">
    <div class="logo">WEB CAFE</div>
    <ul>
        <li><a href="index.php">Trang chủ</a></li>
        <li><a href="index.php#menu">Menu</a></li>
        <li><a href="cart.php">Giỏ hàng</a></li>

        <?php if(isset($_SESSION['user'])): ?>
            <li><a href="auth/logout.php">Đăng xuất</a></li>
        <?php else: ?>
            <li><a href="auth/login.php">Đăng nhập</a></li>
        <?php endif; ?>
    </ul>
</nav>
