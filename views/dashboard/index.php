<?php
// views/dashboard/index.php

// --- 1. CONFIG & SESSION ---
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// --- 2. LOGIC BACKEND (Query Statistik & Grafik) ---
try {
    // A. Hitung Total Data (Widget Atas)
    $jml_siswa = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif'")->fetchColumn();
    $jml_ptk   = $pdo->query("SELECT COUNT(*) FROM ptk WHERE status_aktif = 'Aktif'")->fetchColumn();
    $jml_masuk = $pdo->query("SELECT COUNT(*) FROM surat_masuk")->fetchColumn();
    $jml_keluar= $pdo->query("SELECT COUNT(*) FROM surat_keluar")->fetchColumn();

    // B. Ambil 5 Surat Masuk Terakhir
    $recent_surat = $pdo->query("SELECT * FROM surat_masuk ORDER BY id DESC LIMIT 5")->fetchAll();

    // C. LOGIC GRAFIK: SURAT PER BULAN (Tahun Ini)
    $tahun_ini = date('Y');
    
    // Siapkan array kosong untuk jan-des (isi 0 semua)
    $data_masuk_per_bulan = array_fill(1, 12, 0); 
    $data_keluar_per_bulan = array_fill(1, 12, 0);

    // Query Surat Masuk Group By Bulan
    $sql_m = "SELECT MONTH(tgl_diterima) as bulan, COUNT(*) as total 
              FROM surat_masuk WHERE YEAR(tgl_diterima) = '$tahun_ini' 
              GROUP BY bulan";
    foreach ($pdo->query($sql_m) as $row) {
        $data_masuk_per_bulan[$row['bulan']] = $row['total'];
    }

    // Query Surat Keluar Group By Bulan
    $sql_k = "SELECT MONTH(tgl_surat) as bulan, COUNT(*) as total 
              FROM surat_keluar WHERE YEAR(tgl_surat) = '$tahun_ini' 
              GROUP BY bulan";
    foreach ($pdo->query($sql_k) as $row) {
        $data_keluar_per_bulan[$row['bulan']] = $row['total'];
    }

    // D. LOGIC GRAFIK: STATUS SISWA
    // Kita hitung jumlah siswa berdasarkan statusnya
    $stat_aktif = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif'")->fetchColumn();
    $stat_lulus = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Lulus'")->fetchColumn();
    $stat_pindah= $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Pindah'")->fetchColumn();
    $stat_keluar= $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Keluar'")->fetchColumn();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Helper Tanggal Indonesia
$hari_ini = date('Y-m-d');
$hari = ['Sun'=>'Minggu', 'Mon'=>'Senin', 'Tue'=>'Selasa', 'Wed'=>'Rabu', 'Thu'=>'Kamis', 'Fri'=>'Jumat', 'Sat'=>'Sabtu'];
$bulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
$tgl_indo = $hari[date('D')].", ".date('d')." ".$bulan[date('m')]." ".date('Y');

require_once '../layouts/header.php';
?>

<div class="container-fluid px-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden" style="border-radius: 1rem;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>! 👋</h2>
                            <p class="mb-0 opacity-75">
                                Anda login sebagai <strong><?= ucfirst($_SESSION['role']); ?></strong>. 
                            </p>
                        </div>
                        <div class="text-end d-none d-md-block">
                            <h5 class="mb-0 fw-bold opacity-75"><?= $tgl_indo ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary"><i class="bi bi-people-fill fs-3"></i></div>
                    <div><h6 class="text-muted mb-0 small fw-bold">Siswa Aktif</h6><h3 class="fw-bold mb-0"><?= number_format($jml_siswa) ?></h3></div>
                </div>
                <div class="card-footer bg-white border-0 py-2"><a href="../siswa/index.php" class="text-decoration-none small fw-bold text-primary">Detail <i class="bi bi-arrow-right"></i></a></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3 text-info"><i class="bi bi-person-workspace fs-3"></i></div>
                    <div><h6 class="text-muted mb-0 small fw-bold">Guru & Staff</h6><h3 class="fw-bold mb-0"><?= number_format($jml_ptk) ?></h3></div>
                </div>
                <div class="card-footer bg-white border-0 py-2"><a href="../ptk/index.php" class="text-decoration-none small fw-bold text-info">Detail <i class="bi bi-arrow-right"></i></a></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3 text-success"><i class="bi bi-envelope-check-fill fs-3"></i></div>
                    <div><h6 class="text-muted mb-0 small fw-bold">Surat Masuk</h6><h3 class="fw-bold mb-0"><?= number_format($jml_masuk) ?></h3></div>
                </div>
                <div class="card-footer bg-white border-0 py-2"><a href="../surat_masuk/index.php" class="text-decoration-none small fw-bold text-success">Arsip <i class="bi bi-arrow-right"></i></a></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3 text-danger"><i class="bi bi-send-fill fs-3"></i></div>
                    <div><h6 class="text-muted mb-0 small fw-bold">Surat Keluar</h6><h3 class="fw-bold mb-0"><?= number_format($jml_keluar) ?></h3></div>
                </div>
                <div class="card-footer bg-white border-0 py-2"><a href="../surat_keluar/index.php" class="text-decoration-none small fw-bold text-danger">Arsip <i class="bi bi-arrow-right"></i></a></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-fill me-2"></i> Statistik Surat Tahun <?= $tahun_ini ?></h6>
                </div>
                <div class="card-body">
                    <canvas id="chartSurat" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill me-2"></i> Status Siswa</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center position-relative">
                    <canvas id="chartSiswa" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i> Aktivitas Surat Masuk Terbaru</h6>
                    <a href="../surat_masuk/index.php" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr><th class="ps-4">No. Agenda</th><th>Pengirim</th><th>Perihal</th><th>Tgl Terima</th></tr>
                            </thead>
                            <tbody>
                                <?php if(count($recent_surat) > 0): ?>
                                    <?php foreach($recent_surat as $row): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">#<?= $row['no_agenda'] ?></td>
                                        <td><?= htmlspecialchars($row['pengirim']) ?></td>
                                        <td><span class="d-inline-block text-truncate" style="max-width: 200px;"><?= htmlspecialchars($row['perihal']) ?></span></td>
                                        <td><?= date('d/m/Y', strtotime($row['tgl_diterima'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i> Akses Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="../surat_masuk/tambah.php" class="btn btn-outline-success text-start p-3"><i class="bi bi-plus-circle-fill me-2"></i> Input Surat Masuk</a>
                        <a href="../surat_keluar/tambah.php" class="btn btn-outline-danger text-start p-3"><i class="bi bi-plus-circle-fill me-2"></i> Catat Surat Keluar</a>
                        <a href="../siswa/tambah.php" class="btn btn-outline-primary text-start p-3"><i class="bi bi-person-plus-fill me-2"></i> Tambah Siswa</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. DATA DARI PHP KE JS
    const dataMasuk = <?= json_encode(array_values($data_masuk_per_bulan)) ?>;
    const dataKeluar = <?= json_encode(array_values($data_keluar_per_bulan)) ?>;
    
    // 2. CHART BATANG (SURAT)
    const ctxSurat = document.getElementById('chartSurat').getContext('2d');
    new Chart(ctxSurat, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Surat Masuk',
                data: dataMasuk,
                backgroundColor: 'rgba(25, 135, 84, 0.7)', // Hijau
                borderColor: 'rgba(25, 135, 84, 1)',
                borderWidth: 1
            }, {
                label: 'Surat Keluar',
                data: dataKeluar,
                backgroundColor: 'rgba(220, 53, 69, 0.7)', // Merah
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 3. CHART DONUT (SISWA)
    const ctxSiswa = document.getElementById('chartSiswa').getContext('2d');
    new Chart(ctxSiswa, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Lulus', 'Pindah', 'Keluar'],
            datasets: [{
                data: [<?= $stat_aktif ?>, <?= $stat_lulus ?>, <?= $stat_pindah ?>, <?= $stat_keluar ?>],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'], // Biru, Hijau, Kuning, Merah
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

<?php
require_once '../layouts/footer.php';
?>