<?php
// views/surat_keluar/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// Ambil data surat keluar
$stmt = $pdo->query("SELECT * FROM surat_keluar ORDER BY tgl_surat DESC, id DESC");
$surat = $stmt->fetchAll();

require_once '../layouts/header.php';
?>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-danger"><i class="bi bi-arrow-left-circle me-2"></i> Arsip Surat Keluar</h5>
            
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-danger btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalCetak">
                    <i class="bi bi-printer me-1"></i> Cetak Agenda
                </button>

                <a href="tambah.php" class="btn btn-danger btn-sm fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> Catat Surat
                </a>
            </div>
        </div>
        <div class="card-body">
            
            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses_tambah'): ?>
                <div class="alert alert-success alert-dismissible fade show">Surat Keluar berhasil dicatat!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses_hapus'): ?>
                <div class="alert alert-warning alert-dismissible fade show">Data berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead class="table-danger"> <tr>
                            <th>Agenda</th>
                            <th>No. Surat</th>
                            <th>Tujuan / Penerima</th>
                            <th>Perihal</th>
                            <th>Tgl Surat</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($surat as $row): ?>
                        <tr>
                            <td class="text-center fw-bold text-danger">#<?= htmlspecialchars($row['no_agenda']) ?></td>
                            <td><?= htmlspecialchars($row['no_surat']) ?></td>
                            <td><?= htmlspecialchars($row['tujuan']) ?></td> 
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 250px;">
                                    <?= htmlspecialchars($row['perihal']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($row['tgl_surat'])) ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['file_path'])): ?>
                                    <a href="../../public/uploads/surat_keluar/<?= $row['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="bi bi-file-earmark-text me-1"></i> File
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="../../config/app/surat_keluar/proses_surat.php?aksi=hapus&id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus surat keluar ini?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCetak" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-printer me-2"></i> Cetak Agenda Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../config/app/surat_keluar/cetak_agenda.php" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="alert alert-light border border-danger border-start-0 border-end-0 border-top-0 text-danger small">
                        <i class="bi bi-info-circle me-1"></i> Pilih periode surat keluar yang ingin dicetak.
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold mb-1">Dari Tanggal</label>
                        <input type="date" name="tgl_awal" class="form-control" required value="<?= date('Y-m-01') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold mb-1">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" class="form-control" required value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="bi bi-printer me-1"></i> Cetak Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>