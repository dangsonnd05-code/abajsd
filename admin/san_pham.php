<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="container-fluid mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>📦 Danh sách sản phẩm</h4>

        </div>

        <!-- PRODUCT GRID -->
        <div class="row">
            <?php
            $rs = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
            while ($p = mysqli_fetch_assoc($rs)):

                $img = !empty($p['image'])
                    ? '/web_cafe/assets/images/' . $p['image']
                    : '/web_cafe/assets/images/no_image.jpg';
                ?>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card shadow h-100">

                        <img src="<?= htmlspecialchars($img) ?>" style="height:180px;object-fit:cover">

                        <div class="card-body">
                            <h6><?= htmlspecialchars($p['name']) ?></h6>

                            <p class="text-danger fw-bold">
                                <?= number_format($p['price']) ?> đ
                            </p>

                            <div class="d-flex gap-2">
                                <a href="#" onclick="loadPage('/web_cafe/admin/product_edit.php?id=<?= $p['id'] ?>')"
                                    class="btn btn-warning btn-sm">
                                    ✏️ Sửa
                                </a>

                                <button type="button" class="btn btn-danger btn-delete" data-id="<?= $p['id'] ?>">
                                    🗑 Xoá
                                </button>

                            </div>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    </div>
</body>

</html>