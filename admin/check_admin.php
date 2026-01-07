<?php
require_once 'check_auth.php';

if ($_SESSION['auth']['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>