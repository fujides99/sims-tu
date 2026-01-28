<?php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

try {
    $q_siswa = $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_siswa = 'Aktif'");
    $jml_siswa = $q_siswa->fetchColumn();

    $q_ptk = $pdo->query("SELECT COUNT(*) FROM ptk WHERE status_aktif = 'Aktif'");
    $jml_ptk = $q_ptk->fetchColumn();

    $q_masuk = $pdo->query("SELECT COUNT(*) FROM surat_masuk");
    $jml_masuk = $q_masuk->fetchColumn();

    $q_keluar = $pdo->query("SELECT COUNT(*) FROM surat_keluar");
    $jml_keluar = $q_keluar->fetchColumn();

    $q_recent = $pdo->query("SELECT * FROM surat_masuk ORDER BY id DESC LIMIT 5");
    $recent_surat = $q_recent->fetchAll();

} catch (PDOException $e) {
    $jml_siswa = $jml_ptk = $jml_masuk = $jml_keluar = 0;
    $recent_surat = [];
}

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

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i> 5 Surat Masuk Terakhir</h6>
                    <a href="../surat_masuk/index.php" class="btn btn-sm btn-outline-secondary">Semua</a>
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

<?php
require_once '../layouts/footer.php';
?>