<!-- SẢN PHẨM -->
<div class="menu-group">
    <div class="menu-parent" onclick="toggleMenu('productMenu')">
        <span>📦 Sản phẩm</span>
    </div>
    <div class="menu-child" id="productMenu">
        <a onclick="loadPage('san_pham.php')">Danh sách</a>
        <a onclick="loadPage('product_add.php')">Thêm sản phẩm</a>
    </div>
</div>

<!-- NGUYÊN LIỆU -->
<div class="menu-group">
    <div class="menu-parent" onclick="toggleMenu('ingredientMenu')">
        <span>🥛 Nguyên liệu</span>
    </div>
    <div class="menu-child" id="ingredientMenu">
        <a onclick="loadPage('ingredients.php')">Danh sách</a>
        <a onclick="loadPage('ingredient_add.php')">Nhập kho</a>
        <a onclick="loadPage('stock_history.php')">Lịch sử</a>
    </div>
</div>

<!-- ĐƠN HÀNG -->
<a onclick="loadPage('donhang.php')">🧾 Đơn hàng</a>

<!-- THỐNG KÊ -->
<div class="menu-group">
    <div class="menu-parent" onclick="toggleMenu('statsMenu')">
        <span>📊 Thống kê</span>
    </div>
    <div class="menu-child" id="statsMenu">
        <a onclick="loadPage('stats_category.php')">Theo danh mục</a>
        <a onclick="loadPage('stats_revenue.php')">Doanh thu</a>
    </div>
</div>

<!-- DANH MỤC -->
<a onclick="loadPage('categories.php')">📁 Danh mục</a>