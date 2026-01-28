<?php
// config/app/surat_masuk/cetak_agenda.php
require_once '../../database.php';

// Validasi jika diakses langsung tanpa parameter
if (!isset($_GET['tgl_awal']) || !isset($_GET['tgl_akhir'])) {
    die("Harap pilih tanggal terlebih dahulu.");
}

$tgl_awal = $_GET['tgl_awal'];
$tgl_akhir = $_GET['tgl_akhir'];

// Query Data Berdasarkan Rentang Tanggal
// Menggunakan 'tgl_diterima' sebagai acuan agenda
$sql = "SELECT * FROM surat_masuk 
        WHERE tgl_diterima BETWEEN ? AND ? 
        ORDER BY tgl_diterima ASC, id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$tgl_awal, $tgl_akhir]);
$data = $stmt->fetchAll();

// Fungsi format tanggal indo sederhana
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
    <title>Agenda Surat Masuk</title>
    <style>
        /* CSS KHUSUS UNTUK CETAK (PRINT) */
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h3, .header h2, .header p {
            margin: 2px 0;
            text-transform: uppercase;
        }
        hr {
            border: 0;
            border-bottom: 2px double #000; /* Garis ganda tebal tipis */
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0; /* Warna abu muda saat di layar */
            text-align: center;
            font-weight: bold;
        }
        /* Tanda Tangan */
        .ttd-container {
            width: 100%;
            margin-top: 40px;
            display: flex;
            justify-content: flex-end; /* Geser ke kanan */
        }
        .ttd-box {
            width: 250px;
            text-align: center;
        }
        
        /* PENGATURAN SAAT DIPRINT */
        @media print {
            @page {
                size: landscape; /* Kertas Tidur */
                margin: 2cm;
            }
            body { 
                -webkit-print-color-adjust: exact; 
                padding: 0;
            }
            /* Hilangkan elemen header/footer browser default jika browser mendukung */
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h3>BUKU AGENDA SURAT MASUK</h3>
        <h2>SMP NUSANTARA</h2>
        <p>Periode: <?= tgl_indo($tgl_awal) ?> s.d. <?= tgl_indo($tgl_akhir) ?></p>
    </div>
    <hr>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="15%">NO. AGENDA</th>
                <th width="15%">TGL TERIMA</th>
                <th width="20%">NO. SURAT</th>
                <th width="20%">PENGIRIM</th>
                <th width="25%">PERIHAL</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($data) > 0): ?>
                <?php $no = 1; foreach($data as $row): ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td style="text-align: center; font-weight: bold;"><?= htmlspecialchars($row['no_agenda']) ?></td>
                    <td style="text-align: center;"><?= date('d/m/Y', strtotime($row['tgl_diterima'])) ?></td>
                    <td>
                        <?= htmlspecialchars($row['no_surat']) ?><br>
                        <small><i>Tgl Surat: <?= date('d/m/Y', strtotime($row['tgl_surat'])) ?></i></small>
                    </td>
                    <td><?= htmlspecialchars($row['pengirim']) ?></td>
                    <td><?= htmlspecialchars($row['perihal']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; font-style: italic;">
                        Tidak ada surat masuk pada rentang tanggal ini.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Kota Pelajar, <?= tgl_indo(date('Y-m-d')) ?></p>
            <p>Kepala Tata Usaha,</p>
            <br><br><br> <p><b>__________________________</b></p>
            <p>NIP. ..............................</p>
        </div>
    </div>

</body>
</html>