<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

/* =====================
   XỬ LÝ SUBMIT
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $price = (int) $_POST['price'];
    $category_id = (int) $_POST['category_id'];
    $recipe = trim($_POST['recipe'] ?? '');

    // ẢNH MẶC ĐỊNH
    $imageName = 'no_image.jpg';

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {

            $uploadDir = __DIR__ . '/../assets/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        }
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO products (name, price, image, category_id, recipe)
         VALUES (?,?,?,?,?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sisis",
        $name,
        $price,
        $imageName,
        $category_id,
        $recipe
    );

    mysqli_stmt_execute($stmt);

    echo json_encode([
        'status' => 'success',
        'message' => 'Thêm sản phẩm thành công'
    ]);
    exit;
}

/* =====================
   LOAD CATEGORY
===================== */
$cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY id ASC");
?>
<form id="addProductForm" method="POST" action="/web_cafe/admin/product_add.php" enctype="multipart/form-data">

    <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" class="form-control" name="name" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Giá (VNĐ)</label>
        <input type="number" class="form-control" name="price" required>
    </div>

    <!-- CÔNG THỨC -->
    <div class="mb-3">
        <label class="form-label">Công thức pha</label>

        <div id="recipe-list">
            <div class="row mb-2 recipe-row align-items-center">
                <div class="col-4">
                    <input type="text" class="form-control" placeholder="Nguyên liệu">
                </div>
                <div class="col-3">
                    <input type="number" class="form-control" placeholder="Số lượng">
                </div>
                <div class="col-3">
                    <select class="form-select">
                        <option value="ml">ml</option>
                        <option value="g">g</option>
                    </select>
                </div>
                <div class="col-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-recipe">
                        ❌
                    </button>
                </div>
            </div>
        </div>

        <button type="button" id="btnAddRecipe" class="btn btn-sm btn-secondary mt-2">
            + Thêm nguyên liệu
        </button>


        <input type="hidden" name="recipe" id="recipeData">
    </div>

    <!-- CATEGORY -->
    <div class="mb-3">
        <label class="form-label">Phân loại</label>
        <select name="category_id" class="form-control" required>
            <option value="">-- Chọn loại --</option>
            <?php while ($c = mysqli_fetch_assoc($cats)): ?>
                <option value="<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- IMAGE -->
    <div class="mb-3">
        <label class="form-label">Ảnh sản phẩm</label>
        <input type="file" class="form-control" name="image" accept="image/*">
        <small class="text-muted">Không chọn → dùng ảnh mặc định</small>
    </div>

    <button class="btn btn-primary w-100">Lưu sản phẩm</button>
</form>