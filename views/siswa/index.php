<?php
// views/siswa/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// Ambil data siswa
$stmt = $pdo->query("SELECT * FROM siswa ORDER BY kelas_sekarang ASC, nama_lengkap ASC");
$siswa = $stmt->fetchAll();

// Panggil Header & Sidebar
require_once '../layouts/header.php';
?>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Siswa</h5>
            <div class="d-flex gap-2">
                
                <a href="../../config/app/siswa/export_excel.php" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </a>

                <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-upload"></i> Import CSV
                </button>

                <a href="../laporan/cetak_siswa.php" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="bi bi-printer"></i> Cetak
                </a>

                <a href="tambah.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah
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
                <?php elseif ($_GET['pesan'] == 'sukses_import'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Data dari CSV berhasil diimport!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['pesan'] == 'gagal_import'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Gagal Import! Pastikan file format .csv
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
                            <th>Agama</th>
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
                            <td><?= htmlspecialchars($row['agama']) ?></td>
                            <td class="text-center"><span class="badge bg-secondary"><?= htmlspecialchars($row['kelas_sekarang']) ?></span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../../config/app/siswa/proses_siswa.php?aksi=hapus&id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin hapus data <?= htmlspecialchars($row['nama_lengkap']) ?>?');">
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

<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Siswa (CSV)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../config/app/siswa/proses_import.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <strong>Petunjuk:</strong><br>
                        1. Gunakan file format <b>.csv</b> (Comma Delimited).<br>
                        2. Urutan Kolom (tanpa judul header):<br>
                        <b>NIS, NISN, Nama, JK (L/P), Agama, Kelas, Thn Masuk, Tgl Lahir (YYYY-MM-DD)</b>
                    </div>
                    <div class="mb-3">
                        <label>Pilih File CSV</label>
                        <input type="file" name="file_csv" class="form-control" required accept=".csv">
                    </div>
                    <input type="hidden" name="import" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Panggil Footer (Tutup HTML & Script DataTables)
require_once '../layouts/footer.php'; 
?>