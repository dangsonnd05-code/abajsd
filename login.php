<?php
require_once __DIR__ . '/config.php';

// Nếu đã đăng nhập
if (isset($_SESSION['username'])) {
    header("Location: /web_cafe/index.php");
    exit;
}

$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 1)
        $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
    if ($_GET['error'] == 2)
        $error = "Vui lòng nhập đầy đủ thông tin.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        header("Location: login.php?error=2");
        exit;
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? LIMIT 1");
    if (!$stmt) {
        die("SQL error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // 👉 SO SÁNH MẬT KHẨU
    if ($user && trim($password) == trim($user['password'])) {

        $_SESSION['username'] = $user['username']; // 🔥 BẮT BUỘC

        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];

        if ($user['role'] === 'admin') {
            header("Location: /web_cafe/admin/dashboard.php");
        } else {
            header("Location: /web_cafe/index.php");
        }
        exit;
    }

    header("Location: login.php?error=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Web Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .login-header {
            background: #6f4e37;
            color: white;
            padding: 35px;
            text-align: center;
        }

        .brand-logo {
            font-size: 2.5rem;
            color: #c9a769;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                        <h2 class="brand-logo mb-0">Web Cafe</h2>
                        <p class="mb-0 opacity-75">Đăng nhập hệ thống</p>
                    </div>
                    <div class="p-4 p-md-5">

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="/web_cafe/login.php">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Tên đăng nhập</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i
                                            class="fas fa-user text-muted"></i></span>
                                    <input type="text" class="form-control" name="username"
                                        placeholder="Nhập tên đăng nhập..." required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i
                                            class="fas fa-lock text-muted"></i></span>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Nhập mật khẩu..." required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"
                                style="background-color: #007bff; border: none;">
                                ĐĂNG NHẬP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>