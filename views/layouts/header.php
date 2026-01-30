<?php
// views/layouts/header.php
if (session_status() == PHP_SESSION_NONE) {
    // session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMS TU</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <link rel="stylesheet" href="../../public/assets/css/style.css">
</head>
<body class="bg-light">

    <div id="wrapper">

        <div class="overlay" id="overlay"></div>

        <div id="sidebar-wrapper">
            
            <div class="sidebar-heading text-white text-center py-4 fs-4 fw-bold border-bottom border-secondary">
                <i class="bi bi-mortarboard-fill me-2"></i> SIMS TU
            </div>

            <div class="sidebar-content py-2 px-2">
                <?php require_once 'sidebar.php'; ?>
            </div>

            <div class="mt-auto p-3 border-top border-secondary bg-dark">
                <div class="dropup"> <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary rounded-circle d-flex justify-content-center align-items-center me-2 fw-bold text-white shadow-sm" style="width: 38px; height: 38px;">
                            <?= substr($_SESSION['nama_lengkap'], 0, 1) ?>
                        </div>
                        <div class="text-truncate" style="max-width: 140px;">
                            <small class="d-block text-white-50" style="font-size: 10px;">Login sebagai:</small>
                            <span class="fw-bold small"><?= $_SESSION['nama_lengkap'] ?></span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow mb-2" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="../auth/profil.php"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-3 py-3 mb-4">
                <div class="container-fluid">
                    
                    <button class="btn-toggle-menu" id="menu-toggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <span class="navbar-brand ms-3 mb-0 h1 fs-6 text-secondary d-none d-md-block">
                        Sistem Informasi Manajemen Sekolah
                    </span>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <a href="../users/index.php" class="btn btn-light text-secondary ms-2 rounded-circle border shadow-sm" data-bs-toggle="tooltip" title="Manajemen User">
                            <i class="bi bi-gear-fill"></i>
                        </a>
                    <?php endif; ?>
                    
                </div>
            </nav>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var el = document.getElementById("wrapper");
                    var toggleButton = document.getElementById("menu-toggle");
                    var overlay = document.getElementById("overlay");
                    
                    // Ambil semua link di sidebar
                    var menuLinks = document.querySelectorAll("#sidebar-wrapper a");

                    function toggleMenu() {
                        el.classList.toggle("toggled");
                    }

                    // 1. Klik Hamburger
                    toggleButton.onclick = function() {
                        toggleMenu();
                    };

                    // 2. Klik Overlay (Tutup)
                    overlay.onclick = function() {
                        el.classList.remove("toggled");
                    };

                    // 3. Klik Menu (LOGIC DIPERBAIKI)
                    menuLinks.forEach(function(link) {
                        link.addEventListener('click', function(e) {
                            
                            // PERBAIKAN: Cek apakah yang diklik adalah tombol Dropdown Profil?
                            // Jika class-nya mengandung 'dropdown-toggle', JANGAN tutup sidebar.
                            if (this.classList.contains('dropdown-toggle')) {
                                return; // Stop, biarkan dropdown terbuka
                            }

                            // Jika bukan dropdown (berarti menu biasa), baru tutup sidebar
                            el.classList.remove("toggled");
                        });
                    });
                });
            </script>