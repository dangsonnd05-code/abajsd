<?php
require_once __DIR__ . '/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $email === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin';
    } else {
        // kiểm tra trùng username
        $check = mysqli_query(
            $conn,
            "SELECT id FROM users WHERE username='$username' LIMIT 1"
        );

        if (mysqli_num_rows($check) > 0) {
            $error = 'Tên đăng nhập đã tồn tại';
        } else {
            // KHÔNG HASH PASSWORD
            mysqli_query(
                $conn,
                "INSERT INTO users(username,email,password,role)
                 VALUES('$username','$email','$password','user')"
            );

            $success = 'Đăng ký thành công, bạn có thể đăng nhập';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(rgba(0,0,0,.5),rgba(0,0,0,.5)),
    url("https://images.unsplash.com/photo-1509042239860-f550ce710b93");
    background-size:cover;
}
.card{border-radius:20px}
</style>
</head>
<body>

<div class="card p-4 shadow" style="width:400px">
    <h3 class="text-center mb-3">📝 Đăng ký</h3>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Tên đăng nhập</label>
            <input class="form-control" name="username" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label>Mật khẩu</label>
            <input type="text" class="form-control" name="password" required>
        </div>

        <button class="btn btn-primary w-100">Đăng ký</button>
    </form>

    <div class="text-center mt-3">
        <a href="login.php">Đã có tài khoản? Đăng nhập</a>
    </div>
</div>

</body>
</html>
