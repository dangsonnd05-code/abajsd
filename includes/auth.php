<?php
// includes/auth.php

// Kiểm tra admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

// Kiểm tra đã đăng nhập
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Chuyển hướng nếu không phải admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: ../admin/login.php");
        exit();
    }
}

// Chuyển hướng nếu chưa đăng nhập
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../user/login.php");
        exit();
    }
}

// Chuyển hướng nếu đã đăng nhập
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        if (isAdmin()) {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../user/index.php");
        }
        exit();
    }
}
?>