<?php
// views/public_view/ptk.php
require_once '../../config/database.php';
$stmt = $pdo->query("SELECT nama_lengkap, nip, jk, jenis_ptk, status_aktif FROM ptk ORDER BY nama_lengkap ASC");
$ptk = $stmt->fetchAll();
require_once 'header.php';
?>

<div class="container mt-5 mb-5">
    <div class="card-custom p-4">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-info text-white rounded-3 p-3 me-3">
                <i class="bi bi-person-workspace fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Direktori Guru & Staff</h4>
                <p class="text-muted mb-0 small">Daftar tenaga pengajar dan kependidikan.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover" id="tabelPtk">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama & NIP</th>
                        <th>Jabatan</th>
                        <th>L/P</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($ptk as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-secondary">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                    <div class="small text-muted"><?= $row['nip'] ? 'NIP: '.$row['nip'] : '-' ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= $row['jenis_ptk'] ?></td>
                        <td><?= $row['jk'] ?></td>
                        <td>
                            <?php if($row['status_aktif'] == 'Aktif'): ?>
                                <span class="badge-soft badge-soft-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge-soft badge-soft-danger"><?= $row['status_aktif'] ?></span>
                            <?php endif; ?>
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
        $('#tabelPtk').DataTable({ language: { search: "Cari:", lengthMenu: "Tampil _MENU_", info: "Menampilkan _START_ - _END_ dari _TOTAL_ data" } });
    });
</script>
</body>
</html>