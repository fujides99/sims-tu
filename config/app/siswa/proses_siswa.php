<?php
// config/app/siswa/proses_siswa.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$path_db = __DIR__ . '/../../database.php';
if (!file_exists($path_db)) die("FATAL ERROR: File database tidak ditemukan.");
require_once $path_db;

$aksi = $_GET['aksi'] ?? '';

try {
    // --- TAMBAH ---
    if ($aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $nis = trim($_POST['nis']);
        $nisn = trim($_POST['nisn']);
        $nama = trim($_POST['nama_lengkap']);
        $jk = $_POST['jk'];
        $agama = $_POST['agama']; // BARU
        $kelas = $_POST['kelas_sekarang'];
        $tahun_masuk = $_POST['tahun_masuk'];
        $tgl_lahir = $_POST['tgl_lahir'];

        // Cek NISN Ganda
        $check = $pdo->prepare("SELECT id FROM siswa WHERE nisn = ?");
        $check->execute([$nisn]);
        if ($check->rowCount() > 0) {
            header("Location: ../../../views/siswa/tambah.php?pesan=nisn_duplicate");
            exit();
        }

        $sql = "INSERT INTO siswa (nis, nisn, nama_lengkap, jk, agama, kelas_sekarang, tahun_masuk, tgl_lahir) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nis, $nisn, $nama, $jk, $agama, $kelas, $tahun_masuk, $tgl_lahir]);

        header("Location: ../../../views/siswa/index.php?pesan=sukses_tambah");
    }

    // --- EDIT ---
    elseif ($aksi == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $nis = trim($_POST['nis']);
        $nisn = trim($_POST['nisn']);
        $nama = trim($_POST['nama_lengkap']);
        $jk = $_POST['jk'];
        $agama = $_POST['agama']; // BARU
        $kelas = $_POST['kelas_sekarang'];
        $tahun_masuk = $_POST['tahun_masuk'];
        $tgl_lahir = $_POST['tgl_lahir'];

        $check = $pdo->prepare("SELECT id FROM siswa WHERE nisn = ? AND id != ?");
        $check->execute([$nisn, $id]);
        if ($check->rowCount() > 0) {
            header("Location: ../../../views/siswa/edit.php?id=$id&pesan=nisn_duplicate");
            exit();
        }

        $sql = "UPDATE siswa SET nis=?, nisn=?, nama_lengkap=?, jk=?, agama=?, kelas_sekarang=?, tahun_masuk=?, tgl_lahir=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nis, $nisn, $nama, $jk, $agama, $kelas, $tahun_masuk, $tgl_lahir, $id]);

        header("Location: ../../../views/siswa/index.php?pesan=sukses_edit");
    }

    // --- HAPUS ---
    elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM siswa WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ../../../views/siswa/index.php?pesan=sukses_hapus");
    }

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>