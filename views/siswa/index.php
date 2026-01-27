<?php
// views/siswa/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// 1. AMBIL DATA SISWA UTAMA (Untuk Tabel Daftar Siswa)
$stmt = $pdo->query("SELECT * FROM siswa ORDER BY kelas_sekarang ASC, nama_lengkap ASC");
$siswa = $stmt->fetchAll();

// 2. LOGIKA REKAPITULASI CANGGIH
// Kita ambil data ringkas
$sql_rekap = "SELECT kelas_sekarang, jk, agama FROM siswa WHERE status_siswa = 'Aktif' ORDER BY kelas_sekarang ASC";
$stmt_rekap = $pdo->query($sql_rekap);
$data_mentah = $stmt_rekap->fetchAll();

// Struktur Penampung Data
// Kita bagi langsung menjadi 3 Tingkat agar mudah membuat Subtotal Gabungan
$data_tingkat = [
    '7' => [], // Menampung kelas 7.1, 7.2, dst
    '8' => [],
    '9' => []
];

// Variabel Grand Total Sekolah
$total_sekolah = [
    'Islam_L' => 0, 'Islam_P' => 0, 
    'Non_L' => 0, 'Non_P' => 0, 
    'Total_L' => 0, 'Total_P' => 0, 'Grand_Total' => 0
];

foreach ($data_mentah as $row) {
    $kls_nama = $row['kelas_sekarang'];
    $jk = $row['jk'];
    $agama = $row['agama'];

    // Deteksi Tingkat (Ambil angka pertama dari nama kelas, misal "7.1" -> "7")
    // Pastikan penamaan kelas di database mengandung angka 7, 8, atau 9
    if (strpos($kls_nama, '7') !== false) $tingkat = '7';
    elseif (strpos($kls_nama, '8') !== false) $tingkat = '8';
    elseif (strpos($kls_nama, '9') !== false) $tingkat = '9';
    else $tingkat = 'Lain'; // Jaga-jaga jika ada kelas lain

    // Jika tingkat valid (7,8,9), masukkan ke array
    if (isset($data_tingkat[$tingkat])) {
        // Inisialisasi array per rombel jika belum ada
        if (!isset($data_tingkat[$tingkat][$kls_nama])) {
            $data_tingkat[$tingkat][$kls_nama] = [
                'Islam_L' => 0, 'Islam_P' => 0,
                'Non_L' => 0, 'Non_P' => 0,
                'Total_L' => 0, 'Total_P' => 0,
                'Total_Kelas' => 0
            ];
        }

        // Hitung Data Per Rombel
        if ($agama == 'Islam') {
            $data_tingkat[$tingkat][$kls_nama]['Islam_' . $jk]++;
            $total_sekolah['Islam_' . $jk]++;
        } else {
            $data_tingkat[$tingkat][$kls_nama]['Non_' . $jk]++;
            $total_sekolah['Non_' . $jk]++;
        }

        $data_tingkat[$tingkat][$kls_nama]['Total_' . $jk]++;
        $data_tingkat[$tingkat][$kls_nama]['Total_Kelas']++;
        
        // Hitung Grand Total Sekolah
        $total_sekolah['Total_' . $jk]++;
        $total_sekolah['Grand_Total']++;
    }
}

// Fungsi Helper untuk Menghitung Subtotal Per Tingkat
function hitungSubtotal($array_kelas) {
    $sub = ['Islam_L'=>0, 'Islam_P'=>0, 'Non_L'=>0, 'Non_P'=>0, 'Total_L'=>0, 'Total_P'=>0, 'Grand'=>0];
    foreach ($array_kelas as $kls) {
        $sub['Islam_L'] += $kls['Islam_L'];
        $sub['Islam_P'] += $kls['Islam_P'];
        $sub['Non_L'] += $kls['Non_L'];
        $sub['Non_P'] += $kls['Non_P'];
        $sub['Total_L'] += $kls['Total_L'];
        $sub['Total_P'] += $kls['Total_P'];
        $sub['Grand']   += $kls['Total_Kelas'];
    }
    return $sub;
}

require_once '../layouts/header.php';
?>

