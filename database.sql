CREATE DATABASE db_tu_smp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_tu_smp;

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

CREATE TABLE ptk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    nuptk VARCHAR(25) NULL,
    jk ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50) NULL,
    tgl_lahir DATE NULL,
    nip VARCHAR(30) NULL,
    status_kepegawaian VARCHAR(50) NOT NULL,
    jenis_ptk VARCHAR(50) NOT NULL,
    gelar_depan VARCHAR(20) NULL,
    gelar_belakang VARCHAR(20) NULL,
    jenjang_pendidikan VARCHAR(20) NULL,
    jurusan_prodi VARCHAR(100) NULL,
    sertifikasi ENUM('Sudah', 'Belum') DEFAULT 'Belum',
    tmt_kerja DATE NULL,
    status_aktif ENUM('Aktif', 'Pensiun', 'Keluar', 'Cuti') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;-- 3. Tabel Guru

CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(20) UNIQUE NOT NULL,      
    nisn VARCHAR(20) UNIQUE NOT NULL,     
    nama_lengkap VARCHAR(100) NOT NULL,
    jk ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50),
    tgl_lahir DATE NOT NULL,
    agama VARCHAR(20),
    alamat_siswa TEXT,
    
    nama_ayah VARCHAR(100),
    nama_ibu VARCHAR(100),
    pekerjaan_ayah VARCHAR(50),
    pekerjaan_ibu VARCHAR(50),
    no_hp_ortu VARCHAR(15),             

    kelas_sekarang VARCHAR(10) NOT NULL, 
    tahun_masuk YEAR NOT NULL,
    status_siswa ENUM('aktif', 'lulus', 'pindah', 'keluar') DEFAULT 'aktif',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX (nama_lengkap),
    INDEX (kelas_sekarang)
) ENGINE=InnoDB;

CREATE TABLE surat_masuk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_agenda VARCHAR(50) UNIQUE,         
    no_surat VARCHAR(100) NOT NULL,       
    pengirim VARCHAR(100) NOT NULL,      
    perihal TEXT NOT NULL,
    tgl_surat DATE NOT NULL,           
    tgl_diterima DATE NOT NULL,          
    file_path VARCHAR(255) NULL,          
 
    input_by INT,
    FOREIGN KEY (input_by) REFERENCES users(id) ON DELETE SET NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (tgl_diterima),
    INDEX (pengirim)
) ENGINE=InnoDB;

INSERT INTO users (username, password, nama_lengkap, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Utama', 'admin');
