<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin - Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            display: flex;
            min-height: 100vh;
            background: #f4f6f9;
        }

        .sidebar {
            width: 260px;
            background: #1f2937;
            min-height: 100vh;
            padding-top: 15px;
            color: #fff;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #cbd5e1;
            text-decoration: none;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background: #374151;
            color: #fff;
        }

        .menu-group {
            margin-top: 5px;
        }

        .menu-parent {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            cursor: pointer;
            color: #cbd5e1;
        }

        .menu-parent:hover {
            background: #374151;
            color: #fff;
        }

        .menu-child {
            display: none;
            background: #111827;
        }

        .menu-child a {
            padding-left: 45px;
            font-size: 14px;
        }

        .arrow {
            transition: 0.3s;
        }

        .arrow.rotate {
            transform: rotate(90deg);
        }


        /* MAIN */
        .main {
            flex: 1;
            padding: 25px;
        }

        /* PRODUCT */
        .card img {
            height: 180px;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- DASHBOARD -->
        <a href="/web_cafe/admin/dashboard.php" class="menu-item">
            <i class="fa fa-home"></i>
            <span>Dashboard</span>
        </a>

        <!-- ================= SẢN PHẨM ================= -->
        <div class="menu-group">
            <div class="menu-parent" onclick="toggleMenu('productMenu')">
                <div>
                    <i class="fa fa-box"></i>
                    <span>Sản phẩm</span>
                </div>
                <i class="fa fa-chevron-right arrow" id="arrow-productMenu"></i>
            </div>

            <div class="menu-child" id="productMenu">
                <a href="#" onclick="loadPage('/web_cafe/admin/san_pham.php','Danh sách sản phẩm')">
                    <i class="fa fa-list"></i> Danh sách
                </a>
                <a href="#" onclick="loadPage('/web_cafe/admin/product_add.php','Thêm sản phẩm')">
                    <i class="fa fa-plus"></i> Thêm sản phẩm
                </a>
            </div>
        </div>

        <!-- ================= NGUYÊN LIỆU ================= -->
        <div class="menu-group">
            <div class="menu-parent" onclick="toggleMenu('ingredientMenu')">
                <div>
                    <i class="fa fa-flask"></i>
                    <span>Nguyên liệu</span>
                </div>
                <i class="fa fa-chevron-right arrow" id="arrow-ingredientMenu"></i>
            </div>

            <div class="menu-child" id="ingredientMenu">
                <a href="#" onclick="loadPage('/web_cafe/admin/ingredients.php','Nguyên liệu')">
                    <i class="fa fa-list"></i> Danh sách
                </a>
                <a href="#" onclick="loadPage('/web_cafe/admin/ingredient_add.php','Nhập kho')">
                    <i class="fa fa-plus"></i> Nhập kho
                </a>
                <a href="#" onclick="loadPage('/web_cafe/admin/stock_history.php','Lịch sử kho')">
                    <i class="fa fa-clock"></i> Lịch sử
                </a>
            </div>
        </div>

        <!-- ================= ĐƠN HÀNG ================= -->
        <a href="#" onclick="loadPage('/web_cafe/admin/donhang.php','Đơn hàng')">
            <i class="fa fa-receipt"></i>
            <span>Đơn hàng</span>
        </a>

        <!-- ================= THỐNG KÊ ================= -->
        <div class="menu-group">
            <div class="menu-parent" onclick="toggleMenu('statsMenu')">
                <div>
                    <i class="fa fa-chart-bar"></i>
                    <span>Thống kê</span>
                </div>
                <i class="fa fa-chevron-right arrow" id="arrow-statsMenu"></i>
            </div>

            <div class="menu-child" id="statsMenu">
                <a href="#" onclick="openRevenueToday()">
                    <i class="fa fa-dollar-sign"></i> Doanh thu
                </a>

            </div>
        </div>
        <!-- ================= NGƯỜI DÙNG ================= -->
        <a href="#" onclick="loadPage('/web_cafe/admin/khachhang.php','Người dùng')">
            <i class="fa fa-users"></i>
            <span>Người dùng</span>
        </a>

        <!-- ================= ĐĂNG XUẤT ================= -->
        <a href="/web_cafe/logout.php" class="text-danger mt-3">
            <i class="fa fa-sign-out-alt me-2"></i> Đăng xuất
        </a>

    </div>

    <div class="main">
        <!-- TOP BAR -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 id="page-title">Quản lý</h3>
            <span>Xin chào, <b><?= htmlspecialchars($_SESSION['user']['username']) ?></b></span>
        </div>
        <!-- 🔥 KHUNG LOAD NỘI DUNG -->
        <div id="content">
        </div>
    </div>
    <script>
        /* ===============================
           MENU
        ================================ */
        function toggleMenu(id) {
            const menu = document.getElementById(id);
            const arrow = document.getElementById('arrow-' + id);

            if (menu.style.display === 'block') {
                menu.style.display = 'none';
                arrow.classList.remove('rotate');
            } else {
                menu.style.display = 'block';
                arrow.classList.add('rotate');
            }
        }

        /* ===============================
           LOAD PAGE (AJAX)
        ================================ */
        function loadPage(url, title = '') {
            document.getElementById('page-title').innerText = title;

            fetch(url)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('content').innerHTML = html;
                    initRecipeFromHidden(); // ✅ OK
                })
                .catch(() => {
                    document.getElementById('content').innerHTML =
                        '<div class="text-danger">Không tải được dữ liệu</div>';
                });
        }

        /* ===============================
           ADD RECIPE ROW (GLOBAL)
        ================================ */
        function addRecipeRow(name = '', qty = '', unit = 'ml') {

            const list = document.getElementById('recipe-list');
            if (!list) return;

            const row = document.createElement('div');
            row.className = 'row mb-2 recipe-row align-items-center';

            row.innerHTML = `
        <div class="col-4">
            <input type="text" class="form-control"
                   data-field="name"
                   value="${name}"
                   placeholder="Nguyên liệu">
        </div>
        <div class="col-3">
            <input type="number" class="form-control"
                   data-field="qty"
                   value="${qty}"
                   placeholder="Số lượng">
        </div>
        <div class="col-3">
            <select class="form-select" data-field="unit">
                <option value="ml" ${unit === 'ml' ? 'selected' : ''}>ml</option>
                <option value="g" ${unit === 'g' ? 'selected' : ''}>g</option>
            </select>
        </div>
        <div class="col-2 text-end">
            <button type="button"
                    class="btn btn-danger btn-sm btn-remove-recipe">❌</button>
        </div>
    `;
            list.appendChild(row);
        }

        /* ===============================
           INIT RECIPE (EDIT PAGE)
        ================================ */
        function initRecipeFromHidden() {

            const list = document.getElementById('recipe-list');
            const recipeInput = document.getElementById('recipeData');

            if (!list || !recipeInput) return;

            list.innerHTML = '';

            const text = recipeInput.value.trim();

            if (text === '') {
                addRecipeRow();
                return;
            }

            text.split('\n').forEach(line => {
                const [name, qty, unit] = line.split('|');
                addRecipeRow(name, qty, unit || 'ml');
            });
        }

        /* ===============================
           ADD / REMOVE RECIPE
        ================================ */
        document.addEventListener('click', function (e) {

            if (e.target.id === 'btnAddRecipe') {
                addRecipeRow();
            }

            if (e.target.classList.contains('btn-remove-recipe')) {
                e.target.closest('.recipe-row')?.remove();
            }
        });

        /* ===============================
           SUBMIT ADD + EDIT PRODUCT
        ================================ */
        document.addEventListener('submit', function (e) {

            if (!e.target.matches('#addProductForm, #editForm')) return;

            e.preventDefault();

            let recipe = [];
            document.querySelectorAll('.recipe-row').forEach(row => {
                const name = row.querySelector('[data-field="name"]')?.value.trim();
                const qty = row.querySelector('[data-field="qty"]')?.value.trim();
                const unit = row.querySelector('[data-field="unit"]')?.value;

                if (name && qty) {
                    recipe.push(`${name}|${qty}|${unit}`);
                }
            });

            const recipeInput = e.target.querySelector('#recipeData');
            if (recipeInput) {
                recipeInput.value = recipe.join('\n');
            }

            fetch(e.target.action, {
                method: 'POST',
                body: new FormData(e.target)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('✔️ Lưu thành công');
                        loadPage('/web_cafe/admin/san_pham.php', 'Danh sách sản phẩm');
                    }
                })
                .catch(() => alert('Lỗi server'));
        });
        document.addEventListener('click', function (e) {

            if (!e.target.classList.contains('btn-delete')) return;

            const id = e.target.dataset.id;
            if (!id) return;

            if (!confirm('❗ Bạn chắc chắn muốn xoá sản phẩm này?')) return;

            fetch('/web_cafe/admin/product_delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('🗑 Đã xoá');
                        loadPage('/web_cafe/admin/san_pham.php', 'Danh sách sản phẩm');
                    } else {
                        alert('❌ Không xoá được');
                    }
                })
                .catch(() => alert('Lỗi server'));
        });
    </script>
    <script>
        document.addEventListener('submit', function (e) {

            if (e.target.id !== 'ingredientForm') return;

            e.preventDefault(); // 🔥 CỰC KỲ QUAN TRỌNG

            fetch('/web_cafe/admin/ingredient_add.php', {
                method: 'POST',
                body: new FormData(e.target)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('✔ Nhập kho thành công');
                        loadPage('/web_cafe/admin/ingredients.php', 'Danh sách nguyên liệu');
                    } else {
                        alert('❌ Lỗi nhập kho');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Lỗi server');
                });
        });
        document.addEventListener('click', function (e) {

            if (!e.target.classList.contains('btn-delete-ingredient')) return;

            const id = e.target.dataset.id;
            if (!id) return;

            if (!confirm('❗ Bạn chắc chắn muốn xoá nguyên liệu này?')) return;

            fetch('/web_cafe/admin/ingredient_delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('🗑 Đã xoá nguyên liệu');
                        loadPage('/web_cafe/admin/ingredients.php', 'Nguyên liệu');
                    } else {
                        alert(data.message || '❌ Xoá thất bại');
                    }
                })
                .catch(() => alert('❌ Lỗi server'));
        });
    </script>
    <script>
        document.addEventListener('submit', function (e) {

            if (e.target.id !== 'updateOrderForm') return;

            e.preventDefault();

            const form = e.target;
            const id = form.dataset.id;

            fetch(`/web_cafe/admin/donhang.php?action=update&id=${id}`, {
                method: 'POST',
                body: new FormData(form)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('✔️ Cập nhật đơn hàng thành công');
                        loadPage('/web_cafe/admin/donhang.php', 'Quản lý đơn hàng');
                    } else {
                        alert('❌ Lỗi: ' + (data.msg || 'Không xác định'));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('❌ Lỗi server');
                });
        });
    </script>
    <script>
        function deleteOrder(id) {
            if (!confirm('⚠️ Bạn có chắc chắn muốn xoá đơn hàng #' + id + ' ?\nHành động này KHÔNG THỂ hoàn tác!')) {
                return;
            }

            fetch('/web_cafe/admin/donhang.php?action=delete&id=' + id, {
                method: 'POST'
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('✅ Đã xoá đơn hàng');
                        loadPage('/web_cafe/admin/donhang.php', 'Quản lý đơn hàng');
                    } else {
                        alert('❌ ' + data.msg);
                    }
                });
        }
    </script>
    <script>
        function openRevenueToday() {
            const today = new Date().toISOString().slice(0, 10);

            loadPage(
                '/web_cafe/admin/stats_revenue.php?date=' + today,
                'Doanh thu'
            );
        }
    </script>

</body>

</html>