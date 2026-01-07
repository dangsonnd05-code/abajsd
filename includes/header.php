<?php
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<header class="header">
    <div class="container">
        <div class="header-left">
            <a href="index.php" class="logo">
                <i class="fas fa-coffee"></i>
                <div>
                    <h1>Coffee House</h1>
                    <p class="slogan">Đậm đà – Nguyên chất – Chuẩn vì Việt</p>
                </div>
            </a>
        </div>
        
        <div class="header-right">
            <nav class="main-nav">
                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
                <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-coffee"></i> Sản phẩm
                </a>
                <a href="cart.php" class="cart-link <?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i> Giỏ hàng
                    <?php if($cart_count > 0): ?>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown-container">
                        <button class="user-btn">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown">
                            <?php if($_SESSION['user_role'] == 'admin'): ?>
                                <a href="admin/index.php"><i class="fas fa-cog"></i> Admin Panel</a>
                            <?php endif; ?>
                            <a href="user/profile.php"><i class="fas fa-user-circle"></i> Tài khoản</a>
                            <a href="user/orders.php"><i class="fas fa-receipt"></i> Đơn hàng</a>
                            <a href="user/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <a href="user/login.php" class="btn btn-login">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                        <a href="user/register.php" class="btn btn-register">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>