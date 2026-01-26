<?php
// config/app/siswa/export_excel.php
require_once '../../database.php';

// Header agar browser membaca ini sebagai file Excel (.xls)
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Siswa_".date('d-m-Y').".xls");

// Ambil data siswa
$stmt = $pdo->query("SELECT * FROM siswa ORDER BY kelas_sekarang ASC, nama_lengkap ASC");
$siswa = $stmt->fetchAll();
?>

<h3>Data Siswa SMP Nusantara</h3>
<table border="1">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>No</th>
            <th>NIS</th>
            <th>NISN</th>
            <th>Nama Lengkap</th>
            <th>L/P</th>
            <th>Agama</th>
            <th>Kelas</th>
            <th>Tahun Masuk</th>
            <th>Tgl Lahir</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($siswa as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td>'<?= $row['nis'] ?></td>
            <td>'<?= $row['nisn'] ?></td>
            <td><?= $row['nama_lengkap'] ?></td>
            <td><?= $row['jk'] ?></td>
            <td><?= $row['agama'] ?></td>
            <td><?= $row['kelas_sekarang'] ?></td>
            <td><?= $row['tahun_masuk'] ?></td>
            <td><?= $row['tgl_lahir'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>