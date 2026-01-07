<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

$id = (int) ($_GET['id'] ?? 0);

$p = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM products WHERE id = $id")
);

if (!$p) {
    echo "<div class='text-danger'>Không tìm thấy sản phẩm</div>";
    exit;
}

/* =====================
   UPDATE PRODUCT
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $price = (int) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $recipe = trim($_POST['recipe'] ?? '');

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE products 
         SET price = ?, stock = ?, recipe = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iisi",
        $price,
        $stock,
        $recipe,
        $id
    );

    mysqli_stmt_execute($stmt);

    echo json_encode(['status' => 'success']);
    exit;
}
?>

<form id="editForm" class="bg-white p-4 rounded shadow" action="/web_cafe/admin/product_edit.php?id=<?= $id ?>">

    <h3 class="mb-3">✏️ <?= htmlspecialchars($p['name']) ?></h3>

    <!-- GIÁ -->
    <div class="mb-3">
        <label class="form-label">Giá bán (VNĐ)</label>
        <input class="form-control" name="price" value="<?= (int) $p['price'] ?>" required>
    </div>

    <!-- TỒN -->
    <div class="mb-3">
        <label class="form-label">Số lượng tồn</label>
        <input type="number" class="form-control" name="stock" value="<?= (int) $p['stock'] ?>">
    </div>
    <!-- CÔNG THỨC -->
    <div class="mb-3">
        <label class="form-label">Công thức pha</label>

        <div id="recipe-list"></div>

        <button type="button" id="btnAddRecipe" class="btn btn-sm btn-secondary mt-2">
            + Thêm nguyên liệu
        </button>

        <input type="hidden" name="recipe" id="recipeData" value="<?= htmlspecialchars($p['recipe'] ?? '') ?>">
    </div>

    <button class="btn btn-warning w-100">
        Cập nhật
    </button>
</form>