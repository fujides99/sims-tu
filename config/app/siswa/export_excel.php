<?php
// config/app/siswa/export_excel.php

require_once '../../database.php';

// Header agar browser membaca file ini sebagai Excel (.xls)
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Siswa_Lengkap_".date('d-m-Y_H-i').".xls");

// Ambil semua data siswa
$stmt = $pdo->query("SELECT * FROM siswa ORDER BY kelas_sekarang ASC, nama_lengkap ASC");
$siswa = $stmt->fetchAll();
?>

<h3>DATA INDUK SISWA SMP NUSANTARA</h3>
<p>Dicetak pada tanggal: <?= date('d-m-Y H:i') ?></p>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr style="background-color: #eeeeee; font-weight: bold;">
            <th>No</th>
            <th>NIS</th>
            <th>NISN</th>
            <th>Nama Lengkap</th>
            <th>L/P</th>
            <th>Tempat Lahir</th>
            <th>Tgl Lahir</th>
            <th>Agama</th>
            <th>Alamat Lengkap</th>
            <th>Nama Ayah</th>
            <th>Nama Ibu</th>
            <th>Pekerjaan Ayah</th>
            <th>Pekerjaan Ibu</th>
            <th>No HP Ortu</th>
            <th>Kelas</th>
            <th>Thn Masuk</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; 
        foreach($siswa as $row): 
            // Ubah format tanggal lahir jadi dd-mm-yyyy agar enak dibaca
            $tgl_lahir = date('d-m-Y', strtotime($row['tgl_lahir']));
        ?>
        <tr>
            <td align="center"><?= $no++ ?></td>
            <td align="left">'<?= htmlspecialchars($row['nis']) ?></td>
            <td align="left">'<?= htmlspecialchars($row['nisn']) ?></td>
            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
            <td align="center"><?= htmlspecialchars($row['jk']) ?></td>
            <td><?= htmlspecialchars($row['tempat_lahir']) ?></td>
            <td><?= $tgl_lahir ?></td>
            <td><?= htmlspecialchars($row['agama']) ?></td>
            <td><?= htmlspecialchars($row['alamat_siswa']) ?></td>
            <td><?= htmlspecialchars($row['nama_ayah']) ?></td>
            <td><?= htmlspecialchars($row['nama_ibu']) ?></td>
            <td><?= htmlspecialchars($row['pekerjaan_ayah']) ?></td>
            <td><?= htmlspecialchars($row['pekerjaan_ibu']) ?></td>
            <td align="left">'<?= htmlspecialchars($row['no_hp_ortu']) ?></td>
            <td align="center"><?= htmlspecialchars($row['kelas_sekarang']) ?></td>
            <td align="center"><?= htmlspecialchars($row['tahun_masuk']) ?></td>
            <td align="center"><?= htmlspecialchars($row['status_siswa']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>