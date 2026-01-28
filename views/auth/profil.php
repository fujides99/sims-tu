<?php
// views/auth/profil.php
require_once '../../config/session_check.php';
require_once '../../config/database.php';

// Ambil data terbaru user dari DB
$id_user = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch();

require_once '../layouts/header.php';
?>

<div class="container-fluid px-4">
    <h3 class="mt-3 mb-4 fw-bold text-secondary">Profil Pengguna</h3>

    <?php if (isset($_GET['pesan'])): ?>
        <?php if ($_GET['pesan'] == 'sukses_profil'): ?>
            <div class="alert alert-success alert-dismissible fade show">Profil berhasil diperbarui!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php elseif ($_GET['pesan'] == 'sukses_password'): ?>
            <div class="alert alert-success alert-dismissible fade show">Password berhasil diganti!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php elseif ($_GET['pesan'] == 'gagal_konfirmasi'): ?>
            <div class="alert alert-danger alert-dismissible fade show">Konfirmasi password baru tidak cocok!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php elseif ($_GET['pesan'] == 'gagal_lama'): ?>
            <div class="alert alert-danger alert-dismissible fade show">Password lama salah!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 text-center py-4">
                <div class="card-body">
                    <?php 
                        $fotoPath = "../../public/uploads/profil/" . $user['foto'];
                        if (!empty($user['foto']) && file_exists("../" . $fotoPath)) {
                            $src = $fotoPath;
                        } else {
                            $src = "https://ui-avatars.com/api/?name=".urlencode($user['nama_lengkap'])."&background=0D8ABC&color=fff&size=150";
                        }
                    ?>
                    <img src="<?= $src ?>" class="rounded-circle mb-3 border border-3 border-light shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                    
                    <h5 class="fw-bold"><?= htmlspecialchars($user['nama_lengkap']) ?></h5>
                    <p class="text-muted mb-1">@<?= htmlspecialchars($user['username']) ?></p>
                    
                    <?php if($user['role'] == 'admin'): ?>
                        <span class="badge bg-danger px-3 rounded-pill">Administrator</span>
                    <?php else: ?>
                        <span class="badge bg-primary px-3 rounded-pill">Operator</span>
                    <?php endif; ?>
                    
                    <hr>
                    <small class="text-muted">Terdaftar sejak: <br> <?= date('d F Y', strtotime($user['created_at'])) ?></small>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button">Edit Data Diri</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="pass-tab" data-bs-toggle="tab" data-bs-target="#pass" type="button">Ganti Password</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        
                        <div class="tab-pane fade show active" id="data">
                            <form id="formProfil" action="../../config/app/auth/proses_profil.php?aksi=update_profil" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">NAMA LENGKAP</label>
                                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">USERNAME</label>
                                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                    <small class="text-muted">*Username tidak dapat diubah.</small>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold">GANTI FOTO PROFIL</label>
                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pass">
                            <form id="formPassword" action="../../config/app/auth/proses_profil.php?aksi=ganti_password" method="POST">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">PASSWORD LAMA</label>
                                    <input type="password" name="pass_lama" class="form-control" required placeholder="Masukkan password saat ini">
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">PASSWORD BARU</label>
                                    <input type="password" name="pass_baru" class="form-control" required placeholder="Minimal 6 karakter">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold">ULANGI PASSWORD BARU</label>
                                    <input type="password" name="konfirmasi_pass" class="form-control" required placeholder="Ketik ulang password baru">
                                </div>
                                <div class="alert alert-warning small">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> 
                                    Pastikan Anda mengingat password baru sebelum menyimpan.
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-danger">Ganti Password</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- LOGIC 1: FORM EDIT PROFIL ---
        const formProfil = document.getElementById('formProfil');
        if(formProfil) {
            formProfil.addEventListener('submit', function(e) {
                e.preventDefault(); 
                Swal.fire({
                    title: 'Simpan Profil?',
                    text: "Pastikan data diri Anda sudah benar.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) formProfil.submit();
                });
            });
        }

        // --- LOGIC 2: FORM GANTI PASSWORD (BARU) ---
        const formPass = document.getElementById('formPassword');
        if(formPass) {
            formPass.addEventListener('submit', function(e) {
                e.preventDefault(); 
                Swal.fire({
                    title: 'Ganti Password?',
                    text: "Anda harus login ulang setelah password diganti.",
                    icon: 'warning', // Ikon Warning biar lebih alert
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545', // Warna Merah (Danger)
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ganti!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) formPass.submit();
                });
            });
        }

    });
</script>

<?php require_once '../layouts/footer.php'; ?>