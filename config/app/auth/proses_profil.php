<?php
// config/app/auth/proses_profil.php
session_start();
require_once '../../database.php';

$aksi = $_GET['aksi'] ?? '';
$id_user = $_SESSION['user_id']; // Ambil ID user yang sedang login
$targetDir = "../../../public/uploads/profil/";

try {
    // --- 1. UPDATE DATA DIRI & FOTO ---
    if ($aksi == 'update_profil' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_lengkap = trim($_POST['nama_lengkap']);
        
        // Cek apakah ada upload foto?
        if (!empty($_FILES['foto']['name'])) {
            $fileName = "Profil_" . time() . "_" . $_FILES['foto']['name'];
            $fileTmp = $_FILES['foto']['tmp_name'];
            
            // Hapus foto lama jika ada (agar server tidak penuh)
            $stmt = $pdo->prepare("SELECT foto FROM users WHERE id = ?");
            $stmt->execute([$id_user]);
            $oldFoto = $stmt->fetchColumn();
            
            if ($oldFoto && file_exists($targetDir . $oldFoto)) {
                unlink($targetDir . $oldFoto);
            }

            // Upload foto baru
            move_uploaded_file($fileTmp, $targetDir . $fileName);

            // Update DB dengan Foto
            $sql = "UPDATE users SET nama_lengkap = ?, foto = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama_lengkap, $fileName, $id_user]);
            
            // Update Session Foto agar header langsung berubah
            $_SESSION['foto'] = $fileName;
        
        } else {
            // Update DB Tanpa Foto (Hanya Nama)
            $sql = "UPDATE users SET nama_lengkap = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama_lengkap, $id_user]);
        }

        // Update Session Nama
        $_SESSION['nama_lengkap'] = $nama_lengkap;

        header("Location: ../../../views/auth/profil.php?pesan=sukses_profil");
    }

    // --- 2. GANTI PASSWORD ---
    elseif ($aksi == 'ganti_password' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $pass_lama = $_POST['pass_lama'];
        $pass_baru = $_POST['pass_baru'];
        $konfirmasi = $_POST['konfirmasi_pass'];

        // Cek Pass Baru vs Konfirmasi
        if ($pass_baru !== $konfirmasi) {
            header("Location: ../../../views/auth/profil.php?pesan=gagal_konfirmasi");
            exit();
        }

        // Ambil Password Hash dari DB
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$id_user]);
        $user = $stmt->fetch();

        // Verifikasi Password Lama
        if (password_verify($pass_lama, $user['password'])) {
            // Hash Password Baru
            $new_hash = password_hash($pass_baru, PASSWORD_DEFAULT);
            
            // Update DB
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$new_hash, $id_user]);

            header("Location: ../../../views/auth/profil.php?pesan=sukses_password");
        } else {
            header("Location: ../../../views/auth/profil.php?pesan=gagal_lama");
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>