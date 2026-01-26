<?php
// views/surat/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// Ambil data surat
$stmt = $pdo->query("SELECT * FROM surat_masuk ORDER BY tgl_diterima DESC, id DESC");
$surat = $stmt->fetchAll();

// [UBAHAN 1]
// HAPUS semua kode HTML dari <!DOCTYPE html> sampai </nav>
// GANTI dengan baris ini untuk memanggil Header & Sidebar otomatis:
require_once '../layouts/header.php';
?>

<div class="container-fluid"> <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Buku Agenda Surat Masuk</h5>
            <a href="tambah.php" class="btn btn-success btn-sm"><i class="bi bi-plus-lg"></i> Input Surat Baru</a>
        </div>
        <div class="card-body">
            
            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data surat berhasil disimpan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle datatable">
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
                                    <a href="../../public/uploads/surat/<?= $row['file_path'] ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark-text"></i> Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <a href="../../config/app/surat/proses_surat.php?aksi=hapus&id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus surat dari <?= htmlspecialchars($row['pengirim']) ?>?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// [UBAHAN 3]
// HAPUS penutup </body> dan <script> manual
// GANTI dengan baris ini untuk memanggil Footer & DataTables JS:
require_once '../layouts/footer.php';
?>