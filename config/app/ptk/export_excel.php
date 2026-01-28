<?php
// config/app/ptk/export_excel.php
require_once '../../database.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_PTK_".date('d-m-Y').".xls");

$stmt = $pdo->query("SELECT * FROM ptk ORDER BY nama_lengkap ASC");
$ptk = $stmt->fetchAll();
?>

<h3>DATA PENDIDIK DAN TENAGA KEPENDIDIKAN (PTK)</h3>
<table border="1">
    <thead>
        <tr style="background-color: #f2f2f2; font-weight:bold;">
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Gelar Dpn</th>
            <th>Gelar Blk</th>
            <th>NUPTK</th>
            <th>NIP</th>
            <th>L/P</th>
            <th>Tempat Lhr</th>
            <th>Tgl Lhr</th>
            <th>Status Pegawai</th>
            <th>Jenis PTK</th>
            <th>Pendidikan</th>
            <th>Jurusan</th>
            <th>Sertifikasi</th>
            <th>TMT Kerja</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($ptk as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama_lengkap'] ?></td>
            <td><?= $row['gelar_depan'] ?></td>
            <td><?= $row['gelar_belakang'] ?></td>
            <td>'<?= $row['nuptk'] ?></td> <td>'<?= $row['nip'] ?></td>
            <td><?= $row['jk'] ?></td>
            <td><?= $row['tempat_lahir'] ?></td>
            <td><?= $row['tgl_lahir'] ?></td>
            <td><?= $row['status_kepegawaian'] ?></td>
            <td><?= $row['jenis_ptk'] ?></td>
            <td><?= $row['jenjang_pendidikan'] ?></td>
            <td><?= $row['jurusan_prodi'] ?></td>
            <td><?= $row['sertifikasi'] ?></td>
            <td><?= $row['tmt_kerja'] ?></td>
            <td><?= $row['status_aktif'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>