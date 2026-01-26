<?php
// config/app/siswa/proses_siswa.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$path_db = __DIR__ . '/../../database.php';
if (!file_exists($path_db)) {
    die("FATAL ERROR: File database tidak ditemukan.");
}
require_once $path_db;

if (!isset($pdo)) {
    die("FATAL ERROR: Koneksi database gagal (\$pdo not found).");
}

$aksi = $_GET['aksi'] ?? '';

try {
    // --- FUNGSI TAMBAH ---
    if ($aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $nis = trim($_POST['nis'] ?? '');
        $nisn = trim($_POST['nisn'] ?? '');
        $nama = trim($_POST['nama_lengkap'] ?? '');
        $jk = $_POST['jk'] ?? '';
        $kelas = $_POST['kelas_sekarang'] ?? '';
        // 1. TANGKAP DATA TAHUN MASUK
        $tahun_masuk = $_POST['tahun_masuk'] ?? date('Y'); 
        $tgl_lahir = $_POST['tgl_lahir'] ?? '';

        if(empty($nisn) || empty($nama)) {
            die("Error: NISN dan Nama tidak boleh kosong.");
        }

        $check = $pdo->prepare("SELECT id FROM siswa WHERE nisn = ?");
        $check->execute([$nisn]);
        
        if ($check->rowCount() > 0) {
            header("Location: ../../../views/siswa/tambah.php?pesan=nisn_duplicate");
            exit();
        }

        // 2. UPDATE QUERY INSERT (Tambahkan kolom tahun_masuk)
        $sql = "INSERT INTO siswa (nis, nisn, nama_lengkap, jk, kelas_sekarang, tahun_masuk, tgl_lahir) 
                VALUES (:nis, :nisn, :nama, :jk, :kelas, :tahun_masuk, :tgl_lahir)";
        
        $stmt = $pdo->prepare($sql);
        
        // 3. UPDATE EKSEKUSI
        $simpan = $stmt->execute([
            'nis' => $nis,
            'nisn' => $nisn,
            'nama' => $nama,
            'jk' => $jk,
            'kelas' => $kelas,
            'tahun_masuk' => $tahun_masuk, // Masukkan ke array
            'tgl_lahir' => $tgl_lahir
        ]);

        if ($simpan) {
            header("Location: ../../../views/siswa/index.php?pesan=sukses_tambah");
            exit();
        } else {
            $errorInfo = $stmt->errorInfo();
            die("GAGAL SIMPAN SQL: " . $errorInfo[2]);
        }
    }

    // --- FUNGSI EDIT ---
    elseif ($aksi == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $nis = trim($_POST['nis']);
        $nisn = trim($_POST['nisn']);
        $nama = trim($_POST['nama_lengkap']);
        $jk = $_POST['jk'];
        $kelas = $_POST['kelas_sekarang'];
        // 1. TANGKAP DATA TAHUN MASUK
        $tahun_masuk = $_POST['tahun_masuk'];
        $tgl_lahir = $_POST['tgl_lahir'];

        $check = $pdo->prepare("SELECT id FROM siswa WHERE nisn = ? AND id != ?");
        $check->execute([$nisn, $id]);

        if ($check->rowCount() > 0) {
            header("Location: ../../../views/siswa/edit.php?id=$id&pesan=nisn_duplicate");
            exit();
        }

        // 2. UPDATE QUERY UPDATE
        $sql = "UPDATE siswa SET nis=?, nisn=?, nama_lengkap=?, jk=?, kelas_sekarang=?, tahun_masuk=?, tgl_lahir=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        
        // 3. UPDATE EKSEKUSI
        $update = $stmt->execute([$nis, $nisn, $nama, $jk, $kelas, $tahun_masuk, $tgl_lahir, $id]);

        if ($update) {
            header("Location: ../../../views/siswa/index.php?pesan=sukses_edit");
        } else {
            $errorInfo = $stmt->errorInfo();
            die("GAGAL UPDATE SQL: " . $errorInfo[2]);
        }
    }

    // --- FUNGSI HAPUS ---
    elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        $sql = "DELETE FROM siswa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $hapus = $stmt->execute([$id]);

        if ($hapus) {
            header("Location: ../../../views/siswa/index.php?pesan=sukses_hapus");
        } else {
            $errorInfo = $stmt->errorInfo();
            die("GAGAL HAPUS SQL: " . $errorInfo[2]);
        }
    }

} catch (PDOException $e) {
    die("<h1>ERROR DATABASE FATAL:</h1> " . $e->getMessage());
} catch (Exception $e) {
    die("<h1>ERROR SYSTEM:</h1> " . $e->getMessage());
}
?>