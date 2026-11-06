<?php
require_once __DIR__ . '/../config/security.php';

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}

function require_admin() {
    require_login();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: dashboard.php?error=unauthorized");
        exit();
    }
}
?>