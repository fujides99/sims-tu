<?php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

$stmt = $pdo->query("SELECT * FROM surat_keluar ORDER BY tgl_surat DESC, id DESC");
$surat = $stmt->fetchAll();

require_once '../layouts/header.php';
?>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Surat Keluar</h5>
            <a href="tambah.php" class="btn btn-danger btn-sm"><i class="bi bi-plus-lg"></i> Catat Surat Keluar</a>
        </div>
        <div class="card-body">
            
            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses_tambah'): ?>
                <div class="alert alert-success alert-dismissible fade show">Surat Keluar berhasil dicatat!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses_hapus'): ?>
                <div class="alert alert-warning alert-dismissible fade show">Data berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable align-middle">
                    <thead class="table-secondary"> <tr>
                            <th>Agenda</th>
                            <th>No. Surat</th>
                            <th>Tujuan</th>
                            <th>Perihal</th>
                            <th>Tgl Surat</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($surat as $row): ?>
                        <tr>
                            <td class="text-center fw-bold"><?= htmlspecialchars($row['no_agenda']) ?></td>
                            <td><?= htmlspecialchars($row['no_surat']) ?></td>
                            <td><?= htmlspecialchars($row['tujuan']) ?></td>
                            <td><?= htmlspecialchars($row['perihal']) ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tgl_surat'])) ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['file_path'])): ?>
                                    <a href="../../public/uploads/surat_keluar/<?= $row['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-danger">Lihat</a>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                            <td>
                                <a href="../../config/app/surat_keluar/proses_surat.php?aksi=hapus&id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirm('Hapus surat keluar ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>