<?php
// setup_admin.php

// Panggil database sesuai struktur di gambar (folder config)
$path_db = 'config/database.php';

if (!file_exists($path_db)) {
    die("File database tidak ketemu. Pastikan file ini ada di folder root (luar), sejajar dengan folder config.");
}

require_once $path_db;

// Kita akan reset user 'admin'
$username = 'admin';
$password_baru = 'admin123'; 
// ENKRIPSI PASSWORD (Wajib!)
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

try {
    // Hapus user admin lama (biar bersih)
    $pdo->exec("DELETE FROM users WHERE username = 'admin'");

    // Buat user admin baru yang passwordnya sudah di-hash
    $sql = "INSERT INTO users (username, password, nama_lengkap, role, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $password_hash, 'Administrator Utama', 'admin', 'aktif']);

    echo "<h1>SUKSES!</h1>";
    echo "Akun Admin berhasil di-reset.<br>";
    echo "Username: <b>admin</b><br>";
    echo "Password: <b>admin123</b><br><br>";
    echo "Silakan <a href='views/auth/login.php'>LOGIN DISINI</a>";

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>