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
            <a href="/sims-tu/views/dashboard/index.php" 
               class="nav-link text-white <?= (strpos($uri, 'dashboard') !== false) ? 'active bg-primary' : '' ?>" aria-current="page">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="/sims-tu/views/siswa/index.php" 
               class="nav-link text-white <?= (strpos($uri, 'siswa') !== false) ? 'active bg-primary' : '' ?>">
                <i class="bi bi-people-fill me-2"></i>
                Data Siswa
            </a>
        </li>

        <li>
            <a href="/sims-tu/views/surat/index.php" 
               class="nav-link text-white <?= (strpos($uri, 'surat') !== false) ? 'active bg-primary' : '' ?>">
                <i class="bi bi-envelope-paper-fill me-2"></i>
                Surat Masuk
            </a>
        </li>

        <li>
            <a href="#" class="nav-link text-white text-muted">
                <i class="bi bi-person-badge-fill me-2"></i>
                Data Guru (Soon)
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
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="/sims-tu/views/auth/logout.php">Sign out</a></li>
        </ul>
    </div>
</div>