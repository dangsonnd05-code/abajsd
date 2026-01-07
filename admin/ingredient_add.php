<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $qty = (int) $_POST['quantity'];
    $unit = $_POST['unit'];

    $cost_per_unit = (int) $_POST['cost_per_unit'];
    $total_cost = $cost_per_unit * $qty;


    // 1️⃣ Kiểm tra nguyên liệu tồn tại chưa
    $rs = mysqli_query($conn, "SELECT * FROM ingredients WHERE name='$name'");
    $ingredient = mysqli_fetch_assoc($rs);

    if ($ingredient) {
        // Cộng tồn kho
        mysqli_query(
            $conn,
            "UPDATE ingredients 
     SET 
        quantity = quantity + $qty,
        cost_per_unit = $cost_per_unit
     WHERE id = {$ingredient['id']}"
        );

        $ingredient_id = $ingredient['id'];
    } else {
        // Tạo mới nguyên liệu
        mysqli_query(
            $conn,
            "INSERT INTO ingredients(name, quantity, unit, cost_per_unit)
VALUES('$name', $qty, '$unit', $cost_per_unit)"
        );
        $ingredient_id = mysqli_insert_id($conn);
    }

    // 2️⃣ Ghi lịch sử nhập
    mysqli_query(
        $conn,
        "INSERT INTO stock_history
        (ingredient_id, ingredient_name, change_qty, unit, cost_per_unit, total_cost, type)
        VALUES
        ($ingredient_id, '$name', $qty, '$unit', $cost_per_unit, $total_cost, 'in')"
    );

    echo json_encode(['status' => 'success']);
    exit;
}
?>

<form id="ingredientForm" method="post" action="/web_cafe/admin/ingredient_add.php" class="bg-white p-4 rounded shadow">

    <h4>Nhập nguyên liệu</h4>

    <input name="name" class="form-control mb-3" placeholder="Tên nguyên liệu" required>
    <input name="quantity" type="number" class="form-control mb-3" placeholder="Số lượng" required>
    <input name="cost_per_unit" type="number" class="form-control mb-3" placeholder="Giá / đơn vị (VNĐ)" required>


    <select name="unit" class="form-control mb-3">
        <option value="ml">ml</option>
        <option value="g">g</option>
    </select>

    <button class="btn btn-primary w-100">Lưu</button>
</form>