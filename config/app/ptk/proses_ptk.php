<?php
// config/app/ptk/proses_ptk.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../../database.php';

$aksi = $_GET['aksi'] ?? '';

try {
    // --- TAMBAH ---
    if ($aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql = "INSERT INTO ptk (
            nama_lengkap, nuptk, jk, tempat_lahir, tgl_lahir, nip, 
            status_kepegawaian, jenis_ptk, gelar_depan, gelar_belakang, 
            jenjang_pendidikan, jurusan_prodi, sertifikasi, tmt_kerja, status_aktif
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nama_lengkap'], $_POST['nuptk'], $_POST['jk'], 
            $_POST['tempat_lahir'], $_POST['tgl_lahir'], $_POST['nip'],
            $_POST['status_kepegawaian'], $_POST['jenis_ptk'], 
            $_POST['gelar_depan'], $_POST['gelar_belakang'],
            $_POST['jenjang_pendidikan'], $_POST['jurusan_prodi'], 
            $_POST['sertifikasi'], $_POST['tmt_kerja'], $_POST['status_aktif']
        ]);
        header("Location: ../../../views/ptk/index.php?pesan=sukses_tambah");
    }

    // --- EDIT ---
    elseif ($aksi == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql = "UPDATE ptk SET 
            nama_lengkap=?, nuptk=?, jk=?, tempat_lahir=?, tgl_lahir=?, nip=?, 
            status_kepegawaian=?, jenis_ptk=?, gelar_depan=?, gelar_belakang=?, 
            jenjang_pendidikan=?, jurusan_prodi=?, sertifikasi=?, tmt_kerja=?, status_aktif=?
            WHERE id=?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nama_lengkap'], $_POST['nuptk'], $_POST['jk'], 
            $_POST['tempat_lahir'], $_POST['tgl_lahir'], $_POST['nip'],
            $_POST['status_kepegawaian'], $_POST['jenis_ptk'], 
            $_POST['gelar_depan'], $_POST['gelar_belakang'],
            $_POST['jenjang_pendidikan'], $_POST['jurusan_prodi'], 
            $_POST['sertifikasi'], $_POST['tmt_kerja'], $_POST['status_aktif'],
            $_POST['id']
        ]);
        header("Location: ../../../views/ptk/index.php?pesan=sukses_edit");
    }

    // --- HAPUS SATUAN ---
    elseif ($aksi == 'hapus') {
        $stmt = $pdo->prepare("DELETE FROM ptk WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header("Location: ../../../views/ptk/index.php?pesan=sukses_hapus");
    }

    // --- FITUR BARU: HAPUS SEMUA (RESET DB) ---
    elseif ($aksi == 'hapus_semua' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $pass_input = $_POST['password_konfirmasi'];
        $user_id = $_SESSION['user_id']; 

        // Cek Password Admin
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass_input, $user['password'])) {
            $pdo->query("DELETE FROM ptk"); 
            $pdo->query("ALTER TABLE ptk AUTO_INCREMENT = 1");
            header("Location: ../../../views/ptk/index.php?pesan=sukses_reset");
        } else {
            header("Location: ../../../views/ptk/index.php?pesan=gagal_password");
        }
    }

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>