<a href="../dashboard/index.php" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-2 mb-1">
    <i class="bi bi-speedometer2 me-3"></i> Dashboard
</a>

<div class="text-white-50 small fw-bold px-3 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Data Utama</div>

<a href="../siswa/index.php" class="list-group-item list-group-item-action bg-transparent text-white-50 border-0 py-2">
    <i class="bi bi-people-fill me-3"></i> Data Siswa
</a>
<a href="../ptk/index.php" class="list-group-item list-group-item-action bg-transparent text-white-50 border-0 py-2">
    <i class="bi bi-person-workspace me-3"></i> Data Guru / PTK
</a>

<div class="text-white-50 small fw-bold px-3 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Administrasi</div>

<a href="../surat_masuk/index.php" class="list-group-item list-group-item-action bg-transparent text-white-50 border-0 py-2">
    <i class="bi bi-envelope-paper-fill me-3"></i> Surat Masuk
</a>
<a href="../surat_keluar/index.php" class="list-group-item list-group-item-action bg-transparent text-white-50 border-0 py-2">
    <i class="bi bi-send-fill me-3"></i> Surat Keluar
</a>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
    <div class="text-white-50 small fw-bold px-3 mt-4 mb-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Pengaturan</div>
    
    <a href="../users/index.php" class="list-group-item list-group-item-action bg-transparent text-white-50 border-0 py-2">
        <i class="bi bi-people me-3"></i> Manajemen User
    </a>
<?php endif; ?>

<style>
    .list-group-item-action:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        border-radius: 8px;
        padding-left: 1.5rem !important;
        transition: all 0.2s;
    }
</style>