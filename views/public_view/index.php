<?php
// views/public_view/index.php
require_once '../../config/database.php';

// --- LOGIC PHP (Mengambil Data) ---
try {
    // Statistik Utama
    $jml_siswa = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif'")->fetchColumn();
    $jml_ptk   = $pdo->query("SELECT COUNT(*) FROM ptk WHERE status_aktif = 'Aktif'")->fetchColumn();
    $jml_masuk = $pdo->query("SELECT COUNT(*) FROM surat_masuk")->fetchColumn();
    $jml_keluar= $pdo->query("SELECT COUNT(*) FROM surat_keluar")->fetchColumn();
    
    // Data Grafik Donut (Status Siswa)
    $stat_aktif = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif'")->fetchColumn();
    $stat_lulus = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Lulus'")->fetchColumn();
    $stat_pindah= $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Pindah'")->fetchColumn();
    $stat_keluar= $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Keluar'")->fetchColumn();

} catch (PDOException $e) {
    die("Error Database: " . $e->getMessage());
}

require_once 'header.php';
?>

<style>
    /* CSS KHUSUS PUBLIC DASHBOARD */
    :root {
        --public-primary: #0d6efd;
        --public-dark: #0a58ca;
    }

    /* 1. HERO SECTION DENGAN WAVE */
    .hero-wrapper {
        background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
        color: white;
        padding-top: 80px;
        padding-bottom: 0;
        position: relative;
        overflow: hidden;
    }
    
    .hero-content {
        padding-bottom: 150px; /* Ruang untuk wave */
        position: relative;
        z-index: 2;
    }

    /* Hiasan background abstrak */
    .bg-shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        z-index: 1;
    }
    .shape-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .shape-2 { width: 200px; height: 200px; bottom: 50px; left: -50px; }

    /* Wave SVG Separator */
    .custom-shape-divider-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        transform: rotate(180deg);
        z-index: 3;
    }
    .custom-shape-divider-bottom svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 120px;
    }
    .custom-shape-divider-bottom .shape-fill {
        fill: #f4f7f6; /* Sesuaikan dengan warna background body */
    }

    /* 2. FLOATING STAT CARDS */
    .stats-container {
        margin-top: -100px; /* Overlap ke atas Hero */
        position: relative;
        z-index: 10;
        padding-bottom: 50px;
    }
    
    .card-stat-public {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        padding: 30px 20px;
        text-align: center;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    .card-stat-public:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(13, 110, 253, 0.2);
    }
    
    .icon-circle {
        width: 70px; height: 70px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center; justify-content: center;
        margin-bottom: 20px;
        font-size: 1.8rem;
        transition: transform 0.4s ease;
    }
    .card-stat-public:hover .icon-circle {
        transform: scale(1.1) rotate(10deg);
    }

    /* Warna Icon */
    .ic-blue { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .ic-teal { background: rgba(32, 201, 151, 0.1); color: #20c997; }
    .ic-orange { background: rgba(253, 126, 20, 0.1); color: #fd7e14; }
    .ic-red { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

    /* 3. CHART SECTION */
    .chart-section {
        background: white;
        border-radius: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        padding: 40px;
        margin-bottom: 60px;
    }
</style>

<div class="hero-wrapper">
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="container hero-content text-center">
        <span class="badge bg-white bg-opacity-25 border border-white border-opacity-50 rounded-pill px-4 py-2 mb-3 fw-normal" style="backdrop-filter: blur(5px);">
            <i class="bi bi-stars me-2 text-warning"></i> Portal Informasi Resmi
        </span>
        <h1 class="display-4 fw-bold mb-3">Sistem Informasi Sekolah Digital</h1>
        <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
            Akses data akademik, tenaga pengajar, dan arsip persuratan sekolah secara transparan, akurat, dan <em>real-time</em>.
        </p>
        
        <div class="mt-4">
            <a href="siswa.php" class="btn btn-light rounded-pill px-5 py-3 fw-bold shadow-sm text-primary me-2">
                <i class="bi bi-search me-2"></i> Cari Data Siswa
            </a>
        </div>
    </div>

    <div class="custom-shape-divider-bottom">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
</div>

<div class="container stats-container">
    <div class="row g-4 justify-content-center">
        <div class="col-xl-3 col-md-6">
            <div class="card-stat-public">
                <div class="icon-circle ic-blue">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h2 class="fw-bold mb-1 counter"><?= number_format($jml_siswa) ?></h2>
                <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Siswa Aktif</p>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card-stat-public">
                <div class="icon-circle ic-teal">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <h2 class="fw-bold mb-1 counter"><?= number_format($jml_ptk) ?></h2>
                <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Guru & Staff</p>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card-stat-public">
                <div class="icon-circle ic-orange">
                    <i class="bi bi-envelope-paper-fill"></i>
                </div>
                <h2 class="fw-bold mb-1 counter"><?= number_format($jml_masuk) ?></h2>
                <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Surat Masuk</p>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card-stat-public">
                <div class="icon-circle ic-red">
                    <i class="bi bi-send-fill"></i>
                </div>
                <h2 class="fw-bold mb-1 counter"><?= number_format($jml_keluar) ?></h2>
                <p class="text-muted mb-0 small text-uppercase fw-bold ls-1">Surat Keluar</p>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row align-items-center">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="pe-lg-4">
                <span class="text-primary fw-bold text-uppercase small ls-1">Statistik Sekolah</span>
                <h2 class="fw-bold mt-2 mb-3">Sebaran Data Siswa</h2>
                <p class="text-muted mb-4">
                    Grafik berikut menampilkan status terkini seluruh siswa yang terdaftar dalam sistem, meliputi siswa aktif, lulus, pindah, maupun yang telah keluar.
                </p>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Data Terintegrasi</h6>
                        <small class="text-muted">Update otomatis dari database pusat.</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-shield-lock-fill text-primary fs-4 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Akses Publik Aman</h6>
                        <small class="text-muted">Hanya menampilkan data umum (privasi terjaga).</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="chart-section position-relative">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Status Siswa</h5>
                    <span class="badge bg-light text-dark border">Tahun <?= date('Y') ?></span>
                </div>
                <div style="height: 350px;">
                    <canvas id="publicChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-white py-5 mt-auto border-top">
    <div class="container text-center">
        <div class="mb-3">
            <a href="index.php" class="text-decoration-none text-primary fw-bold fs-4">
                <i class="bi bi-mortarboard-fill"></i> SIMS SEKOLAH
            </a>
        </div>
        <p class="text-muted small mb-4">
            Sistem Informasi Manajemen Sekolah Terpadu.<br>
            Jl. Pendidikan No. 123, Kota Pelajar, Indonesia.
        </p>
        <div class="text-muted small">
            &copy; <?= date('Y') ?> All rights reserved. <span class="mx-2">|</span> 
            <a href="../auth/login.php" class="text-decoration-none text-primary fw-bold">Login Admin</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Config Chart Public
    const ctx = document.getElementById('publicChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Lulus', 'Pindah', 'Keluar'],
            datasets: [{
                data: [<?= $stat_aktif ?>, <?= $stat_lulus ?>, <?= $stat_pindah ?>, <?= $stat_keluar ?>],
                backgroundColor: [
                    '#0d6efd', // Biru
                    '#20c997', // Teal
                    '#fd7e14', // Orange
                    '#dc3545'  // Merah
                ],
                hoverOffset: 15,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%', // Lubang tengah
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 30,
                        font: { size: 12, family: "'Poppins', sans-serif" }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
</script>
</body>
</html>