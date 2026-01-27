<?php
// config/app/siswa/proses_siswa.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../../database.php';

$aksi = $_GET['aksi'] ?? '';

try {
    // --- 1. TAMBAH DATA ---
    if ($aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Cek NISN Ganda
        $check = $pdo->prepare("SELECT id FROM siswa WHERE nisn = ?");
        $check->execute([$_POST['nisn']]);
        if ($check->rowCount() > 0) {
            header("Location: ../../../views/siswa/tambah.php?pesan=nisn_duplicate");
            exit();
        }

        $sql = "INSERT INTO siswa (
            nis, nisn, nama_lengkap, jk, tempat_lahir, tgl_lahir, agama, 
            alamat_siswa, nama_ayah, nama_ibu, pekerjaan_ayah, pekerjaan_ibu, 
            no_hp_ortu, kelas_sekarang, tahun_masuk, status_siswa
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, 'Aktif'
        )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nis'], $_POST['nisn'], $_POST['nama_lengkap'], $_POST['jk'],
            $_POST['tempat_lahir'], $_POST['tgl_lahir'], $_POST['agama'],
            $_POST['alamat_siswa'], $_POST['nama_ayah'], $_POST['nama_ibu'],
            $_POST['pekerjaan_ayah'], $_POST['pekerjaan_ibu'], $_POST['no_hp_ortu'],
            $_POST['kelas_sekarang'], $_POST['tahun_masuk']
        ]);

        header("Location: ../../../views/siswa/index.php?pesan=sukses_tambah");
    }

    // --- 2. EDIT DATA ---
    elseif ($aksi == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];

        // Cek NISN Ganda (kecuali punya sendiri)
        $check = $pdo->prepare("SELECT id FROM siswa WHERE nisn = ? AND id != ?");
        $check->execute([$_POST['nisn'], $id]);
        if ($check->rowCount() > 0) {
            header("Location: ../../../views/siswa/edit.php?id=$id&pesan=nisn_duplicate");
            exit();
        }

        $sql = "UPDATE siswa SET 
            nis=?, nisn=?, nama_lengkap=?, jk=?, tempat_lahir=?, tgl_lahir=?, agama=?, 
            alamat_siswa=?, nama_ayah=?, nama_ibu=?, pekerjaan_ayah=?, pekerjaan_ibu=?, 
            no_hp_ortu=?, kelas_sekarang=?, tahun_masuk=?, status_siswa=?
            WHERE id=?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nis'], $_POST['nisn'], $_POST['nama_lengkap'], $_POST['jk'],
            $_POST['tempat_lahir'], $_POST['tgl_lahir'], $_POST['agama'],
            $_POST['alamat_siswa'], $_POST['nama_ayah'], $_POST['nama_ibu'],
            $_POST['pekerjaan_ayah'], $_POST['pekerjaan_ibu'], $_POST['no_hp_ortu'],
            $_POST['kelas_sekarang'], $_POST['tahun_masuk'], $_POST['status_siswa'],
            $id
        ]);

        header("Location: ../../../views/siswa/index.php?pesan=sukses_edit");
    }

    // --- 3. HAPUS DATA SATUAN ---
    elseif ($aksi == 'hapus') {
        $stmt = $pdo->prepare("DELETE FROM siswa WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        header("Location: ../../../views/siswa/index.php?pesan=sukses_hapus");
    }

    // --- 4. HAPUS SEMUA (RESET DATABASE) ---
    elseif ($aksi == 'hapus_semua' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $pass_input = $_POST['password_konfirmasi'];
        $user_id = $_SESSION['user_id']; // Ambil ID admin yang sedang login

        // Ambil Password Hash Admin dari Database
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        // Verifikasi Password
        if ($user && password_verify($pass_input, $user['password'])) {
            
            // Password Benar: Hapus Semua Data
            $stmt = $pdo->query("DELETE FROM siswa"); 
            
            // Reset Auto Increment agar ID mulai dari 1 lagi
            $pdo->query("ALTER TABLE siswa AUTO_INCREMENT = 1");

            header("Location: ../../../views/siswa/index.php?pesan=sukses_reset");
        } else {
            // Password Salah
            header("Location: ../../../views/siswa/index.php?pesan=gagal_password");
        }
    }

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>