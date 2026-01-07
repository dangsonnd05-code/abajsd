<?php
require_once 'config.php';

$id = $_GET['id'];

if (isset($_POST['update'])) {
    mysqli_query($conn,
        "UPDATE products SET
         name='{$_POST['name']}',
         price='{$_POST['price']}',
         description='{$_POST['des']}'
         WHERE id=$id");

    header("Location: product.php");
}

$p = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM products WHERE id=$id"));
?>

<form method="post">
    <input name="name" value="<?= $p['name'] ?>">
    <input name="price" value="<?= $p['price'] ?>">
    <textarea name="des"><?= $p['description'] ?></textarea>
    <button name="update">Lưu</button>
</form>
