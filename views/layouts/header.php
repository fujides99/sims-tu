<?php
if (session_status() == PHP_SESSION_NONE) {
    // session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMS TU - SMP Nusantara</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../public/assets/css/style.css">
</head>
<body class="bg-light">

    <div id="wrapper">

        <div id="sidebar-wrapper" class="bg-dark pt-0">
            <div class="list-group list-group-flush p-2">
                <?php require_once 'sidebar.php'; ?>
            </div>
        </div>

        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-2 py-2 mb-4">
                <div class="container-fluid">

                    <button class="btn-toggle-menu" id="menu-toggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <span class="navbar-brand mb-0 h1 fs-6 d-none d-md-block text-secondary">
                        Sistem Informasi Manajemen Sekolah
                    </span>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i>
                                    <?= isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'User' ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="../auth/profil.php"><i class="bi bi-person me-2"></i> Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>