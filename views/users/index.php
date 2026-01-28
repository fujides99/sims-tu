<?php
// views/users/index.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// Cek akses: Hanya Admin yang boleh akses halaman ini
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda bukan Admin!'); window.location='../dashboard/index.php';</script>";
    exit;
}

// Ambil semua data user
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
                    <div class="alert alert-success alert-dismissible fade show">User baru berhasil ditambahkan! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_edit'): ?>
                    <div class="alert alert-info alert-dismissible fade show">Data user berhasil diperbarui! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'sukses_hapus'): ?>
                    <div class="alert alert-secondary alert-dismissible fade show">User berhasil dinonaktifkan (Soft Delete). <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'gagal_username'): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><strong>Gagal!</strong> Username sudah digunakan user lain. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php elseif ($_GET['pesan'] == 'gagal_hapus_diri'): ?>
                    <div class="alert alert-warning alert-dismissible fade show">Anda tidak bisa menonaktifkan akun sendiri! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
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
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($users as $u): ?>
                        <tr class="<?= $u['status'] == 'nonaktif' ? 'table-secondary text-muted' : '' ?>">
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($u['username']) ?></td>
                            
                            <td>
                                <?php if($u['role'] == 'admin'): ?>
                                    <span class="badge bg-danger">Admin</span>
                                <?php else: ?>
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

                            <td class="small"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                            
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

                                    <?php if($u['status'] == 'aktif'): ?>
                                        <a href="../../config/app/users/proses_user.php?aksi=hapus&id=<?= $u['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Nonaktifkan user ini? User tidak akan bisa login lagi.');">
                                            <i class="bi bi-person-x-fill"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled><i class="bi bi-trash"></i></button>
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
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Username (Untuk Login)</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                    </div>
                    <div class="mb-3">
                        <label>Role Akses</label>
                        <select name="role" class="form-select" required>
                            <option value="operator">Operator</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
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
                    
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" id="edit_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="(Kosongkan jika tidak diganti)">
                        <small class="text-muted fst-italic">Isi hanya jika ingin mereset password user ini.</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <label>Role</label>
                            <select name="role" id="edit_role" class="form-select">
                                <option value="operator">Operator</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Status Akun</label>
                            <select name="status" id="edit_status" class="form-select fw-bold">
                                <option value="aktif" class="text-success">Aktif</option>
                                <option value="nonaktif" class="text-secondary">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../layouts/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('.btn-edit').click(function() {
            // Ambil data dari atribut tombol
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var username = $(this).data('username');
            var role = $(this).data('role');
            var status = $(this).data('status');

            // Masukkan ke dalam input form modal
            $('#edit_id').val(id);
            $('#edit_nama').val(nama);
            $('#edit_username').val(username);
            $('#edit_role').val(role);
            $('#edit_status').val(status);
        });
    });
</script>