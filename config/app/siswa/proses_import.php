<?php
// config/app/siswa/proses_import.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../database.php';

if (isset($_POST['import'])) {
    
    // Validasi File
    $fileName = $_FILES['file_csv']['name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    if (strtolower($fileExt) !== 'csv') {
        header("Location: ../../../views/siswa/index.php?pesan=gagal_import&ket=BukanCSV");
        exit();
    }

    // Buka file CSV
    $handle = fopen($_FILES['file_csv']['tmp_name'], "r");
    
    // Lewati baris pertama (Header Judul Kolom)
    fgetcsv($handle);

    try {
        $pdo->beginTransaction(); // Mulai Transaksi

        $sql = "INSERT INTO siswa (nis, nisn, nama_lengkap, jk, agama, kelas_sekarang, tahun_masuk, tgl_lahir) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Loop baris per baris
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Urutan Kolom CSV: 0.NIS, 1.NISN, 2.Nama, 3.JK, 4.Agama, 5.Kelas, 6.Thn, 7.TglLahir
            
            // Cek Duplicate NISN
            $cek = $pdo->prepare("SELECT id FROM siswa WHERE nisn = ?");
            $cek->execute([$row[1]]);
            
            if ($cek->rowCount() == 0) {
                $stmt->execute([
                    $row[0], // NIS
                    $row[1], // NISN
                    $row[2], // Nama
                    $row[3], // JK (L/P)
                    $row[4], // Agama
                    $row[5], // Kelas
                    $row[6], // Thn Masuk
                    $row[7]  // Tgl Lahir
                ]);
            }
        }

        $pdo->commit(); // Simpan permanen
        fclose($handle);
        header("Location: ../../../views/siswa/index.php?pesan=sukses_import");

    } catch (Exception $e) {
        $pdo->rollBack(); // Batalkan jika error
        die("Error Import: " . $e->getMessage());
    }
}
?>