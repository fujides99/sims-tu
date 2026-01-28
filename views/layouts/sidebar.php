<?php
// Logika sederhana untuk menentukan menu aktif
// Mengambil URL saat ini, misal: /sims-tu/views/siswa/index.php
$uri = $_SERVER['REQUEST_URI'];
?>

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; min-height: 100vh;">
    <a href="/sims-tu/views/dashboard/index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="bi bi-building fs-4 me-2"></i>
        <span class="fs-4 fw-bold">SIMS SMP</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        
       <li class="nav-item">
            <a href="#menuSurat" data-bs-toggle="collapse" class="nav-link text-white dropdown-toggle">
                <i class="bi bi-envelope-paper-fill me-2"></i>
                Arsip Surat
            </a>
            <ul class="collapse list-unstyled ps-4 <?= (strpos($_SERVER['REQUEST_URI'], 'surat_') !== false) ? 'show' : '' ?>" id="menuSurat">
                <li class="mb-1">
                    <a href="/sims-tu/views/surat_masuk/index.php" class="nav-link text-white text-opacity-75">
                        <i class="bi bi-arrow-return-right me-2"></i> Surat Masuk
                    </a>
                </li>
                <li>
                    <a href="/sims-tu/views/surat_keluar/index.php" class="nav-link text-white text-opacity-75">
                        <i class="bi bi-arrow-return-left me-2"></i> Surat Keluar
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="/sims-tu/views/siswa/index.php" 
               class="nav-link text-white <?= (strpos($uri, 'siswa') !== false) ? 'active bg-primary' : '' ?>">
                <i class="bi bi-people-fill me-2"></i>
                Data Siswa
            </a>
        </li>

        <li class="nav-item">
            <a href="/sims-tu/views/ptk/index.php" 
                class="nav-link text-white <?= (strpos($uri, 'ptk') !== false) ? 'active bg-primary' : '' ?>">
                <i class="bi bi-person-badge-fill me-2"></i>
                Data PTK
            </a>
        </li>
    </ul>
    
    <hr>
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name=Admin+TU&background=random" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong><?= $_SESSION['nama_lengkap'] ?? 'Admin' ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="../auth/profil.php">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="/sims-tu/views/auth/logout.php">Sign out</a></li>
        </ul>
    </div>
</div>