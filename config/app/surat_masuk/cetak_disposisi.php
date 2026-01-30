<?php
// config/app/surat_masuk/cetak_disposisi.php
require_once '../../database.php';

// Validasi ID
if (!isset($_GET['id'])) {
    die("Error: ID Surat tidak ditemukan.");
}

$id = $_GET['id'];

// Ambil Data Surat
$stmt = $pdo->prepare("SELECT * FROM surat_masuk WHERE id = ?");
$stmt->execute([$id]);
$surat = $stmt->fetch();

if (!$surat) {
    die("Data surat tidak ditemukan.");
}

function tgl_indo($tanggal){
    $bulan = array (1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Disposisi - <?= $surat['no_agenda'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; padding: 20px; }
        
        /* Layout Utama Box */
        .container {
            border: 2px solid #000;
            padding: 10px;
            width: 100%;
            max-width: 800px; /* Batas lebar agar rapi di A4 */
            margin: 0 auto;
        }

        /* Header */
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 15px; position: relative; }
        .logo { width: 70px; position: absolute; left: 10px; top: 0; }
        .header h3, .header h2 { margin: 2px 0; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10pt; }

        /* Judul Surat */
        .judul-disposisi { text-align: center; font-weight: bold; font-size: 14pt; text-decoration: underline; margin-bottom: 20px; text-transform: uppercase; }

        /* Tabel Info Surat */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 130px; }

        /* Kotak Isian (Grid System Sederhana) */
        .grid-box { display: flex; border: 1px solid #000; }
        .col { flex: 1; padding: 10px; border-right: 1px solid #000; }
        .col:last-child { border-right: none; }
        
        .box-title { font-weight: bold; text-align: center; margin-bottom: 10px; text-decoration: underline; }
        
        /* List Checkbox Manual */
        ul { list-style: none; padding: 0; margin: 0; }
        li { margin-bottom: 8px; }
        .checkbox {
            display: inline-block;
            width: 15px; height: 15px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
        }

        @media print {
            @page { size: auto; margin: 1cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container">
        <div class="header">
            <img src="../../../public/assets/img/logo.png" class="logo" alt="Logo">
            <h3>PEMERINTAH KABUPATEN</h3>
            <h2>SMPN 1 CONCONG</h2>
            <p>Alamat: Jl. Pendidikan No. 1, Concong Luar, Indragiri Hilir, Riau</p>
        </div>

        <div class="judul-disposisi">LEMBAR DISPOSISI</div>

        <table class="info-table">
            <tr>
                <td class="label">Surat Dari</td>
                <td>: <?= htmlspecialchars($surat['pengirim']) ?></td>
                <td class="label">Diterima Tgl</td>
                <td>: <?= tgl_indo($surat['tgl_diterima']) ?></td>
            </tr>
            <tr>
                <td class="label">No. Surat</td>
                <td>: <?= htmlspecialchars($surat['no_surat']) ?></td>
                <td class="label">No. Agenda</td>
                <td>: <b>#<?= htmlspecialchars($surat['no_agenda']) ?></b></td>
            </tr>
            <tr>
                <td class="label">Tgl. Surat</td>
                <td>: <?= tgl_indo($surat['tgl_surat']) ?></td>
                <td class="label">Sifat</td>
                <td>: Biasa / Penting / Rahasia *)</td>
            </tr>
            <tr>
                <td class="label">Perihal</td>
                <td colspan="3" style="border-bottom: 1px dashed #999;">: <?= htmlspecialchars($surat['perihal']) ?></td>
            </tr>
        </table>

        <div class="grid-box">
            <div class="col">
                <div class="box-title">Diteruskan Kepada Sdr:</div>
                <ul>
                    <li><span class="checkbox"></span> Wakil Kepala Sekolah</li>
                    <li><span class="checkbox"></span> Urusan Kurikulum</li>
                    <li><span class="checkbox"></span> Urusan Kesiswaan</li>
                    <li><span class="checkbox"></span> Urusan Sarana Prasarana</li>
                    <li><span class="checkbox"></span> Urusan Humas</li>
                    <li><span class="checkbox"></span> Pembina OSIS</li>
                    <li><span class="checkbox"></span> Koordinator BK</li>
                    <li><span class="checkbox"></span> Wali Kelas .....................</li>
                    <li><span class="checkbox"></span> Arsip</li>
                </ul>
            </div>

            <div class="col">
                <div class="box-title">Isi Disposisi / Instruksi:</div>
                <ul>
                    <li><span class="checkbox"></span> Tanggapi dan saran</li>
                    <li><span class="checkbox"></span> Proses lebih lanjut</li>
                    <li><span class="checkbox"></span> Konfirmasikan</li>
                    <li><span class="checkbox"></span> Edarkan / Umumkan</li>
                    <li><span class="checkbox"></span> File / Arsipkan</li>
                    <li><span class="checkbox"></span> ...............................................</li>
                </ul>
                
                <br>
                <div style="border-top: 1px solid #000; padding-top: 5px;">
                    <b>Catatan Khusus:</b>
                    <br><br><br><br> </div>
            </div>
        </div>

        <div style="margin-top: 20px; float: right; text-align: center; width: 200px;">
            <p>Kepala Sekolah,</p>
            <br><br><br>
            <p><b>_______________________</b></p>
            <p>NIP. .............................</p>
        </div>
        <div style="clear: both;"></div>

    </div>

    <center class="no-print" style="margin-top: 20px;">
        <small>*) Coret yang tidak perlu</small>
    </center>

</body>
</html>