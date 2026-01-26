<?php
// config/session_check.php

// 1. Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Cek apakah ada data user_id di session
if (!isset($_SESSION['user_id'])) {
    // KEAMANAN: Gunakan path absolut agar tidak error "views/views"
    // Pastikan folder proyek Anda namanya 'sims-tu' sesuai URL yang Anda kirim
    header("Location: /sims-tu/views/auth/login.php?pesan=belum_login");
    exit();
}
?>