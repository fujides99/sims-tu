<?php
// views/public_view/surat_masuk.php
require_once '../../config/database.php';
$stmt = $pdo->query("SELECT no_agenda, pengirim, perihal, tgl_diterima FROM surat_masuk ORDER BY tgl_diterima DESC");
$surat = $stmt->fetchAll();
require_once 'header.php';
?>

<div class="container mt-5 mb-5">
    <div class="card-custom p-4">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-success text-white rounded-3 p-3 me-3">
                <i class="bi bi-inbox-fill fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Arsip Surat Masuk</h4>
                <p class="text-muted mb-0 small">Daftar surat yang diterima sekolah (Metadata Only).</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover" id="tabelMasuk">
                <thead>
                    <tr>
                        <th>No. Agenda</th>
                        <th>Pengirim</th>
                        <th>Perihal</th>
                        <th>Tanggal Terima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($surat as $row): ?>
                    <tr>
                        <td><span class="fw-bold text-success">#<?= htmlspecialchars($row['no_agenda']) ?></span></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['pengirim']) ?></td>
                        <td><?= htmlspecialchars($row['perihal']) ?></td>
                        <td>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-calendar3 me-2"></i>
                                <?= date('d M Y', strtotime($row['tgl_diterima'])) ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () { $('#tabelMasuk').DataTable({ "order": [] }); });
</script>
</body>
</html>