<?php
// views/dashboard/index.php
require_once '../../config/session_check.php';

// 1. Panggil Header (Otomatis panggil Sidebar juga)
require_once '../layouts/header.php';
?>

<div class="container-fluid">
    <h2 class="mb-4">Dashboard Overview</h2>

    <div class="alert alert-success shadow-sm">
        <h4 class="alert-heading">Selamat Datang, <?= $_SESSION['nama_lengkap']; ?>!</h4>
        <p>Anda login sebagai <strong><?= ucfirst($_SESSION['role']); ?></strong>. Selamat bekerja mengelola administrasi sekolah.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title"><i class="bi bi-people-fill"></i> Manajemen Siswa</h5>
                        <p class="card-text opacity-75">Kelola data induk siswa, edit, dan cetak laporan.</p>
                    </div>
                    <a href="../siswa/index.php" class="btn btn-light text-primary fw-bold mt-3 stretched-link">Buka Menu Siswa</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title"><i class="bi bi-envelope-paper-fill"></i> Surat Masuk</h5>
                        <p class="card-text opacity-75">Input surat masuk, scan file, dan agenda surat.</p>
                    </div>
                    <a href="../surat/index.php" class="btn btn-light text-success fw-bold mt-3 stretched-link">Buka Menu Surat</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title text-secondary"><i class="bi bi-gear-fill"></i> Pengaturan</h5>
                        <p class="card-text text-muted">Profil pengguna dan sistem.</p>
                    </div>
                    <a href="../auth/logout.php" class="btn btn-outline-danger w-100 mt-3">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// 2. Panggil Footer (Tutup HTML)
require_once '../layouts/footer.php';
?>