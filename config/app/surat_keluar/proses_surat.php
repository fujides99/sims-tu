<?php
session_start();
require_once '../../database.php';

$aksi = $_GET['aksi'] ?? '';
$targetDir = "../../../public/uploads/surat_keluar/";
if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

try {
    // TAMBAH
    if ($aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $no_agenda = $_POST['no_agenda'];
        $no_surat = $_POST['no_surat'];
        $tujuan = $_POST['tujuan']; // Bedanya di sini (Tujuan)
        $perihal = $_POST['perihal'];
        $tgl_surat = $_POST['tgl_surat'];
        
        $fileName = null;
        if (!empty($_FILES['file_surat']['name'])) {
            $ext = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);
            $fileName = "Keluar_" . time() . "." . $ext;
            move_uploaded_file($_FILES['file_surat']['tmp_name'], $targetDir . $fileName);
        }

        $sql = "INSERT INTO surat_keluar (no_agenda, no_surat, tujuan, perihal, tgl_surat, file_path) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$no_agenda, $no_surat, $tujuan, $perihal, $tgl_surat, $fileName]);

        header("Location: ../../../views/surat_keluar/index.php?pesan=sukses_tambah");
    }
    // HAPUS
    elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT file_path FROM surat_keluar WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if ($row && $row['file_path']) {
            if (file_exists($targetDir . $row['file_path'])) unlink($targetDir . $row['file_path']);
        }

        $stmt = $pdo->prepare("DELETE FROM surat_keluar WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ../../../views/surat_keluar/index.php?pesan=sukses_hapus");
    }
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }
?>