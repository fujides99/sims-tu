<?php
// views/users/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// Cek akses
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda bukan Admin!'); window.location='../dashboard/index.php';</script>";
    exit;
}

// Ambil data
$stmt = $pdo->query("SELECT * FROM users ORDER BY status ASC, role ASC");
$users = $stmt->fetchAll();

require_once '../layouts/header.php';
?>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-people-fill me-2"></i> Manajemen Pengguna</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah User
            </button>
        </div>
        <div class="card-body">

            <?php if (isset($_GET['pesan'])): ?>
                <?php if ($_GET['pesan'] == 'sukses_tambah'): ?>
                    <div class="alert alert-success alert-dismissible fade show">User berhasil ditambahkan! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_edit'): ?>
                    <div class="alert alert-info alert-dismissible fade show">Data diperbarui! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                
                <?php elseif ($_GET['pesan'] == 'sukses_nonaktif'): ?>
                    <div class="alert alert-warning alert-dismissible fade show"><i class="bi bi-pause-circle me-2"></i>User berhasil <strong>dinonaktifkan</strong> (Akses dicabut). <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_aktif'): ?>
                    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>User berhasil <strong>diaktifkan</strong> kembali. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_hapus_permanen'): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-trash-fill me-2"></i>User telah <strong>dihapus permanen</strong> dari database. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                
                <?php elseif ($_GET['pesan'] == 'gagal_username'): ?>
                    <div class="alert alert-danger alert-dismissible fade show">Username sudah digunakan! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($users as $u): ?>
                        <tr class="<?= ($u['status'] == 'nonaktif') ? 'table-secondary text-muted' : '' ?>">
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($u['username']) ?></td>
                            
                            <td>
                                <?php if($u['role'] == 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php elseif($u['role'] == 'operator'): ?>
                                    <span class="badge bg-primary">Operator</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($u['status'] == 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-warning text-white btn-edit"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                        data-id="<?= $u['id'] ?>"
                                        data-nama="<?= $u['nama_lengkap'] ?>"
                                        data-username="<?= $u['username'] ?>"
                                        data-role="<?= $u['role'] ?>"
                                        data-status="<?= $u['status'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <?php if($u['id'] != $_SESSION['user_id']): ?>
                                        
                                        <?php if($u['status'] == 'aktif'): ?>
                                            <a href="../../config/app/users/proses_user.php?aksi=ubah_status&id=<?= $u['id'] ?>&set=nonaktif" 
                                               class="btn btn-sm btn-secondary" title="Nonaktifkan User"
                                               onclick="return confirm('Nonaktifkan user ini? Dia tidak akan bisa login lagi.');">
                                                <i class="bi bi-power"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="../../config/app/users/proses_user.php?aksi=ubah_status&id=<?= $u['id'] ?>&set=aktif" 
                                               class="btn btn-sm btn-success" title="Aktifkan User"
                                               onclick="return confirm('Aktifkan kembali user ini?');">
                                                <i class="bi bi-check-lg"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="../../config/app/users/proses_user.php?aksi=hapus&id=<?= $u['id'] ?>" 
                                           class="btn btn-sm btn-danger" title="Hapus Permanen"
                                           onclick="return confirm('PERINGATAN: Hapus Permanen?\n\nData ini akan hilang selamanya dan tidak bisa dikembalikan!');">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>

                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../config/app/users/proses_user.php?aksi=tambah" method="POST">
                <div class="modal-body">
                    <div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" required></div>
                    <div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                    <div class="mb-3">
                        <label>Role Akses</label>
                        <select name="role" class="form-select" required>
                            <option value="operator">Operator (Bisa Input/Edit)</option>
                            <option value="admin">Administrator (Full)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../config/app/users/proses_user.php?aksi=edit" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required></div>
                    <div class="mb-3"><label>Username</label><input type="text" name="username" id="edit_username" class="form-control" required></div>
                    <div class="mb-3"><label>Password Baru</label><input type="password" name="password" class="form-control" placeholder="(Kosongkan jika tidak diganti)"></div>
                        <div class="col-6">
                            <label>Role</label>
                            <select name="role" id="edit_role" class="form-select">
                                <option value="operator">Operator</option> 
                                <option value="admin">Administrator</option>
                            </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-warning">Update</button></div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('.btn-edit').click(function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_nama').val($(this).data('nama'));
            $('#edit_username').val($(this).data('username'));
            $('#edit_role').val($(this).data('role'));
            $('#edit_status').val($(this).data('status'));
        });
    });
</script>