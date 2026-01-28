<?php require_once '../../config/session_check.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah PTK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Tambah PTK Baru</h5>
            </div>
            <div class="card-body">
                <form action="../../config/app/ptk/proses_ptk.php?aksi=tambah" method="POST">
                    
                    <h6 class="text-primary border-bottom pb-2 mb-3">A. Identitas Diri</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" required placeholder="Tanpa gelar">
                        </div>
                        <div class="col-md-3">
                            <label>Gelar Depan</label>
                            <input type="text" name="gelar_depan" class="form-control" placeholder="Cth: Dr., Drs.">
                        </div>
                        <div class="col-md-3">
                            <label>Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" class="form-control" placeholder="Cth: S.Pd, M.Kom">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>NIP</label>
                            <input type="text" name="nip" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>NUPTK</label>
                            <input type="text" name="nuptk" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Jenis Kelamin</label>
                            <select name="jk" class="form-select" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control">
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">B. Pendidikan & Sertifikasi</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Jenjang Pendidikan</label>
                            <select name="jenjang_pendidikan" class="form-select">
                                <option value="">- Pilih -</option>
                                <option value="SMA/Sederajat">SMA/Sederajat</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Jurusan / Prodi</label>
                            <input type="text" name="jurusan_prodi" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Sertifikasi Guru</label>
                            <select name="sertifikasi" class="form-select">
                                <option value="Belum">Belum</option>
                                <option value="Sudah">Sudah</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">C. Data Kepegawaian</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Status Kepegawaian <span class="text-danger">*</span></label>
                            <select name="status_kepegawaian" class="form-select" required>
                                <option value="">- Pilih -</option>
                                <option value="PNS">PNS</option>
                                <option value="PPPK">PPPK</option>
                                <option value="GTY">GTY (Guru Tetap Yayasan)</option>
                                <option value="GTT">GTT (Guru Tidak Tetap)</option>
                                <option value="Honor Daerah">Honor Daerah</option>
                                <option value="Tenaga Honor Sekolah">Tenaga Honor Sekolah</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Jenis PTK <span class="text-danger">*</span></label>
                            <select name="jenis_ptk" class="form-select" required>
                                <option value="">- Pilih -</option>
                                <option value="Guru Mapel">Guru Mapel</option>
                                <option value="Guru Kelas">Guru Kelas</option>
                                <option value="Guru BK">Guru BK</option>
                                <option value="Kepala Sekolah">Kepala Sekolah</option>
                                <option value="Tenaga Administrasi">Tenaga Administrasi</option>
                                <option value="Penjaga Sekolah">Penjaga Sekolah</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>TMT Kerja</label>
                            <input type="date" name="tmt_kerja" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Status Aktif</label>
                            <select name="status_aktif" class="form-select">
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Pensiun">Pensiun</option>
                                <option value="Keluar">Keluar</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>