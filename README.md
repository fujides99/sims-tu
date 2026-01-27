```markdown
# 🏫 SIMS-TU (Sistem Informasi Manajemen Sekolah - Tata Usaha)

**SIMS-TU** adalah aplikasi berbasis web yang dirancang untuk mempermudah pekerjaan administrasi Tata Usaha di sekolah. Aplikasi ini fokus pada manajemen data induk siswa dan pengarsipan surat menyurat (Masuk & Keluar) secara digital.

---

## 🚀 Fitur Utama

### 1. Manajemen Siswa
- **CRUD Lengkap**: Input data pribadi, orang tua, dan status sekolah.
- **Import CSV**: Tambah data siswa secara massal dengan logika perhitungan Tahun Masuk otomatis berdasarkan semester genap/ganjil.
- **Export Excel**: Mengunduh seluruh database siswa ke format `.xls`.
- **Rekapitulasi Dinamis**: Laporan jumlah siswa per kelas, jenis kelamin, dan agama dengan tampilan yang siap cetak (Print-friendly).

### 2. Arsip Surat
- **Surat Masuk**: Pencatatan surat yang diterima beserta upload scan dokumen.
- **Surat Keluar**: Pencatatan surat yang dikirim dengan penomoran agenda internal.
- **Digital Archive**: Penyimpanan file dokumen (PDF/JPG) yang terorganisir.

### 3. Keamanan & UI/UX
- **Autentikasi**: Sistem Login/Logout yang aman menggunakan `password_hash`.
- **Reset Database**: Fitur hapus semua data siswa yang dilindungi konfirmasi password admin.
- **Responsive Design**: Menggunakan Bootstrap 5 dan DataTables untuk navigasi data yang cepat.

---

## 🛠️ Teknologi yang Digunakan

- **Bahasa**: PHP Native (v7.4 / v8.x)
- **Database**: MySQL (PDO Connection)
- **Frontend**: Bootstrap 5, Bootstrap Icons
- **Library**: jQuery, DataTables.js
- **Tools**: XAMPP / Laragon

---

## 📂 Struktur Folder

```text
sims-tu/
├── config/             # Konfigurasi database & logika backend (CRUD)
├── public/             # Folder aset publik
│   └── uploads/        # Penyimpanan scan surat (Masuk/Keluar)
├── views/              # Tampilan UI (HTML/PHP)
│   ├── auth/           # Login & Logout
│   ├── dashboard/      # Halaman utama statistik
│   ├── layouts/        # Header, Sidebar, Footer (Include)
│   ├── siswa/          # Modul Siswa
│   ├── surat_masuk/    # Modul Surat Masuk
│   └── surat_keluar/   # Modul Surat Keluar
└── index.php           # Redirect awal

```

---

## ⚙️ Instalasi

1. **Clone Repositori**
```bash
git clone [https://github.com/username-anda/sims-tu.git](https://github.com/username-anda/sims-tu.git)

```


2. **Pindahkan ke Server Lokal**
Pindahkan folder `sims-tu` ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
3. **Persiapan Database**
* Buat database baru bernama `db_tu_smp`.
* Import file SQL yang disediakan (atau jalankan query tabel `users`, `siswa`, `surat_masuk`, dan `surat_keluar`).


4. **Konfigurasi Database**
Buka `config/database.php` dan sesuaikan *credentials* database Anda:
```php
$host = "localhost";
$db   = "db_tu_smp";
$user = "root";
$pass = "";

```


5. **Akses Aplikasi**
Buka browser dan akses `http://localhost/sims-tu`

---

## 🔑 Akun Default

* **Username**: `admin`
* **Password**: `admin123` (Atau sesuai yang didaftarkan di tabel `users`)

---

## 📝 Catatan untuk Git (Best Practices)

* **.gitignore**: Pastikan folder `public/uploads/` tidak ikut di-*push* jika berisi dokumen sensitif, namun buat file `.gitkeep` di dalamnya agar struktur folder tetap terbawa.
* **Commit**: Gunakan pesan commit yang jelas, contoh: `git commit -m "feat: tambah fitur rekapitulasi siswa per tingkat"`.

---

Dikembangkan dengan ❤️ oleh **Fujides99**

```
