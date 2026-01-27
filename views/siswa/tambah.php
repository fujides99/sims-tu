<?php require_once '../../config/session_check.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Tambah Siswa Lengkap</h5>
            </div>
            <div class="card-body">
                
                <form action="../../config/app/siswa/proses_siswa.php?aksi=tambah" method="POST">
                    
                    <h6 class="text-primary border-bottom pb-2 mb-3">A. Data Pribadi Siswa</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>NIS</label>
                            <input type="text" name="nis" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>NISN <span class="text-danger">*</span></label>
                            <input type="text" name="nisn" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Jenis Kelamin</label>
                            <select name="jk" class="form-select" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>Agama</label>
                            <select name="agama" class="form-select">
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat_siswa" class="form-control" rows="2"></textarea>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">B. Data Orang Tua / Wali</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>No. HP Orang Tua / Wali</label>
                        <input type="text" name="no_hp_ortu" class="form-control" placeholder="08xxxx">
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">C. Data Sekolah</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Kelas Sekarang</label>
                            <input type="text" name="kelas_sekarang" class="form-control" placeholder="Contoh: 7A" required>
                        </div>
                        <div class="col-md-6">
                            <label>Tahun Masuk</label>
                            <input type="number" name="tahun_masuk" class="form-control" value="<?= date('Y') ?>">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Data Siswa</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>