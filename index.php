<?php
// index.php
session_start();

// Jika sudah login, lempar ke dashboard admin
if (isset($_SESSION['user_id'])) {
    header('Location: views/dashboard/index.php');
    exit;
}

// Jika belum login, lempar ke halaman Public View
header('Location: views/public_view/index.php');
exit;
?>