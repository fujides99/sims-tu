<?php
// config/app/ptk/proses_import.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../../database.php';

if (isset($_POST['import'])) {
    
    $fileName = $_FILES['file_csv']['name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    if (strtolower($fileExt) !== 'csv') {
        header("Location: ../../../views/ptk/index.php?pesan=gagal_import");
        exit();
    }

    $handle = fopen($_FILES['file_csv']['tmp_name'], "r");
    // Deteksi separator (; atau ,)
    $firstLine = fgets($handle);
    $separator = (strpos($firstLine, ';') !== false) ? ';' : ',';
    rewind($handle);
    fgetcsv($handle, 1000, $separator); // Skip Header

    try {
        $pdo->beginTransaction();
        
        $sql = "INSERT INTO ptk (
            nama_lengkap, nuptk, nip, jk, tempat_lahir, tgl_lahir, 
            status_kepegawaian, jenis_ptk, gelar_depan, gelar_belakang, 
            jenjang_pendidikan, jurusan_prodi, sertifikasi, tmt_kerja, status_aktif
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);

        while (($row = fgetcsv($handle, 1000, $separator)) !== FALSE) {
            if (count($row) < 15) continue; // Pastikan kolom cukup

            // Mapping Data CSV ke Database
            $nama = trim($row[0]);
            $nuptk = trim($row[1]);
            $nip = trim($row[2]);
            $jk = trim($row[3]);
            $tempat = trim($row[4]);
            $tgl = trim($row[5]);
            $status_peg = trim($row[6]);
            $jenis_ptk = trim($row[7]);
            $gelar_d = trim($row[8]);
            $gelar_b = trim($row[9]);
            $pendidikan = trim($row[10]);
            $jurusan = trim($row[11]);
            $sertifikasi = trim($row[12]);
            $tmt = trim($row[13]);
            $aktif = trim($row[14]);

            // Cek NUPTK/NIP Duplicate (opsional, disini kita insert saja)
            $stmt->execute([
                $nama, $nuptk, $nip, $jk, $tempat, $tgl, 
                $status_peg, $jenis_ptk, $gelar_d, $gelar_b, 
                $pendidikan, $jurusan, $sertifikasi, $tmt, $aktif
            ]);
        }

        $pdo->commit();
        header("Location: ../../../views/ptk/index.php?pesan=sukses_import");

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>