<style>
    /* Styling Tabel Rekap agar Rapi */
    .table-rekap { width: 100%; border-collapse: collapse; font-family: 'Arial', sans-serif; }
    .table-rekap th, .table-rekap td {
        border: 1px solid #000; padding: 6px; text-align: center; font-size: 14px;
    }
    .table-rekap thead th {
        background-color: #dbeeff !important; /* Biru Header */
        vertical-align: middle;
        font-weight: bold;
    }
    
    /* Warna Warni Baris */
    .bg-orange { background-color: #ffcc99 !important; }
    .bg-yellow { background-color: #ffff99 !important; }
    .bg-green { background-color: #c4d79b !important; }
    .bg-total { background-color: #8db4e2 !important; font-weight: bold; } /* Biru Total */
    .bg-subtotal { background-color: #e6e6e6 !important; font-weight: bold; font-style: italic; } /* Abu Subtotal */

    /* PENGATURAN CETAK (PRINT) */
    @media print {
        /* Sembunyikan semua elemen kecuali area print */
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        
        /* Posisikan area print di pojok kiri atas */
        .print-area {
            position: absolute; left: 0; top: 0; width: 100%;
        }

        /* Hilangkan bayangan modal, scroll, dan border default bootstrap */
        .modal-dialog { margin: 0; max-width: 100%; }
        .modal-content { border: none; box-shadow: none; }
        .modal-header .btn-close, .modal-footer { display: none; } /* Hilangkan tombol tutup & print saat ngeprint */
        
        /* Paksa browser mencetak background color */
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        
        /* Ukuran Kertas */
        @page { size: landscape; margin: 10mm; }
    }
    
    /* Kop Surat hanya muncul saat print */
    .kop-surat { display: none; text-align: center; margin-bottom: 20px; }
    @media print { .kop-surat { display: block; } }
</style>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Siswa</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalReset">
                    <i class="bi bi-trash3-fill"></i> Reset DB
                </button>
                <a href="../../config/app/siswa/export_excel.php" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export
                </a>
                <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-upload"></i> Import CSV
                </button>
                <button type="button" class="btn btn-info btn-sm text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalRekap">
                    <i class="bi bi-printer"></i> Rekapitulasi
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
                    <div class="alert alert-success alert-dismissible fade show fw-bold">DATABASE BERHASIL DI-RESET.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'gagal_password'): ?>
                    <div class="alert alert-danger alert-dismissible fade show fw-bold">GAGAL RESET: Password Admin Salah!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIS / NISN</th>
                            <th>Nama Lengkap</th>
                            <th>L/P</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($siswa as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <span class="fw-bold"><?= htmlspecialchars($row['nis']) ?></span><br>
                                <small class="text-muted"><?= htmlspecialchars($row['nisn']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td class="text-center"><?= $row['jk'] ?></td>
                            <td class="text-center"><span class="badge bg-primary"><?= htmlspecialchars($row['kelas_sekarang']) ?></span></td>
                            <td class="text-center">
                                <?php if($row['status_siswa'] == 'Aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($row['status_siswa'] ?? 'Aktif') ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info text-white btn-detail"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail"
                                        data-nis="<?= $row['nis'] ?>"
                                        data-nisn="<?= $row['nisn'] ?>"
                                        data-nama="<?= $row['nama_lengkap'] ?>"
                                        data-jk="<?= $row['jk'] ?>"
                                        data-agama="<?= $row['agama'] ?>"
                                        data-tempat="<?= $row['tempat_lahir'] ?>"
                                        data-tgl="<?= date('d-m-Y', strtotime($row['tgl_lahir'])) ?>"
                                        data-alamat="<?= $row['alamat_siswa'] ?>"
                                        data-ayah="<?= $row['nama_ayah'] ?>"
                                        data-ibu="<?= $row['nama_ibu'] ?>"
                                        data-payah="<?= $row['pekerjaan_ayah'] ?>"
                                        data-pibu="<?= $row['pekerjaan_ibu'] ?>"
                                        data-hp="<?= $row['no_hp_ortu'] ?>"
                                        data-kelas="<?= $row['kelas_sekarang'] ?>"
                                        data-tahun="<?= $row['tahun_masuk'] ?>"
                                        data-status="<?= $row['status_siswa'] ?>"
                                    ><i class="bi bi-eye"></i></button>

                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                                    
                                    <a href="../../config/app/siswa/proses_siswa.php?aksi=hapus&id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin hapus data ini?');"><i class="bi bi-trash"></i></a>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content print-area"> <div class="modal-header">
                <h5 class="modal-title fw-bold">Rekapitulasi Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                
                <div class="kop-surat">
                    <h3 style="margin:0;">PEMERINTAH KABUPATEN CONTOH</h3>
                    <h2 style="margin:5px 0;">UPTD SMP NUSANTARA</h2>
                    <p style="margin:0;">Alamat: Jl. Pendidikan No. 123, Kota Pelajar. Telp: (021) 1234567</p>
                    <hr style="border: 2px solid black; margin-top: 10px;">
                </div>

                <h5 class="text-center fw-bold mb-3">JUMLAH SISWA DAN SISWI TAHUN PELAJARAN <?= date('Y') ?>/<?= date('Y')+1 ?></h5>
                
                <table class="table-rekap">
                    <thead>
                        <tr>
                            <th rowspan="2" width="5%">NO</th>
                            <th rowspan="2" width="20%">NAMA ROMBEL</th>
                            <th colspan="2">ISLAM</th>
                            <th colspan="2">BUDDHA / LAINNYA</th>
                            <th colspan="2">JUMLAH</th>
                            <th rowspan="2" width="10%">TOTAL</th>
                        </tr>
                        <tr>
                            <th>L</th> <th>P</th>
                            <th>L</th> <th>P</th>
                            <th>L</th> <th>P</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no_urut = 1;
                        // Loop Tingkat 7, 8, 9
                        foreach (['7' => 'bg-orange', '8' => 'bg-yellow', '9' => 'bg-green'] as $tingkat => $bg_color): 
                            
                            // Jika ada data di tingkat ini
                            if (!empty($data_tingkat[$tingkat])):
                                // Loop Kelas di dalam Tingkat ini
                                foreach ($data_tingkat[$tingkat] as $kelas => $d):
                        ?>
                                <tr class="<?= $bg_color ?>">
                                    <td><?= $no_urut++ ?></td>
                                    <td style="text-align: left; padding-left: 15px; font-weight: bold;"><?= htmlspecialchars($kelas) ?></td>
                                    <td><?= $d['Islam_L'] ?: '-' ?></td>
                                    <td><?= $d['Islam_P'] ?: '-' ?></td>
                                    <td><?= $d['Non_L'] ?: '-' ?></td>
                                    <td><?= $d['Non_P'] ?: '-' ?></td>
                                    <td class="fw-bold"><?= $d['Total_L'] ?></td>
                                    <td class="fw-bold"><?= $d['Total_P'] ?></td>
                                    <td class="fw-bold"><?= $d['Total_Kelas'] ?></td>
                                </tr>
                        <?php 
                                endforeach; // End Loop Kelas
                                
                                // --- BARIS SUBTOTAL (GABUNGAN) PER TINGKAT ---
                                $sub = hitungSubtotal($data_tingkat[$tingkat]);
                        ?>
                                <tr class="bg-subtotal">
                                    <td colspan="2">TOTAL KELAS <?= $tingkat ?></td>
                                    <td><?= $sub['Islam_L'] ?></td>
                                    <td><?= $sub['Islam_P'] ?></td>
                                    <td><?= $sub['Non_L'] ?></td>
                                    <td><?= $sub['Non_P'] ?></td>
                                    <td><?= $sub['Total_L'] ?></td>
                                    <td><?= $sub['Total_P'] ?></td>
                                    <td><?= $sub['Grand'] ?></td>
                                </tr>
                        <?php 
                            endif; 
                        endforeach; // End Loop Tingkat 
                        ?>
                        
                        <tr class="bg-total">
                            <td colspan="2" style="padding: 10px;">TOTAL KESELURUHAN</td>
                            <td><?= $total_sekolah['Islam_L'] ?></td>
                            <td><?= $total_sekolah['Islam_P'] ?></td>
                            <td><?= $total_sekolah['Non_L'] ?></td>
                            <td><?= $total_sekolah['Non_P'] ?></td>
                            <td><?= $total_sekolah['Total_L'] ?></td>
                            <td><?= $total_sekolah['Total_P'] ?></td>
                            <td style="font-size: 16px;"><?= $total_sekolah['Grand_Total'] ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="row mt-5 kop-surat"> <div class="col-4 text-center offset-8">
                        <p>Kota Pelajar, <?= date('d F Y') ?><br>Kepala Sekolah,</p>
                        <br><br><br>
                        <p class="fw-bold text-decoration-underline">NAMA KEPALA SEKOLAH</p>
                        <p>NIP. 19800101 200001 1 001</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Laporan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-person-lines-fill"></i> Detail Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary border-bottom pb-2">Data Pribadi</h6>
                        <table class="table table-borderless table-sm">
                            <tr><td width="35%">Nama</td><td>: <span id="d_nama" class="fw-bold"></span></td></tr>
                            <tr><td>NIS / NISN</td><td>: <span id="d_nis"></span> / <span id="d_nisn"></span></td></tr>
                            <tr><td>Jns Kelamin</td><td>: <span id="d_jk"></span></td></tr>
                            <tr><td>TTL</td><td>: <span id="d_tempat"></span>, <span id="d_tgl"></span></td></tr>
                            <tr><td>Agama</td><td>: <span id="d_agama"></span></td></tr>
                            <tr><td>Alamat</td><td>: <span id="d_alamat"></span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary border-bottom pb-2">Data Orang Tua</h6>
                        <table class="table table-borderless table-sm">
                            <tr><td width="35%">Nama Ayah</td><td>: <span id="d_ayah"></span></td></tr>
                            <tr><td>Pekerjaan</td><td>: <span id="d_payah"></span></td></tr>
                            <tr><td>Nama Ibu</td><td>: <span id="d_ibu"></span></td></tr>
                            <tr><td>Pekerjaan</td><td>: <span id="d_pibu"></span></td></tr>
                            <tr><td>No HP Ortu</td><td>: <span id="d_hp"></span></td></tr>
                        </table>
                        <h6 class="text-primary border-bottom pb-2 mt-3">Data Sekolah</h6>
                        <table class="table table-borderless table-sm">
                            <tr><td width="35%">Kelas</td><td>: <span id="d_kelas" class="badge bg-primary"></span></td></tr>
                            <tr><td>Thn Masuk</td><td>: <span id="d_tahun"></span></td></tr>
                            <tr><td>Status</td><td>: <span id="d_status" class="badge bg-success"></span></td></tr>
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

<div class="modal fade" id="modalReset" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">RESET DATABASE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../config/app/siswa/proses_siswa.php?aksi=hapus_semua" method="POST">
                <div class="modal-body">
                    <div class="alert alert-warning text-center">
                        <strong>PERINGATAN KERAS!</strong><br>
                        Tindakan ini akan <b>MENGHAPUS SEMUA DATA SISWA</b> secara permanen.
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Masukkan Password Login:</label>
                        <input type="password" name="password_konfirmasi" class="form-control" placeholder="Ketik password admin..." required>
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

<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Siswa (CSV)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../config/app/siswa/proses_import.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-secondary small">
                        <strong>Format CSV Wajib (14 Kolom):</strong><br>
                        NIS, NISN, Nama, JK (L/P), Tempat Lhr, Tgl Lhr, Agama, Alamat, Nama Ayah, Nama Ibu, Pek. Ayah, Pek. Ibu, No HP, Kelas.
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

<?php require_once '../layouts/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('.btn-detail').click(function() {
            // Ambil Data
            var nama = $(this).data('nama');
            var nis = $(this).data('nis');
            var nisn = $(this).data('nisn');
            var jk = $(this).data('jk');
            var agama = $(this).data('agama');
            var tempat = $(this).data('tempat');
            var tgl = $(this).data('tgl');
            var alamat = $(this).data('alamat');
            var ayah = $(this).data('ayah');
            var ibu = $(this).data('ibu');
            var payah = $(this).data('payah');
            var pibu = $(this).data('pibu');
            var hp = $(this).data('hp');
            var kelas = $(this).data('kelas');
            var tahun = $(this).data('tahun');
            var status = $(this).data('status');

            // Set Data
            $('#d_nama').text(nama);
            $('#d_nis').text(nis);
            $('#d_nisn').text(nisn);
            $('#d_jk').text(jk == 'L' ? 'Laki-laki' : 'Perempuan');
            $('#d_agama').text(agama);
            $('#d_tempat').text(tempat);
            $('#d_tgl').text(tgl);
            $('#d_alamat').text(alamat);
            $('#d_ayah').text(ayah);
            $('#d_ibu').text(ibu);
            $('#d_payah').text(payah);
            $('#d_pibu').text(pibu);
            $('#d_hp').text(hp);
            $('#d_kelas').text(kelas);
            $('#d_tahun').text(tahun);
            $('#d_status').text(status ? status : 'Aktif');
        });
    });
</script>