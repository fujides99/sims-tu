<?php
// views/ptk/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// 1. DATA UTAMA
$stmt = $pdo->query("SELECT * FROM ptk ORDER BY nama_lengkap ASC");
$ptk = $stmt->fetchAll();

// 2. LOGIKA REKAPITULASI (Untuk Modal Rekap)
// Kita kelompokkan berdasarkan Status Kepegawaian (PNS, GTY, dll)
$rekap_data = [];
$total_sekolah = ['L' => 0, 'P' => 0, 'Total' => 0];

// Loop data untuk menghitung
foreach ($ptk as $row) {
    if ($row['status_aktif'] == 'Aktif') { // Hanya hitung yang aktif
        $status = $row['status_kepegawaian'];
        $jk = $row['jk'];

        if (!isset($rekap_data[$status])) {
            $rekap_data[$status] = ['L' => 0, 'P' => 0, 'Total' => 0];
        }

        $rekap_data[$status][$jk]++;
        $rekap_data[$status]['Total']++;

        $total_sekolah[$jk]++;
        $total_sekolah['Total']++;
    }
}

require_once '../layouts/header.php';
?>

<style>
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .modal-header, .modal-footer { display: none !important; } 
    }
    .table-rekap th { background-color: #dbeeff !important; text-align: center; vertical-align: middle; }
    .bg-total { background-color: #8db4e2 !important; font-weight: bold; }
</style>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-person-workspace"></i> Data PTK (Guru & Staff)</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalReset">
                    <i class="bi bi-trash3-fill"></i> Reset DB
                </button>
                <a href="../../config/app/ptk/export_excel.php" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </a>
                <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-upload"></i> Import CSV
                </button>
                <button type="button" class="btn btn-info btn-sm text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalRekap">
                    <i class="bi bi-printer"></i> Rekap
                </button>
                <a href="tambah.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah
                </a>
            </div>
        </div>
        <div class="card-body">
            
            <?php if (isset($_GET['pesan'])): ?>
                <?php if ($_GET['pesan'] == 'sukses_tambah'): ?>
                    <div class="alert alert-success alert-dismissible fade show">Data berhasil ditambahkan!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_edit'): ?>
                    <div class="alert alert-info alert-dismissible fade show">Data berhasil diperbarui!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_hapus'): ?>
                    <div class="alert alert-warning alert-dismissible fade show">Data berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_import'): ?>
                    <div class="alert alert-success alert-dismissible fade show">Import Berhasil!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_reset'): ?>
                    <div class="alert alert-success alert-dismissible fade show fw-bold">DATABASE PTK BERHASIL DI-RESET.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'gagal_password'): ?>
                    <div class="alert alert-danger alert-dismissible fade show fw-bold">GAGAL RESET: Password Admin Salah!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>NIP / NUPTK</th>
                            <th>L/P</th>
                            <th>Jenis PTK</th>
                            <th>Status Pegawai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($ptk as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <span class="fw-bold"><?= htmlspecialchars($row['nama_lengkap']) ?></span>
                                <?php if($row['gelar_belakang']) echo ', '.htmlspecialchars($row['gelar_belakang']); ?>
                            </td>
                            <td>
                                <?php if($row['nip']): ?>
                                    <small class="d-block">NIP: <?= htmlspecialchars($row['nip']) ?></small>
                                <?php endif; ?>
                                <?php if($row['nuptk']): ?>
                                    <small class="d-block text-muted">NUPTK: <?= htmlspecialchars($row['nuptk']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= $row['jk'] ?></td>
                            <td><?= htmlspecialchars($row['jenis_ptk']) ?></td>
                            <td><?= htmlspecialchars($row['status_kepegawaian']) ?></td>
                            <td class="text-center">
                                <?php if($row['status_aktif'] == 'Aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= $row['status_aktif'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info text-white btn-detail"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail"
                                        data-nama="<?= $row['nama_lengkap'] ?>"
                                        data-nuptk="<?= $row['nuptk'] ?>"
                                        data-nip="<?= $row['nip'] ?>"
                                        data-jk="<?= $row['jk'] ?>"
                                        data-tempat="<?= $row['tempat_lahir'] ?>"
                                        data-tgl="<?= $row['tgl_lahir'] ? date('d-m-Y', strtotime($row['tgl_lahir'])) : '-' ?>"
                                        data-statuspeg="<?= $row['status_kepegawaian'] ?>"
                                        data-jenis="<?= $row['jenis_ptk'] ?>"
                                        data-gelardepan="<?= $row['gelar_depan'] ?>"
                                        data-gelarbelakang="<?= $row['gelar_belakang'] ?>"
                                        data-pendidikan="<?= $row['jenjang_pendidikan'] ?>"
                                        data-jurusan="<?= $row['jurusan_prodi'] ?>"
                                        data-sertifikasi="<?= $row['sertifikasi'] ?>"
                                        data-tmt="<?= $row['tmt_kerja'] ? date('d-m-Y', strtotime($row['tmt_kerja'])) : '-' ?>"
                                        data-aktif="<?= $row['status_aktif'] ?>"
                                    ><i class="bi bi-eye"></i></button>

                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                                    
                                    <a href="../../config/app/ptk/proses_ptk.php?aksi=hapus&id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin hapus data PTK ini?');"><i class="bi bi-trash"></i></a>
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

<div class="modal fade" id="modalRekap" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content print-area">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Rekapitulasi Data PTK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <h4 class="text-center fw-bold mb-4">REKAPITULASI JUMLAH PTK BERDASARKAN STATUS KEPEGAWAIAN</h4>
                <table class="table table-bordered table-rekap w-100">
                    <thead>
                        <tr>
                            <th rowspan="2" width="5%">NO</th>
                            <th rowspan="2">STATUS KEPEGAWAIAN</th>
                            <th colspan="2">JENIS KELAMIN</th>
                            <th rowspan="2" width="15%">JUMLAH</th>
                        </tr>
                        <tr>
                            <th>L</th> <th>P</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($rekap_data as $status => $d): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $status ?></td>
                            <td class="text-center"><?= $d['L'] ?></td>
                            <td class="text-center"><?= $d['P'] ?></td>
                            <td class="text-center fw-bold"><?= $d['Total'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="bg-total">
                            <td colspan="2" class="text-center">TOTAL KESELURUHAN</td>
                            <td class="text-center"><?= $total_sekolah['L'] ?></td>
                            <td class="text-center"><?= $total_sekolah['P'] ?></td>
                            <td class="text-center"><?= $total_sekolah['Total'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">Cetak Rekap</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail PTK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary fw-bold border-bottom pb-2">Identitas Diri</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="35%">Nama Lengkap</td><td class="fw-bold">: <span id="d_nama"></span></td></tr>
                            <tr><td>Gelar Depan</td><td>: <span id="d_gelardepan"></span></td></tr>
                            <tr><td>Gelar Belakang</td><td>: <span id="d_gelarbelakang"></span></td></tr>
                            <tr><td>NIP</td><td>: <span id="d_nip"></span></td></tr>
                            <tr><td>NUPTK</td><td>: <span id="d_nuptk"></span></td></tr>
                            <tr><td>Jenis Kelamin</td><td>: <span id="d_jk"></span></td></tr>
                            <tr><td>TTL</td><td>: <span id="d_tempat"></span>, <span id="d_tgl"></span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary fw-bold border-bottom pb-2">Kepegawaian & Pendidikan</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="40%">Jenis PTK</td><td>: <span id="d_jenis"></span></td></tr>
                            <tr><td>Status Pegawai</td><td>: <span id="d_statuspeg"></span></td></tr>
                            <tr><td>TMT Kerja</td><td>: <span id="d_tmt"></span></td></tr>
                            <tr><td>Status Aktif</td><td>: <span id="d_aktif" class="badge bg-success"></span></td></tr>
                            <tr><td colspan="2" class="border-top pt-2"><strong>Pendidikan:</strong></td></tr>
                            <tr><td>Jenjang</td><td>: <span id="d_pendidikan"></span></td></tr>
                            <tr><td>Jurusan/Prodi</td><td>: <span id="d_jurusan"></span></td></tr>
                            <tr><td>Sertifikasi</td><td>: <span id="d_sertifikasi"></span></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data PTK (CSV)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../config/app/ptk/proses_import.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-secondary small">
                        <strong>Urutan Kolom CSV (15 Kolom):</strong><br>
                        Nama, NUPTK, NIP, JK, Tmpt Lhr, Tgl Lhr, Status Peg, Jenis PTK, Gelar Dpn, Gelar Blk, Pendidikan, Jurusan, Sertifikasi, TMT, Status Aktif.
                    </div>
                    <div class="mb-3">
                        <label>Pilih File CSV</label>
                        <input type="file" name="file_csv" class="form-control" required accept=".csv">
                    </div>
                    <input type="hidden" name="import" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReset" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">RESET DATABASE PTK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../config/app/ptk/proses_ptk.php?aksi=hapus_semua" method="POST">
                <div class="modal-body">
                    <div class="alert alert-warning text-center">
                        <strong>PERINGATAN!</strong><br>
                        Tindakan ini akan <b>MENGHAPUS SEMUA DATA PTK</b>.
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Masukkan Password Admin:</label>
                        <input type="password" name="password_konfirmasi" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold">YA, HAPUS SEMUA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('.btn-detail').click(function() {
            $('#d_nama').text($(this).data('nama'));
            $('#d_gelardepan').text($(this).data('gelardepan'));
            $('#d_gelarbelakang').text($(this).data('gelarbelakang'));
            $('#d_nip').text($(this).data('nip'));
            $('#d_nuptk').text($(this).data('nuptk'));
            $('#d_jk').text($(this).data('jk') == 'L' ? 'Laki-laki' : 'Perempuan');
            $('#d_tempat').text($(this).data('tempat'));
            $('#d_tgl').text($(this).data('tgl'));
            $('#d_jenis').text($(this).data('jenis'));
            $('#d_statuspeg').text($(this).data('statuspeg'));
            $('#d_tmt').text($(this).data('tmt'));
            $('#d_aktif').text($(this).data('aktif'));
            $('#d_pendidikan').text($(this).data('pendidikan'));
            $('#d_jurusan').text($(this).data('jurusan'));
            $('#d_sertifikasi').text($(this).data('sertifikasi'));
        });
    });
</script>