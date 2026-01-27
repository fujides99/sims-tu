<?php require_once '../../config/session_check.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Input Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Form Tambah Surat</h5>
            </div>
            <div class="card-body">
                
                <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'ext_salah'): ?>
                    <div class="alert alert-danger">Format file harus PDF, JPG, atau PNG!</div>
                <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'ukuran_besar'): ?>
                    <div class="alert alert-danger">Ukuran file maksimal 2MB!</div>
                <?php endif; ?>

                <form action="../../config/app/surat_masuk/proses_surat.php?aksi=tambah" method="POST" enctype="multipart/form-data">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nomor Agenda</label>
                            <input type="text" name="no_agenda" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Nomor Surat</label>
                            <input type="text" name="no_surat" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Pengirim</label>
                            <input type="text" name="pengirim" class="form-control" placeholder="Nama Instansi/Orang" required>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Surat</label>
                            <input type="date" name="tgl_surat" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Diterima</label>
                        <input type="date" name="tgl_diterima" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Perihal / Isi Ringkas</label>
                        <textarea name="perihal" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Upload File Scan (PDF/JPG)</label>
                        <input type="file" name="file_surat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Surat</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>