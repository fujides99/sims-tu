-- 1. Buat Database
CREATE DATABASE db_tu_smp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_tu_smp;

-- 2. Tabel Users (Untuk Admin & Staf TU)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,       -- Akan diisi hash password (bukan plain text)
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'operator') DEFAULT 'admin',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (username)
) ENGINE=InnoDB;

-- 3. Tabel Guru
CREATE TABLE guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nip VARCHAR(20) UNIQUE NULL,          -- Nullable karena mungkin ada honorer tanpa NIP
    nama_lengkap VARCHAR(100) NOT NULL,
    jk ENUM('L', 'P') NOT NULL,           -- L = Laki-laki, P = Perempuan
    tempat_lahir VARCHAR(50),
    tgl_lahir DATE,
    alamat TEXT,
    no_hp VARCHAR(15),
    status_kepegawaian ENUM('PNS', 'PPPK', 'GTT', 'GTY') DEFAULT 'GTT',
    mapel_ampu VARCHAR(100),              -- Mata pelajaran utama
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. Tabel Siswa (Data Pokok)
-- Didesain untuk menyimpan data penting Buku Induk
CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(20) UNIQUE NOT NULL,      -- Nomor Induk Sekolah
    nisn VARCHAR(20) UNIQUE NOT NULL,     -- Nomor Induk Siswa Nasional
    nama_lengkap VARCHAR(100) NOT NULL,
    jk ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50),
    tgl_lahir DATE NOT NULL,
    agama VARCHAR(20),
    alamat_siswa TEXT,
    
    -- Data Orang Tua / Wali
    nama_ayah VARCHAR(100),
    nama_ibu VARCHAR(100),
    pekerjaan_ayah VARCHAR(50),
    pekerjaan_ibu VARCHAR(50),
    no_hp_ortu VARCHAR(15),               -- Penting untuk notifikasi WA kedepannya
    
    -- Data Akademik
    kelas_sekarang VARCHAR(10) NOT NULL,  -- Contoh: '7A', '8B'
    tahun_masuk YEAR NOT NULL,
    status_siswa ENUM('aktif', 'lulus', 'pindah', 'keluar') DEFAULT 'aktif',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Index untuk mempercepat pencarian
    INDEX (nama_lengkap),
    INDEX (kelas_sekarang)
) ENGINE=InnoDB;

-- 5. Tabel Surat Masuk
CREATE TABLE surat_masuk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_agenda VARCHAR(50) UNIQUE,         -- Nomor urut di buku agenda TU
    no_surat VARCHAR(100) NOT NULL,       -- Nomor asli dari surat yang diterima
    pengirim VARCHAR(100) NOT NULL,       -- Instansi pengirim
    perihal TEXT NOT NULL,
    tgl_surat DATE NOT NULL,              -- Tanggal yang tertera di surat
    tgl_diterima DATE NOT NULL,           -- Tanggal surat sampai di sekolah
    file_path VARCHAR(255) NULL,          -- Lokasi file scan (PDF/JPG)
    
    -- Relasi: Siapa user yang menginput data ini? (Audit Trail)
    input_by INT,
    FOREIGN KEY (input_by) REFERENCES users(id) ON DELETE SET NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (tgl_diterima),
    INDEX (pengirim)
) ENGINE=InnoDB;

-- Seed Data: Akun Admin Default (Password: admin123)
-- Hash di bawah adalah hasil dari password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, password, nama_lengkap, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Utama', 'admin');