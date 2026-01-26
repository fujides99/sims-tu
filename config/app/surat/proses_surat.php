<?php
// config/app/surat/proses_surat.php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Path ke database (Naik 2 langkah)
require_once '../../database.php';

// Folder Upload (Absolute Path)
$folder_upload = __DIR__ . '/../../../public/uploads/surat/';

$aksi = $_GET['aksi'] ?? '';

try {
    // --- 1. PROSES TAMBAH ---
    if ($aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // ... (Logika Tambah sama seperti sebelumnya, saya ringkas di sini) ...
        $nama_file_db = null;
        
        if (!empty($_FILES['file_surat']['name'])) {
            // Cek folder
            if (!is_dir($folder_upload)) mkdir($folder_upload, 0777, true);

            $ext = strtolower(pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION));
            // Validasi ext & size disini (anggap sudah valid seperti kode sebelumnya)
            
            $nama_file_baru = uniqid() . '_surat.' . $ext;
            if(move_uploaded_file($_FILES['file_surat']['tmp_name'], $folder_upload . $nama_file_baru)){
                $nama_file_db = $nama_file_baru;
            }
        }

        $sql = "INSERT INTO surat_masuk (no_agenda, no_surat, pengirim, perihal, tgl_surat, tgl_diterima, file_path, input_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['no_agenda'], $_POST['no_surat'], $_POST['pengirim'], $_POST['perihal'], 
            $_POST['tgl_surat'], $_POST['tgl_diterima'], $nama_file_db, $_SESSION['user_id']
        ]);

        header("Location: ../../../views/surat/index.php?pesan=sukses");
    }

    // --- 2. PROSES EDIT (UPDATE) ---
    elseif ($aksi == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        
        // Ambil data lama untuk cek file lama
        $stmt = $pdo->prepare("SELECT file_path FROM surat_masuk WHERE id = ?");
        $stmt->execute([$id]);
        $data_lama = $stmt->fetch();

        $nama_file_db = $data_lama['file_path']; // Default pakai file lama

        // Jika ada upload file baru
        if (!empty($_FILES['file_surat']['name'])) {
            // 1. Hapus file lama fisik jika ada
            if ($data_lama['file_path'] && file_exists($folder_upload . $data_lama['file_path'])) {
                unlink($folder_upload . $data_lama['file_path']);
            }

            // 2. Upload file baru
            $ext = strtolower(pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION));
            $nama_file_baru = uniqid() . '_surat.' . $ext;
            if(move_uploaded_file($_FILES['file_surat']['tmp_name'], $folder_upload . $nama_file_baru)){
                $nama_file_db = $nama_file_baru;
            }
        }

        // Update Database
        $sql = "UPDATE surat_masuk SET no_agenda=?, no_surat=?, pengirim=?, perihal=?, tgl_surat=?, tgl_diterima=?, file_path=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['no_agenda'], $_POST['no_surat'], $_POST['pengirim'], $_POST['perihal'], 
            $_POST['tgl_surat'], $_POST['tgl_diterima'], $nama_file_db, $id
        ]);

        header("Location: ../../../views/surat/index.php?pesan=sukses_edit");
    }

    // --- 3. PROSES HAPUS (DELETE) ---
    elseif ($aksi == 'hapus') {
        $id = $_GET['id'];

        // Ambil nama file dulu sebelum dihapus datanya
        $stmt = $pdo->prepare("SELECT file_path FROM surat_masuk WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        // Hapus file fisik jika ada
        if ($data['file_path'] && file_exists($folder_upload . $data['file_path'])) {
            unlink($folder_upload . $data['file_path']);
        }

        // Hapus data di database
        $stmt = $pdo->prepare("DELETE FROM surat_masuk WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: ../../../views/surat/index.php?pesan=sukses_hapus");
    }

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>