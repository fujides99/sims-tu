<?php
// views/auth/login.php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/index.php");
    exit();
}

// --- PERBAIKAN PATH SESUAI GAMBAR ---
// Posisi kita: views/auth/
// Target: config/database.php
// Naik 2 level (../../) lalu masuk ke 'config/database.php'
$path_config = __DIR__ . '/../../config/database.php';

if (file_exists($path_config)) {
    require_once $path_config;
} else {
    die("ERROR: File koneksi tidak ditemukan di: $path_config");
}

$error = "";
// ... (Sisa kode ke bawah sama persis dengan sebelumnya)
// 3. Proses Login Saat Tombol ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {
        try {
            // Cek User ke Database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
            $stmt->execute(['u' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                
                // Cek Status Aktif
                if ($user['status'] != 'aktif') {
                    $error = "Akun Anda dinonaktifkan. Hubungi Admin.";
                } else {
                    // Login Sukses -> Set Session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect ke Dashboard
                    header("Location: ../dashboard/index.php");
                    exit();
                }

            } else {
                $error = "Username atau Password salah!";
            }

        } catch (PDOException $e) {
            $error = "Error Database: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMS TU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { width: 100%; max-width: 400px; padding: 30px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <div class="login-box">
        <h3 class="text-center mb-4 text-primary">Login TU SMP</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php 
        // Pesan dari session check
        if (isset($_GET['pesan']) && $_GET['pesan'] == 'belum_login') {
            echo '<div class="alert alert-warning">Silakan login dulu!</div>';
        }
        if (isset($_GET['pesan']) && $_GET['pesan'] == 'logout') {
            echo '<div class="alert alert-success">Berhasil Logout.</div>';
        }
        ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Masuk Aplikasi</button>
            </div>
        </form>
        
        <div class="text-center mt-3 text-muted">
            <small>Default: admin / admin123</small>
        </div>
    </div>

</body>
</html>