<?php
// config/app/surat_keluar/cetak_agenda.php
require_once '../../database.php';

if (!isset($_GET['tgl_awal']) || !isset($_GET['tgl_akhir'])) {
    die("Harap pilih tanggal terlebih dahulu.");
}

$tgl_awal = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];

// Query Surat Keluar
// Menggunakan 'tgl_surat' sebagai acuan
$sql = "SELECT * FROM surat_keluar 
        WHERE tgl_surat BETWEEN ? AND ? 
        ORDER BY tgl_surat ASC, id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$tgl_awal, $tgl_akhir]);
$data = $stmt->fetchAll();

function tgl_indo($tanggal){
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Agenda Surat Keluar - SMPN 1 CONCONG</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; margin: 0; padding: 20px; }
        
        /* HEADER LOGO */
        .header-container { position: relative; width: 100%; margin-bottom: 20px; text-align: center; }
        .logo { position: absolute; left: 0; top: 0; width: 80px; height: auto; }
        .header-text h3, .header-text h2, .header-text p { margin: 2px 0; text-transform: uppercase; }
        
        hr { border: 0; border-bottom: 3px double #000; margin-top: 10px; margin-bottom: 20px; }

        /* TABEL */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; vertical-align: top; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }

        /* TTD */
        .ttd-container { width: 100%; margin-top: 40px; display: flex; justify-content: flex-end; }
        .ttd-box { width: 250px; text-align: center; }
        
        @media print {
            @page { size: landscape; margin: 2cm; }
            body { -webkit-print-color-adjust: exact; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header-container">
        <img src="../../../public/assets/img/logo.png" class="logo" alt="Logo Sekolah">

        <div class="header-text">
            <h3>BUKU AGENDA SURAT KELUAR</h3>
            <h2>SMPN 1 CONCONG</h2>
            <p style="font-size: 10pt; font-weight: normal; text-transform: none;">
                Periode: <?= tgl_indo($tgl_awal) ?> s.d. <?= tgl_indo($tgl_akhir) ?>
            </p>
        </div>
    </div>
    <hr>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="15%">NO. AGENDA</th>
                <th width="15%">TGL SURAT</th>
                <th width="20%">NO. SURAT</th>
                <th width="20%">TUJUAN / PENERIMA</th>
                <th width="25%">PERIHAL</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($data) > 0): ?>
                <?php $no = 1; foreach($data as $row): ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td style="text-align: center; font-weight: bold;"><?= htmlspecialchars($row['no_agenda']) ?></td>
                    <td style="text-align: center;"><?= date('d/m/Y', strtotime($row['tgl_surat'])) ?></td>
                    <td><?= htmlspecialchars($row['no_surat']) ?></td>
                    <td><?= htmlspecialchars($row['tujuan']) ?></td>
                    <td><?= htmlspecialchars($row['perihal']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; font-style: italic;">
                        Tidak ada surat keluar pada rentang tanggal ini.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Concong, <?= tgl_indo(date('Y-m-d')) ?></p>
            <p>Kepala Tata Usaha,</p>
            <br><br><br>
            <p><b>__________________________</b></p>
            <p>NIP. ..............................</p>
        </div>
    </div>

</body>
</html>