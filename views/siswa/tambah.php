<?php require_once '../../config/session_check.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Tambah Siswa</h5>
            </div>
            <div class="card-body">
                
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'nisn_duplicate'): ?>
                    <div class="alert alert-danger">GAGAL: NISN sudah terdaftar!</div>
                <?php endif; ?>

                <form action="../../config/app/siswa/proses_siswa.php?aksi=tambah" method="POST">
                    <div class="row mb-3">
                        <div class="col">
                            <label>NIS</label>
                            <input type="text" name="nis" class="form-control" required>
                        </div>
                        <div class="col">
                            <label>NISN <span class="text-danger">*</span></label>
                            <input type="text" name="nisn" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label>Jenis Kelamin</label>
                            <select name="jk" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Agama</label>
                            <select name="agama" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Kristen Katolik">Kristen Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label>Kelas</label>
                            <input type="text" name="kelas_sekarang" class="form-control" placeholder="Contoh: 7A" required>
                        </div>
                        <div class="col">
                            <label>Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" class="form-control" value="<?= date('Y') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control" required>
                    </div>
                    
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>