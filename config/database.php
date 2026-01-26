<?php
// config/database.php

$host = 'localhost';
$dbname = 'db_tu_smp';
$username = 'admin';      // Sesuaikan dengan user database lokal Anda
$password = 'smpn1c';          // Sesuaikan dengan password database lokal Anda

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Konfigurasi Error Mode ke Exception (Penting untuk debugging)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Default fetch mode ke Associative Array (agar hasil query jadi array ['nama' => 'Budi'])
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Non-aktifkan emulasi prepared statements (Security: Mencegah celah injeksi tingkat lanjut)
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    // Jika gagal, stop aplikasi dan tampilkan pesan error sederhana
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>