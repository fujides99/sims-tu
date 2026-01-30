<?php
// views/public_view/siswa.php
require_once '../../config/database.php';
$stmt = $pdo->query("SELECT nis, nama_lengkap, jk, kelas_sekarang, status_siswa FROM siswa ORDER BY kelas_sekarang ASC, nama_lengkap ASC");
$siswa = $stmt->fetchAll();
require_once 'header.php';
?>

<div class="container mt-5 mb-5">
    <div class="card-custom p-4">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary text-white rounded-3 p-3 me-3">
                <i class="bi bi-people-fill fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Direktori Siswa</h4>
                <p class="text-muted mb-0 small">Data siswa aktif dan alumni terdaftar.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover" id="tabelSiswa">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Identitas</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach($siswa as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-secondary">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                    <div class="small text-muted">NIS: <?= htmlspecialchars($row['nis']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary rounded-pill"><?= $row['kelas_sekarang'] ?></span></td>
                        <td><?= $row['jk'] ?></td>
                        <td>
                            <?php if($row['status_siswa'] == 'Aktif'): ?>
                                <span class="badge-soft badge-soft-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge-soft badge-soft-warning"><?= $row['status_siswa'] ?></span>
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
        $('#tabelSiswa').DataTable({ language: { search: "Cari:", lengthMenu: "Tampil _MENU_", info: "Menampilkan _START_ - _END_ dari _TOTAL_ data" } });
    });
</script>
</body>
</html>