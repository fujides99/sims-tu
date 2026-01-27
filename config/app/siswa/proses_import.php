<?php
// config/app/siswa/proses_import.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../database.php';

if (isset($_POST['import'])) {
    
    $fileName = $_FILES['file_csv']['name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    if (strtolower($fileExt) !== 'csv') {
        header("Location: ../../../views/siswa/index.php?pesan=gagal_import&ket=BukanCSV");
        exit();
    }

    $handle = fopen($_FILES['file_csv']['tmp_name'], "r");
    $firstLine = fgets($handle);
    $separator = (strpos($firstLine, ';') !== false) ? ';' : ',';
    rewind($handle);
    fgetcsv($handle, 1000, $separator); // Lewati Header

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO siswa (
            nis, nisn, nama_lengkap, jk, tempat_lahir, tgl_lahir, agama, 
            alamat_siswa, nama_ayah, nama_ibu, pekerjaan_ayah, pekerjaan_ibu, 
            no_hp_ortu, kelas_sekarang, tahun_masuk, status_siswa
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, 'Aktif'
        )";
        
        $stmt = $pdo->prepare($sql);

        // LOGIKA TAHUN MASUK (Semester Genap -> Mundur 1 Tahun)
        $bulan_ini = date('n'); 
        $tahun_ini = date('Y'); 
        $tahun_awal_tp = ($bulan_ini < 7) ? $tahun_ini - 1 : $tahun_ini;

        while (($row = fgetcsv($handle, 1000, $separator)) !== FALSE) {
            
            // Minimal 14 kolom agar aman (boleh kosong tapi kolomnya ada)
            if (count($row) < 14) continue; 
            if (empty($row[1])) continue; // NISN wajib ada

            // Mapping Data
            $nis = trim($row[0]);
            $nisn = trim($row[1]);
            $nama = trim($row[2]);
            $jk = trim($row[3]);
            $tempat = trim($row[4]);
            $tgl_lahir = trim($row[5]);
            $agama = trim($row[6]);
            $alamat = trim($row[7]);
            $ayah = trim($row[8]);
            $ibu = trim($row[9]);
            $p_ayah = trim($row[10]);
            $p_ibu = trim($row[11]);
            $hp = trim($row[12]);
            $kelas_raw = trim($row[13]);

            // Hitung Tahun Masuk
            $kelas_angka = (int) filter_var($kelas_raw, FILTER_SANITIZE_NUMBER_INT);
            if ($kelas_angka < 7 || $kelas_angka > 9) $kelas_angka = 7;
            $tahun_masuk = $tahun_awal_tp - ($kelas_angka - 7);

            // Cek Duplicate
            $cek = $pdo->prepare("SELECT id FROM siswa WHERE nisn = ?");
            $cek->execute([$nisn]);
            
            if ($cek->rowCount() == 0) {
                $stmt->execute([
                    $nis, $nisn, $nama, $jk, $tempat, $tgl_lahir, $agama,
                    $alamat, $ayah, $ibu, $p_ayah, $p_ibu, $hp, $kelas_raw, 
                    $tahun_masuk
                ]);
            }
        }

        $pdo->commit();
        fclose($handle);
        header("Location: ../../../views/siswa/index.php?pesan=sukses_import");

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>