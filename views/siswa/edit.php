<?php 
require_once '../../config/session_check.php'; 
require_once '../../config/database.php';

// Ambil ID dari URL
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Form Edit Siswa</h5>
                    </div>
                    <div class="card-body">

                         <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'nisn_duplicate'): ?>
                            <div class="alert alert-danger">GAGAL: NISN milik siswa lain!</div>
                        <?php endif; ?>

                        <form action="../../config/app/siswa/proses_siswa.php?aksi=edit" method="POST">
                            <input type="hidden" name="id" value="<?= $data['id'] ?>">

                            <div class="row mb-3">
                                <div class="col">
                                    <label>NIS</label>
                                    <input type="text" name="nis" class="form-control" value="<?= $data['nis'] ?>" required>
                                </div>
                                <div class="col">
                                    <label>NISN</label>
                                    <input type="text" name="nisn" class="form-control" value="<?= $data['nisn'] ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="<?= $data['nama_lengkap'] ?>" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label>Jenis Kelamin</label>
                                    <select name="jk" class="form-select" required>
                                        <option value="L" <?= ($data['jk'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="P" <?= ($data['jk'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label>Kelas</label>
                                    <input type="text" name="kelas_sekarang" class="form-control" value="<?= $data['kelas_sekarang'] ?>" required>
                                </div>
                                <div class="col">
                                    <label>Tahun Masuk</label>
                                    <input type="number" name="tahun_masuk" class="form-control" value="<?= $data['tahun_masuk'] ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" class="form-control" value="<?= $data['tgl_lahir'] ?>" required>
                            </div>
                            
                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-warning">Update Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>