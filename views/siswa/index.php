<?php
// views/siswa/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// Ambil data siswa (Query tetap sama)
$stmt = $pdo->query("SELECT * FROM siswa ORDER BY kelas_sekarang ASC, nama_lengkap ASC");
$siswa = $stmt->fetchAll();

// [UBAHAN 1]
// HAPUS semua kode HTML dari <!DOCTYPE html> sampai </nav>
// GANTI dengan ini untuk memanggil Header & Sidebar otomatis:
require_once '../layouts/header.php';
?>

<div class="container-fluid"> <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Siswa</h5>
            <div class="d-flex gap-2">
                 <a href="../laporan/cetak_siswa.php" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="bi bi-printer"></i> Cetak
                </a>
                <a href="tambah.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Siswa
                </a>
            </div>
        </div>
        <div class="card-body">
            
            <?php if (isset($_GET['pesan'])): ?>
                <?php if ($_GET['pesan'] == 'sukses_tambah'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Data berhasil ditambahkan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['pesan'] == 'sukses_edit'): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        Data berhasil diperbarui!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['pesan'] == 'sukses_hapus'): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Data berhasil dihapus!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>L/P</th>
                            <th>Kelas</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($siswa as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nis']) ?></td>
                            <td><?= htmlspecialchars($row['nisn']) ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td class="text-center"><?= $row['jk'] ?></td>
                            <td class="text-center"><span class="badge bg-secondary"><?= htmlspecialchars($row['kelas_sekarang']) ?></span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../../config/app/siswa/proses_siswa.php?aksi=hapus&id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus data <?= htmlspecialchars($row['nama_lengkap']) ?>? Data yang dihapus tidak bisa dikembalikan.');"
                                       title="Hapus">
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
// HAPUS penutup body & script manual
// GANTI dengan ini untuk memanggil Footer & DataTables JS:
require_once '../layouts/footer.php';
?>