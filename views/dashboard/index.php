<?php
// views/dashboard/index.php

// --- 1. CONFIG & SESSION ---
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// --- SET ZONA WAKTU PHP (Untuk Query Database) ---
date_default_timezone_set('Asia/Jakarta');

// --- 2. LOGIC BACKEND (Query Database Tetap Pakai PHP) ---
try {
    // A. Statistik Utama
    $jml_siswa = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif'")->fetchColumn();
    $jml_ptk   = $pdo->query("SELECT COUNT(*) FROM ptk WHERE status_aktif = 'Aktif'")->fetchColumn();
    $jml_masuk = $pdo->query("SELECT COUNT(*) FROM surat_masuk")->fetchColumn();
    $jml_keluar= $pdo->query("SELECT COUNT(*) FROM surat_keluar")->fetchColumn();

    // B. Ambil 5 Surat Masuk Terakhir
    $recent_surat = $pdo->query("SELECT * FROM surat_masuk ORDER BY tgl_diterima DESC, id DESC LIMIT 5")->fetchAll();

    // C. Data Grafik
    $tahun_ini = date('Y');
    $data_masuk_per_bulan = array_fill(1, 12, 0); 
    $data_keluar_per_bulan = array_fill(1, 12, 0);

    $sql_m = "SELECT MONTH(tgl_diterima) as bulan, COUNT(*) as total FROM surat_masuk WHERE YEAR(tgl_diterima) = '$tahun_ini' GROUP BY bulan";
    foreach ($pdo->query($sql_m) as $row) { $data_masuk_per_bulan[$row['bulan']] = $row['total']; }

    $sql_k = "SELECT MONTH(tgl_surat) as bulan, COUNT(*) as total FROM surat_keluar WHERE YEAR(tgl_surat) = '$tahun_ini' GROUP BY bulan";
    foreach ($pdo->query($sql_k) as $row) { $data_keluar_per_bulan[$row['bulan']] = $row['total']; }

    $stat_aktif = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif'")->fetchColumn();
    $stat_lulus = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Lulus'")->fetchColumn();
    $stat_pindah= $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Pindah'")->fetchColumn();
    $stat_keluar= $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Keluar'")->fetchColumn();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

require_once '../layouts/header.php';
?>

<style>
    /* Styling Card Modern */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    /* Hero Section Gradient */
    .hero-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        position: relative;
    }
    .hero-pattern {
        position: absolute; right: 0; bottom: 0; opacity: 0.1;
        font-size: 10rem; line-height: 0; pointer-events: none;
    }

    /* Stat Cards Gradients */
    .stat-card-1 { background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%); border-left: 5px solid #0d6efd; }
    .stat-card-2 { background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%); border-left: 5px solid #0dcaf0; }
    .stat-card-3 { background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%); border-left: 5px solid #198754; }
    .stat-card-4 { background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%); border-left: 5px solid #dc3545; }
    
    .icon-box {
        width: 50px; height: 50px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-bottom: 10px;
    }
    .icon-blue { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .icon-cyan { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
    .icon-green { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .icon-red { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

    /* Timeline Style */
    .timeline { position: relative; padding-left: 30px; }
    .timeline-item { position: relative; padding-bottom: 25px; border-left: 2px solid #e9ecef; padding-left: 20px; }
    .timeline-item:last-child { border-left: transparent; }
    .timeline-dot {
        position: absolute; left: -6px; top: 0;
        width: 14px; height: 14px; border-radius: 50%;
        background: #0d6efd; border: 3px solid #fff;
        box-shadow: 0 0 0 2px #0d6efd;
    }
    
    /* Quick Actions */
    .action-btn {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 20px; border-radius: 15px; background: #fff;
        border: 1px solid #eee; text-decoration: none; color: #555;
        transition: all 0.3s;
    }
    .action-btn:hover { background: #f8f9fa; border-color: #0d6efd; color: #0d6efd; }
    .action-btn i { font-size: 2rem; margin-bottom: 10px; }
</style>

<div class="container-fluid px-4 pt-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hero-card">
                <div class="card-body p-4 p-lg-5 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold mb-2">
                            <i id="sapaanIcon" class="bi bi-sun-fill me-2"></i> <span id="sapaanText">Memuat...</span>
                        </h6>
                        <h2 class="fw-bold display-6 mb-1">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>!</h2>
                        <p class="mb-0 opacity-75">Selamat datang kembali di Dashboard Administrator.</p>
                    </div>
                    <div class="d-none d-md-block text-end">
                        <h1 id="realtimeClock" class="fw-bold mb-0 display-4">00:00</h1>
                        <p id="realtimeDate" class="mb-0 fs-5">Memuat Tanggal...</p>
                    </div>
                    <div class="hero-pattern"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card-1 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Siswa Aktif</p>
                            <h3 class="fw-bold mb-0"><?= number_format($jml_siswa) ?></h3>
                        </div>
                        <div class="icon-box icon-blue"><i class="bi bi-people-fill"></i></div>
                    </div>
                    <div class="mt-3 small">
                        <a href="../siswa/index.php" class="text-decoration-none fw-bold">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card-2 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Guru & Staff</p>
                            <h3 class="fw-bold mb-0"><?= number_format($jml_ptk) ?></h3>
                        </div>
                        <div class="icon-box icon-cyan"><i class="bi bi-person-workspace"></i></div>
                    </div>
                    <div class="mt-3 small">
                        <a href="../ptk/index.php" class="text-decoration-none text-info fw-bold">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card-3 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Surat Masuk</p>
                            <h3 class="fw-bold mb-0"><?= number_format($jml_masuk) ?></h3>
                        </div>
                        <div class="icon-box icon-green"><i class="bi bi-envelope-arrow-down-fill"></i></div>
                    </div>
                    <div class="mt-3 small">
                        <a href="../surat_masuk/index.php" class="text-decoration-none text-success fw-bold">Arsip <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Surat Keluar</p>
                            <h3 class="fw-bold mb-0"><?= number_format($jml_keluar) ?></h3>
                        </div>
                        <div class="icon-box icon-red"><i class="bi bi-send-fill"></i></div>
                    </div>
                    <div class="mt-3 small">
                        <a href="../surat_keluar/index.php" class="text-decoration-none text-danger fw-bold">Arsip <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2 text-primary"></i> Statistik Persuratan <?= $tahun_ini ?></h6>
                    <button class="btn btn-sm btn-light rounded-circle"><i class="bi bi-three-dots-vertical"></i></button>
                </div>
                <div class="card-body">
                    <canvas id="chartSurat" style="height: 320px; width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill me-2 text-warning"></i> Status Siswa</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <div style="width: 85%;">
                        <canvas id="chartSiswa"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-4 text-center">
                    <small class="text-muted">Total Data: <strong><?= number_format($jml_siswa + $stat_lulus + $stat_pindah + $stat_keluar) ?></strong> Siswa</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-info"></i> Aktivitas Surat Masuk Terbaru</h6>
                    <a href="../surat_masuk/index.php" class="btn btn-sm btn-primary rounded-pill px-3">Semua</a>
                </div>
                <div class="card-body pt-4">
                    <div class="timeline">
                        <?php if(count($recent_surat) > 0): ?>
                            <?php foreach($recent_surat as $row): ?>
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($row['pengirim']) ?></h6>
                                    <p class="text-muted small mb-1"><?= htmlspecialchars($row['perihal']) ?></p>
                                    <small class="text-primary fw-bold bg-light px-2 py-1 rounded">
                                        <i class="bi bi-calendar-check me-1"></i> <?= date('d M Y', strtotime($row['tgl_diterima'])) ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted my-4">Belum ada aktivitas surat masuk.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-grid-fill me-2 text-danger"></i> Akses Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="../surat_masuk/tambah.php" class="action-btn">
                                <i class="bi bi-envelope-plus-fill text-success"></i>
                                <span class="fw-bold small">Input Masuk</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="../surat_keluar/tambah.php" class="action-btn">
                                <i class="bi bi-send-plus-fill text-danger"></i>
                                <span class="fw-bold small">Catat Keluar</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="../siswa/tambah.php" class="action-btn">
                                <i class="bi bi-person-plus-fill text-primary"></i>
                                <span class="fw-bold small">Tambah Siswa</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="../ptk/tambah.php" class="action-btn">
                                <i class="bi bi-person-badge-fill text-info"></i>
                                <span class="fw-bold small">Tambah Guru</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- 1. SCRIPT JAM & SAPAAN REAL-TIME ---
    function updateClock() {
        const now = new Date();
        
        // A. Update Jam (HH:MM)
        const jam = now.getHours().toString().padStart(2, '0');
        const menit = now.getMinutes().toString().padStart(2, '0');
        document.getElementById('realtimeClock').innerText = `${jam}:${menit}`;

        // B. Update Tanggal (Senin, 01 Januari 2024)
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('realtimeDate').innerText = now.toLocaleDateString('id-ID', options);

        // C. Update Sapaan Otomatis (Pagi/Siang/Sore/Malam)
        const hour = now.getHours();
        let sapaan = '';
        let iconClass = '';

        if (hour >= 3 && hour < 10) { 
            sapaan = "Selamat Pagi"; 
            iconClass = "bi-sunrise-fill text-warning";
        } else if (hour >= 10 && hour < 15) { 
            sapaan = "Selamat Siang"; 
            iconClass = "bi-sun-fill text-warning";
        } else if (hour >= 15 && hour < 18) { 
            sapaan = "Selamat Sore"; 
            iconClass = "bi-sunset-fill text-warning";
        } else { 
            sapaan = "Selamat Malam"; 
            iconClass = "bi-moon-stars-fill text-info";
        }

        document.getElementById('sapaanText').innerText = sapaan;
        document.getElementById('sapaanIcon').className = `bi ${iconClass} me-2`;
    }

    // Jalankan fungsi setiap 1 detik
    setInterval(updateClock, 1000);
    // Jalankan sekali saat load agar tidak menunggu 1 detik
    updateClock();


    // --- 2. SCRIPT GRAFIK (SAMA SEPERTI SEBELUMNYA) ---
    const ctxSurat = document.getElementById('chartSurat').getContext('2d');
    
    let gradientMasuk = ctxSurat.createLinearGradient(0, 0, 0, 400);
    gradientMasuk.addColorStop(0, 'rgba(25, 135, 84, 0.5)');
    gradientMasuk.addColorStop(1, 'rgba(25, 135, 84, 0.0)');

    let gradientKeluar = ctxSurat.createLinearGradient(0, 0, 0, 400);
    gradientKeluar.addColorStop(0, 'rgba(220, 53, 69, 0.5)');
    gradientKeluar.addColorStop(1, 'rgba(220, 53, 69, 0.0)');

    new Chart(ctxSurat, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Surat Masuk',
                data: <?= json_encode(array_values($data_masuk_per_bulan)) ?>,
                borderColor: '#198754',
                backgroundColor: gradientMasuk,
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#198754'
            }, {
                label: 'Surat Keluar',
                data: <?= json_encode(array_values($data_keluar_per_bulan)) ?>,
                borderColor: '#dc3545',
                backgroundColor: gradientKeluar,
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#dc3545'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', align: 'end' },
                tooltip: {
                    mode: 'index', intersect: false,
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#333',
                    bodyColor: '#333',
                    borderColor: '#ddd',
                    borderWidth: 1
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                x: { grid: { display: false } }
            }
        }
    });

    const ctxSiswa = document.getElementById('chartSiswa').getContext('2d');
    new Chart(ctxSiswa, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Lulus', 'Pindah', 'Keluar'],
            datasets: [{
                data: [<?= $stat_aktif ?>, <?= $stat_lulus ?>, <?= $stat_pindah ?>, <?= $stat_keluar ?>],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });
</script>

<?php require_once '../layouts/footer.php'; ?>