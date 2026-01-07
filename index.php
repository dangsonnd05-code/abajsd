<?php
session_start();

$isLogin = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Cafe - Thế giới cà phê nguyên bản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #6f4e37;
            --secondary-color: #c9a769;
            --light-color: #f8f5f0;
            --dark-color: #3e2723;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
        }

        .cafe-header {
            background: linear-gradient(rgba(62, 39, 35, 0.9), rgba(62, 39, 35, 0.9)),
                url('https://images.unsplash.com/photo-1498804103079-a6351b050096?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .brand-logo {
            font-family: 'Dancing Script', cursive;
            font-size: 3.5rem;
            color: var(--secondary-color);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .tagline {
            font-size: 1.2rem;
            color: var(--secondary-color);
            margin-bottom: 30px;
        }

        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            background: white;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .product-price {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.3rem;
        }

        .product-category {
            background: var(--secondary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            position: absolute;
            top: 15px;
            left: 15px;
        }

        .add-to-cart {
            background: var(--primary-color);
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .add-to-cart:hover {
            background: var(--dark-color);
            transform: scale(1.05);
        }

        .cart-badge {
            background: var(--secondary-color);
            color: var(--dark-color);
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .nav-cafe {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-link-cafe {
            color: var(--dark-color) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .nav-link-cafe:hover {
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(rgba(111, 78, 55, 0.1), rgba(111, 78, 55, 0.1)),
                url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            text-align: center;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 40px;
            position: relative;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
        }

        .feature-box {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .weather-widget {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }

        .temperature {
            font-size: 3rem;
            font-weight: bold;
        }

        .footer-cafe {
            background: var(--dark-color);
            color: white;
            padding: 60px 0 30px;
            margin-top: 80px;
        }

        .social-icons a {
            color: var(--secondary-color);
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: white;
        }

        .opening-hours {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 380px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            transition: right 0.3s;
            z-index: 1050;
            overflow-y: auto;
        }

        .cart-sidebar.show {
            right: 0;
        }

        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }

        .product-stock {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
        }

        .stock-ok {
            background: #28a745;
        }

        .stock-out {
            background: #dc3545;
        }

        .cart-overlay.show {
            display: block;
        }
    </style>
</head>

<body>
    <!-- Cart Overlay -->
    <div class="cart-overlay" id="cartOverlay"></div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-shopping-cart me-2"></i> Giỏ hàng</h4>
                <button class="btn btn-close" onclick="closeCart()"></button>
            </div>
            <div id="cartContent">
                <!-- Cart items will be loaded here -->
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Giỏ hàng trống</p>
                </div>
            </div>
            <div class="cart-total mt-4 pt-4 border-top">
                <div class="d-flex justify-content-between mb-3">
                    <span>Tổng tiền:</span>
                    <span id="cartTotal">0 đ</span>
                </div>
                <button class="btn btn-primary w-100" onclick="checkout()">Thanh toán</button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="cafe-header">
        <div class="container">
            <h1 class="brand-logo mb-3">Kioh Cafe</h1>
            <p class="tagline">Hương vị nguyên bản - Cảm xúc thuần khiết</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg"
                            placeholder="Tìm kiếm đồ uống yêu thích...">
                        <button class="btn btn-warning btn-lg"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light nav-cafe sticky-top">
        <div class="container">
            <a class="navbar-brand d-lg-none" href="#">
                <span class="brand-logo" style="font-size: 2rem;">Kioh Cafe</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-link-cafe active" href="#"><i class="fas fa-home me-1"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-cafe" href="#"><i class="fas fa-coffee me-1"></i> Thực đơn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-cafe" href="#"><i class="fas fa-store me-1"></i> Cửa hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-cafe" href="#"><i class="fas fa-info-circle me-1"></i> Giới
                            thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-cafe" href="#"><i class="fas fa-phone me-1"></i> Liên hệ</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-primary position-relative me-3" onclick="openCart()">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge position-absolute top-0 start-100 translate-middle">0</span>
                    </button>
                    <?php if (isset($_SESSION['username'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> <?php echo $_SESSION['username']; ?>
                            </button>
                            <ul class="dropdown-menu">
                                <hr class="dropdown-divider">
                                <li>
                                    <a class="dropdown-item" href="order_history.php">
                                        <i class="fas fa-receipt me-2 text-primary"></i>
                                        Lịch sử đơn hàng
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-danger" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="d-flex align-items-center gap-3">
                            <a href="/web_cafe/login.php" class="btn btn-primary d-flex align-items-center gap-1 px-4">
                                <i class="fa fa-sign-in-alt"></i>
                                <span>Đăng nhập</span>
                            </a>

                            <a href="/web_cafe/register.php" class="btn btn-primary d-flex align-items-center gap-1 px-4">
                                <i class="fa fa-user-plus"></i>
                                <span>Đăng ký</span>
                            </a>
                        </div>


                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Weather Widget -->
    <div class="container mt-4">
        <div class="weather-widget">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h3><i class="fas fa-map-marker-alt me-2"></i> Hà Nội</h3>
                    <p>Hôm nay, <?php echo date('d/m/Y'); ?></p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="temperature">28°C</div>
                    <p>Có nắng</p>
                </div>
                <div class="col-md-4 text-end">
                    <i class="fas fa-sun fa-4x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center">THỰC ĐƠN CỦA CHÚNG TÔI</h2>
            <p class="text-center text-muted mb-5">Khám phá những hương vị đặc biệt từ những hạt cà phê nguyên bản</p>

            <!-- Category Filter -->
            <div class="text-center mb-5">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary active" data-filter="all">Tất cả</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="coffee">Cà phê</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="tea">Trà</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="juice">Nước ép</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="dessert">Bánh ngọt</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="non-caffeine">Non-cafein</button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row" id="productsGrid">
                <?php
                require_once __DIR__ . '/connect.php';

                $rs = mysqli_query($conn, "
    SELECT 
        p.id,
        p.name,
        p.price,
        p.image,
        p.stock,
        c.name AS category_id,
        c.name AS category_name,
        c.slug AS category_slug
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
");



                while ($product = mysqli_fetch_assoc($rs)):
                    ?>
                    <div class="col-md-3 mb-4 product-item"
                        data-category="<?= htmlspecialchars($product['category_slug'] ?? 'other') ?>">
                        <div class="product-card">
                            <div class="position-relative">

                                <?php
                                $image = !empty($product['image'])
                                    ? '/web_cafe/assets/images/' . $product['image']
                                    : '/web_cafe/assets/images/no_image.jpg';
                                ?>

                                <img src="<?= $image ?>" class="product-img"
                                    alt="<?= htmlspecialchars($product['name']) ?>">

                                <!-- CATEGORY -->
                                <span class="product-category">
                                    <?= htmlspecialchars($product['category_name'] ?? 'Khác') ?>
                                </span>

                                <!-- STOCK -->
                                <span class="product-stock <?= $product['stock'] > 0 ? 'stock-ok' : 'stock-out' ?>">
                                    <?= $product['stock'] > 0 ? 'Còn ' . $product['stock'] : 'Hết hàng' ?>
                                </span>

                            </div>

                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text text-muted">Hương vị đặc trưng, nguyên chất 100%</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="product-price">
                                        <?= number_format($product['price']) ?> đ
                                    </span>

                                    <?php if ($product['stock'] > 0): ?>
                                        <button class="btn add-to-cart text-white" onclick="addToCart(<?= $product['id'] ?>)">
                                            Thêm vào giỏ
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>
                                            Hết hàng
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">TẠI SAO CHỌN KIOH CAFE</h2>
            <div class="row mt-5">
                <div class="col-md-3 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-coffee"></i>
                        </div>
                        <h4>Cà phê nguyên chất</h4>
                        <p>100% Arabica & Robusta, rang xay tại chỗ</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h4>Giao hàng nhanh</h4>
                        <p>Miễn phí giao hàng trong 30 phút</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h4>Chất lượng 5 sao</h4>
                        <p>Được đánh giá cao bởi khách hàng</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h4>Ưu đãi hàng ngày</h4>
                        <p>Giảm giá cho khách hàng thân thiết</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-cafe">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3 class="brand-logo mb-3">Kioh Cafe</h3>
                    <p>Mang đến hương vị cà phê nguyên bản và không gian thư giãn tuyệt vời.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h4 class="mb-4">Liên hệ</h4>
                    <p><i class="fas fa-map-marker-alt me-2"></i> 123 Đường Cà Phê, Hà Nội</p>
                    <p><i class="fas fa-phone me-2"></i> (024) 1234 5678</p>
                    <p><i class="fas fa-envelope me-2"></i> info@kiohcafe.com</p>
                    <div class="opening-hours">
                        <h6><i class="fas fa-clock me-2"></i> Giờ mở cửa</h6>
                        <p class="mb-1">Thứ 2 - Thứ 6: 7:00 - 22:00</p>
                        <p class="mb-1">Thứ 7 - CN: 8:00 - 23:00</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h4 class="mb-4">Đăng ký nhận tin</h4>
                    <p>Nhận thông tin khuyến mãi và sản phẩm mới</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email của bạn">
                        <button class="btn btn-warning"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <div class="text-center pt-4 mt-4 border-top">
                <p>&copy; 2024 Kioh Cafe. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!-- AUTH MODAL -->
    <div class="modal fade" id="authModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yêu cầu đăng nhập</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Bạn cần đăng nhập để thêm vào giỏ hàng</p>
                    <a href="login.php" class="btn btn-primary w-100 mb-2">Đăng nhập</a>
                    <a href="register.php" class="btn btn-outline-primary w-100">Đăng ký</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const isLogin = <?= $isLogin ? 'true' : 'false' ?>;

        /* ===== CẬP NHẬT SỐ LƯỢNG GIỎ HÀNG ===== */
        function updateCartBadge() {
            fetch('/web_cafe/cart_count.php')
                .then(res => res.text())
                .then(count => {
                    document.querySelector('.cart-badge').textContent = count;
                });
        }
        /* ===== MỞ GIỎ HÀNG ===== */
        function openCart() {
            if (!isLogin) {
                new bootstrap.Modal(document.getElementById('authModal')).show();
                return;
            }

            document.getElementById('cartSidebar').classList.add('show');
            document.getElementById('cartOverlay').classList.add('show');
            loadCart();
        }
        function closeCart() {
            document.getElementById('cartSidebar').classList.remove('show');
            document.getElementById('cartOverlay').classList.remove('show');
        }
        document.getElementById('cartOverlay')
            .addEventListener('click', closeCart);


        /* ===== THÊM VÀO GIỎ (JS ĐÚNG) ===== */
        function addToCart(id) {
            if (!isLogin) {
                new bootstrap.Modal(document.getElementById('authModal')).show();
                return;
            }

            fetch('/web_cafe/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            })
                .then(res => res.text())
                .then(res => {
                    if (res === 'OK') {
                        updateCartBadge();
                        openCart();
                        loadCart();
                        showNotification('Đã thêm vào giỏ hàng!');
                    } else {
                        alert('Có lỗi xảy ra');
                    }
                })
                .catch(() => alert('Lỗi kết nối server'));
        }

        /* ===== THÔNG BÁO ===== */
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className =
                'position-fixed bottom-0 end-0 m-3 p-3 bg-success text-white rounded shadow';
            notification.style.zIndex = '1060';
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => notification.remove(), 2500);
        }

        /* ===== LOAD BADGE KHI VÀO TRANG ===== */
        updateCartBadge();
    </script>
    <script>
        document.querySelectorAll('[data-filter]').forEach(button => {
            button.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');

                // Đổi active button
                document.querySelectorAll('[data-filter]').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                // Lọc sản phẩm
                document.querySelectorAll('.product-item').forEach(item => {
                    const category = item.getAttribute('data-category');

                    if (filter === 'all' || category === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
    <script>
        function checkout() {
            window.location.href = '/web_cafe/checkout.php';
        }
    </script>
    <script>
        function updateQty(id, change) {
            fetch('/web_cafe/update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&change=${change}`
            }).then(() => {
                loadCart();
                updateCartBadge();
            });
        }

        function removeItem(id) {
            fetch('/web_cafe/remove_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}`
            }).then(() => {
                loadCart();
                updateCartBadge();
            });
        }
    </script>
    <script>
        function loadCart() {
            fetch('/web_cafe/cart.php')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('cartContent').innerHTML = html;
                });
        }

        // ❌ Xóa
        function removeItem(id) {
            fetch('/web_cafe/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=remove&id=${id}`
            }).then(() => {
                loadCart();
                updateCartBadge();
            });
        }

        // ➕ ➖
        function updateQty(id, delta) {
            fetch('/web_cafe/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=update&id=${id}&delta=${delta}`
            }).then(() => {
                loadCart();
                updateCartBadge();
            });
        }

        // ✏️ Nhập số lượng
        function setQty(id, qty) {
            if (qty < 1) qty = 1;

            fetch('/web_cafe/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=setQty&id=${id}&qty=${qty}`
            }).then(() => {
                loadCart();
                updateCartBadge();
            });
        }
    </script>

</body>

</html>