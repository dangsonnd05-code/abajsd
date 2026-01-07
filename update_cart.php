<?php
session_start();

$id = $_POST['id'];
$change = $_POST['change'];

if (!isset($_SESSION['cart'][$id]))
    exit;

$_SESSION['cart'][$id] += $change;

if ($_SESSION['cart'][$id] <= 0) {
    unset($_SESSION['cart'][$id]);
}

echo "OK";
