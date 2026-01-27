<?php require_once '../../config/session_check.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Catat Surat Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Form Surat Keluar</h5>
            </div>
            <div class="card-body">
                <form action="../../config/app/surat_keluar/proses_surat.php?aksi=tambah" method="POST" enctype="multipart/form-data">
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>No. Agenda</label>
                            <input type="text" name="no_agenda" class="form-control" placeholder="Contoh: 001/KEL/2026">
                        </div>
                        <div class="col-md-8">
                            <label>Nomor Surat Resmi</label>
                            <input type="text" name="no_surat" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Tujuan / Penerima</label>
                        <input type="text" name="tujuan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Perihal</label>
                        <textarea name="perihal" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Tanggal Surat</label>
                            <input type="date" name="tgl_surat" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>File Scan</label>
                            <input type="file" name="file_surat" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>