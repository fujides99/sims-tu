<?php 
require_once '../../config/session_check.php';
require_once '../../config/database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM surat_masuk WHERE id = ?");
$stmt->execute([$id]);
$surat = $stmt->fetch();

if(!$surat) die("Surat tidak ditemukan!");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Surat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Surat Masuk</h5>
            </div>
            <div class="card-body">
                
                <form action="../../config/app/surat/proses_surat.php?aksi=edit" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $surat['id'] ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Nomor Agenda</label>
                            <input type="text" name="no_agenda" class="form-control" value="<?= $surat['no_agenda'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Nomor Surat</label>
                            <input type="text" name="no_surat" class="form-control" value="<?= $surat['no_surat'] ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Pengirim</label>
                            <input type="text" name="pengirim" class="form-control" value="<?= $surat['pengirim'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Tanggal Surat</label>
                            <input type="date" name="tgl_surat" class="form-control" value="<?= $surat['tgl_surat'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Diterima</label>
                        <input type="date" name="tgl_diterima" class="form-control" value="<?= $surat['tgl_diterima'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Perihal</label>
                        <textarea name="perihal" class="form-control" rows="3" required><?= $surat['perihal'] ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label>File Scan Saat Ini</label><br>
                        <?php if($surat['file_path']): ?>
                            <a href="../../public/uploads/surat/<?= $surat['file_path'] ?>" target="_blank" class="badge bg-primary text-decoration-none">
                                Lihat File Lama
                            </a>
                        <?php else: ?>
                            <span class="badge bg-secondary">Tidak ada file</span>
                        <?php endif; ?>
                        
                        <div class="mt-2">
                            <label class="small text-muted">Ganti File (Biarkan kosong jika tidak ingin mengubah file)</label>
                            <input type="file" name="file_surat" class="form-control" accept=".pdf,.jpg,.png">
                        </div>
                    </div>

                    <a href="index.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">Update Surat</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>