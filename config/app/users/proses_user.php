<?php
// config/app/users/proses_user.php
session_start();
require_once '../../database.php';

$aksi = $_GET['aksi'] ?? '';

try {
    // --- 1. TAMBAH USER ---
    if ($aksi == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $nama = trim($_POST['nama_lengkap']);
        $role = $_POST['role'];
        $password = $_POST['password'];

        // Cek Username Kembar
        $cek = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $cek->execute([$username]);
        if ($cek->rowCount() > 0) {
            header("Location: ../../../views/users/index.php?pesan=gagal_username");
            exit();
        }

        // Hash Password (Aman)
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (nama_lengkap, username, password, role, status) VALUES (?, ?, ?, ?, 'aktif')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $username, $hashed_pass, $role]);

        header("Location: ../../../views/users/index.php?pesan=sukses_tambah");
    }

    // --- 2. EDIT USER ---
    elseif ($aksi == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $username = trim($_POST['username']);
        $nama = trim($_POST['nama_lengkap']);
        $role = $_POST['role'];
        $status = $_POST['status'];
        $password_baru = $_POST['password'];

        // Cek Username Kembar (Kecuali punya diri sendiri)
        $cek = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $cek->execute([$username, $id]);
        if ($cek->rowCount() > 0) {
            header("Location: ../../../views/users/index.php?pesan=gagal_username");
            exit();
        }

        // Logika Password: Ganti hash jika diisi, biarkan jika kosong
        if (!empty($password_baru)) {
            $hashed_pass = password_hash($password_baru, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET nama_lengkap=?, username=?, password=?, role=?, status=? WHERE id=?";
            $params = [$nama, $username, $hashed_pass, $role, $status, $id];
        } else {
            $sql = "UPDATE users SET nama_lengkap=?, username=?, role=?, status=? WHERE id=?";
            $params = [$nama, $username, $role, $status, $id];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header("Location: ../../../views/users/index.php?pesan=sukses_edit");
    }

    // --- 3. SOFT DELETE (NONAKTIFKAN) ---
    // Kita tidak menghapus baris, tapi mengubah status jadi 'nonaktif'
    elseif ($aksi == 'hapus') {
        $id = $_GET['id'];
        
        // Mencegah menghapus akun sendiri yang sedang login
        if ($id == $_SESSION['user_id']) {
            header("Location: ../../../views/users/index.php?pesan=gagal_hapus_diri");
            exit();
        }

        $stmt = $pdo->prepare("UPDATE users SET status = 'nonaktif' WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: ../../../views/users/index.php?pesan=sukses_hapus");
    }

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}
?>