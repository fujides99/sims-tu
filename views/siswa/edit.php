<?php 
require_once '../../config/session_check.php'; 
require_once '../../config/database.php';

// 1. Ambil ID dari URL
$id = $_GET['id'];

// 2. Ambil Data Siswa Berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

// Jika ID ngawur/tidak ditemukan
if (!$data) {
    die("Data siswa tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Data Siswa Lengkap</h5>
            </div>
            <div class="card-body">

                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'nisn_duplicate'): ?>
                    <div class="alert alert-danger">GAGAL UPDATE: NISN tersebut sudah digunakan siswa lain!</div>
                <?php endif; ?>

                <form action="../../config/app/siswa/proses_siswa.php?aksi=edit" method="POST">
                    
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">

                    <h6 class="text-primary border-bottom pb-2 mb-3">A. Data Pribadi Siswa</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>NIS</label>
                            <input type="text" name="nis" class="form-control" value="<?= htmlspecialchars($data['nis']) ?>">
                        </div>
                        <div class="col-md-3">
                            <label>NISN <span class="text-danger">*</span></label>
                            <input type="text" name="nisn" class="form-control" value="<?= htmlspecialchars($data['nisn']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Jenis Kelamin</label>
                            <select name="jk" class="form-select" required>
                                <option value="L" <?= ($data['jk'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= ($data['jk'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($data['tempat_lahir']) ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" value="<?= $data['tgl_lahir'] ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label>Agama</label>
                            <select name="agama" class="form-select">
                                <?php
                                $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'];
                                foreach($agamas as $agama) {
                                    $selected = ($data['agama'] == $agama) ? 'selected' : '';
                                    echo "<option value='$agama' $selected>$agama</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat_siswa" class="form-control" rows="2"><?= htmlspecialchars($data['alamat_siswa']) ?></textarea>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">B. Data Orang Tua / Wali</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" value="<?= htmlspecialchars($data['nama_ayah']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control" value="<?= htmlspecialchars($data['pekerjaan_ayah']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" value="<?= htmlspecialchars($data['nama_ibu']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control" value="<?= htmlspecialchars($data['pekerjaan_ibu']) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>No. HP Orang Tua / Wali</label>
                        <input type="text" name="no_hp_ortu" class="form-control" value="<?= htmlspecialchars($data['no_hp_ortu']) ?>">
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">C. Data Sekolah & Status</h6>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Kelas Sekarang</label>
                            <input type="text" name="kelas_sekarang" class="form-control" value="<?= htmlspecialchars($data['kelas_sekarang']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label>Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" class="form-control" value="<?= htmlspecialchars($data['tahun_masuk']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Status Siswa</label>
                            <select name="status_siswa" class="form-select fw-bold">
                                <option value="Aktif" <?= ($data['status_siswa'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                                <option value="Lulus" <?= ($data['status_siswa'] == 'Lulus') ? 'selected' : '' ?>>Lulus</option>
                                <option value="Mutasi" <?= ($data['status_siswa'] == 'Mutasi') ? 'selected' : '' ?>>Mutasi (Pindah)</option>
                                <option value="Keluar" <?= ($data['status_siswa'] == 'Keluar') ? 'selected' : '' ?>>Keluar / DO</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-warning px-4">Update Data Siswa</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>