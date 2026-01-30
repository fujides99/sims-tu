<?php
// views/public_view/surat_keluar.php
require_once '../../config/database.php';

// Ambil Data Surat Keluar (Metadata Only)
$stmt = $pdo->query("SELECT no_agenda, no_surat, tujuan, perihal, tgl_surat FROM surat_keluar ORDER BY tgl_surat DESC");
$surat = $stmt->fetchAll();

require_once 'header.php';
?>

<div class="container mt-5 mb-5">
    <div class="card-custom p-4">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-danger text-white rounded-3 p-3 me-3 shadow-sm">
                <i class="bi bi-send-fill fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Arsip Surat Keluar</h4>
                <p class="text-muted mb-0 small">Daftar surat resmi yang dikeluarkan sekolah.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover" id="tabelKeluar">
                <thead>
                    <tr>
                        <th>No. Agenda</th>
                        <th>No. Surat</th>
                        <th>Tujuan</th>
                        <th>Perihal</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($surat as $row): ?>
                    <tr>
                        <td>
                            <span class="badge-soft badge-soft-danger">#<?= htmlspecialchars($row['no_agenda']) ?></span>
                        </td>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($row['no_surat']) ?></td>
                        <td><?= htmlspecialchars($row['tujuan']) ?></td>
                        <td><?= htmlspecialchars($row['perihal']) ?></td>
                        <td>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-calendar-event me-2"></i>
                                <?= date('d M Y', strtotime($row['tgl_surat'])) ?>
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
    $(document).ready(function () { 
        $('#tabelKeluar').DataTable({ 
            "order": [], // Matikan default sort
            language: { search: "Cari Surat:", lengthMenu: "Tampil _MENU_", info: "Menampilkan _START_ - _END_ dari _TOTAL_ surat" }
        }); 
    });
</script>
</body>
</html>