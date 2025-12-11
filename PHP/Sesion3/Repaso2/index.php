<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        // Admin ise yönetim paneline
        header('Location: admin_dashboard.php');
        exit;
    } else {
        // Client ise kendi profiline
        header('Location: profile.php');
        exit;
    }
} else {
    // Oturum yoksa giriş sayfasına
    header('Location: login.php');
    exit;
}
?>