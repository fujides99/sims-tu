<?php
session_start();
require_once '../../database.php';

$aksi = $_GET['aksi'] ?? '';
// Perbaikan Path Upload
$targetDir = "../../../public/uploads/surat_masuk/";

try {
    if ($aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // ... (Logika simpan sama, pastikan header location benar)
        $no_agenda = $_POST['no_agenda'];
        $no_surat = $_POST['no_surat'];
        $pengirim = $_POST['pengirim'];
        $perihal = $_POST['perihal'];
        $tgl_surat = $_POST['tgl_surat'];
        $tgl_diterima = $_POST['tgl_diterima'];

        $fileName = null;
        if (!empty($_FILES['file_surat']['name'])) {
            $ext = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);
            $fileName = "Masuk_" . time() . "." . $ext;
            move_uploaded_file($_FILES['file_surat']['tmp_name'], $targetDir . $fileName);
        }

        $sql = "INSERT INTO surat_masuk (no_agenda, no_surat, pengirim, perihal, tgl_surat, tgl_diterima, file_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$no_agenda, $no_surat, $pengirim, $perihal, $tgl_surat, $tgl_diterima, $fileName]);

        header("Location: ../../../views/surat_masuk/index.php?pesan=sukses");
    }
    elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        // Hapus file
        $stmt = $pdo->prepare("SELECT file_path FROM surat_masuk WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && $row['file_path']) {
            if (file_exists($targetDir . $row['file_path'])) unlink($targetDir . $row['file_path']);
        }
        // Hapus DB
        $stmt = $pdo->prepare("DELETE FROM surat_masuk WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ../../../views/surat_masuk/index.php?pesan=hapus");
    }
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }
?>