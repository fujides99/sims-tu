<?php
// views/surat_masuk/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// Ambil data surat masuk
$stmt = $pdo->query("SELECT * FROM surat_masuk ORDER BY tgl_diterima DESC, id DESC");
$surat = $stmt->fetchAll();

require_once '../layouts/header.php';
?>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Surat Masuk</h5>
            <a href="tambah.php" class="btn btn-success btn-sm"><i class="bi bi-plus-lg"></i> Input Surat</a>
        </div>
        <div class="card-body">
            
            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show">Surat berhasil disimpan!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>No Agenda</th>
                            <th>No. Surat</th>
                            <th>Pengirim</th>
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
                            <td><?= htmlspecialchars($row['pengirim']) ?></td>
                            <td><?= htmlspecialchars($row['perihal']) ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tgl_surat'])) ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['file_path'])): ?>
                                    <a href="../../public/uploads/surat_masuk/<?= $row['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="../../config/app/surat_masuk/proses_surat.php?aksi=hapus&id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirm('Hapus surat ini?')"><i class="bi bi-trash"></i></a>
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