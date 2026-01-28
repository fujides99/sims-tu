<?php 
require_once '../../config/session_check.php'; 
require_once '../../config/database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM ptk WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) die("Data tidak ditemukan!");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit PTK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Data PTK</h5>
            </div>
            <div class="card-body">
                <form action="../../config/app/ptk/proses_ptk.php?aksi=edit" method="POST">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">

                    <h6 class="text-primary border-bottom pb-2 mb-3">A. Identitas Diri</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label>Gelar Depan</label>
                            <input type="text" name="gelar_depan" class="form-control" value="<?= htmlspecialchars($data['gelar_depan']) ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" class="form-control" value="<?= htmlspecialchars($data['gelar_belakang']) ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>NIP</label>
                            <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($data['nip']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label>NUPTK</label>
                            <input type="text" name="nuptk" class="form-control" value="<?= htmlspecialchars($data['nuptk']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Jenis Kelamin</label>
                            <select name="jk" class="form-select" required>
                                <option value="L" <?= $data['jk'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $data['jk'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($data['tempat_lahir']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" value="<?= $data['tgl_lahir'] ?>">
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">B. Pendidikan & Sertifikasi</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Jenjang Pendidikan</label>
                            <select name="jenjang_pendidikan" class="form-select">
                                <?php $jenjang = ['SMA/Sederajat', 'D3', 'S1', 'S2', 'S3'];
                                foreach($jenjang as $j) {
                                    $sel = ($data['jenjang_pendidikan'] == $j) ? 'selected' : '';
                                    echo "<option value='$j' $sel>$j</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Jurusan / Prodi</label>
                            <input type="text" name="jurusan_prodi" class="form-control" value="<?= htmlspecialchars($data['jurusan_prodi']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Sertifikasi Guru</label>
                            <select name="sertifikasi" class="form-select">
                                <option value="Belum" <?= $data['sertifikasi'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
                                <option value="Sudah" <?= $data['sertifikasi'] == 'Sudah' ? 'selected' : '' ?>>Sudah</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">C. Data Kepegawaian</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Status Kepegawaian</label>
                            <select name="status_kepegawaian" class="form-select" required>
                                <?php $st_peg = ['PNS', 'PPPK', 'GTY', 'GTT', 'Honor Daerah', 'Tenaga Honor Sekolah'];
                                foreach($st_peg as $s) {
                                    $sel = ($data['status_kepegawaian'] == $s) ? 'selected' : '';
                                    echo "<option value='$s' $sel>$s</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Jenis PTK</label>
                            <select name="jenis_ptk" class="form-select" required>
                                <?php $j_ptk = ['Guru Mapel', 'Guru Kelas', 'Guru BK', 'Kepala Sekolah', 'Tenaga Administrasi', 'Penjaga Sekolah'];
                                foreach($j_ptk as $j) {
                                    $sel = ($data['jenis_ptk'] == $j) ? 'selected' : '';
                                    echo "<option value='$j' $sel>$j</option>";
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>TMT Kerja</label>
                            <input type="date" name="tmt_kerja" class="form-control" value="<?= $data['tmt_kerja'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Status Aktif</label>
                            <select name="status_aktif" class="form-select fw-bold">
                                <?php $st_aktif = ['Aktif', 'Cuti', 'Pensiun', 'Keluar'];
                                foreach($st_aktif as $sa) {
                                    $sel = ($data['status_aktif'] == $sa) ? 'selected' : '';
                                    echo "<option value='$sa' $sel>$sa</option>";
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning px-4">Update Data</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>