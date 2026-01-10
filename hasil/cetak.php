<?php
session_start();
// Cek apakah ada data hasil
if (!isset($_SESSION['hasil_keputusan'])) {
    echo "<script>alert('Data hasil kosong!'); window.location='hasil.php';</script>";
    exit;
}

// Ambil data dari session
$data_mhs = $_SESSION['data_test'];
$keputusan = $_SESSION['hasil_keputusan'];
$prob_lulus = number_format($_SESSION['hasil_lulus'], 2);
$prob_telat = number_format($_SESSION['hasil_tidak'], 2); // Probabilitas terlambat
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Hasil Prediksi</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; padding: 40px; }
        .header { text-align: center; border-bottom: 3px double black; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2, .header h3, .header p { margin: 0; }
        .content { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        .no-border { border: none !important; }
        .text-center { text-align: center; }
        .result-box {
            border: 2px solid #000;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin: 20px 0;
            background-color: #f0f0f0;
        }
        .ttd { float: right; margin-top: 50px; text-align: center; width: 200px; }
        
        /* Menyembunyikan tombol saat dicetak */
        @media print {
            .btn-print { display: none; }
        }
        .btn-print {
            background: #0d6efd; color: white; padding: 10px 20px; 
            border: none; cursor: pointer; border-radius: 5px; text-decoration: none;
            display: inline-block; margin-bottom: 20px; font-family: sans-serif;
        }
    </style>
</head>
<body>

    <a href="hasil.php" class="btn-print" style="background: #6c757d;">&laquo; Kembali</a>
    <button onclick="window.print()" class="btn-print">Cetak Halaman / Simpan PDF</button>

    <div class="header">
        <h3>UNIVERSITAS TEKNOLOGI CONTOH</h3>
        <h2>FAKULTAS ILMU KOMPUTER</h2>
        <p>Jl. Raya Kampus No. 123, Kota Besar, Indonesia 54321</p>
        <p>Website: www.kampus.ac.id | Email: info@kampus.ac.id</p>
    </div>

    <div class="content">
        <h3 class="text-center" style="text-decoration: underline;">LAPORAN HASIL PREDIKSI KELULUSAN</h3>
        <p>Berdasarkan analisis data menggunakan metode <strong>Naive Bayes Classifier</strong>, berikut adalah rincian data mahasiswa dan hasil prediksi:</p>

        <h4>A. Data Mahasiswa</h4>
        <table class="no-border" style="width: 60%;">
            <tr class="no-border"><td class="no-border" width="150">IPK</td><td class="no-border">: <?= $data_mhs['ipk'] ?></td></tr>
            <tr class="no-border"><td class="no-border">Jumlah SKS</td><td class="no-border">: <?= $data_mhs['sks'] ?></td></tr>
            <tr class="no-border"><td class="no-border">Kehadiran</td><td class="no-border">: <?= $data_mhs['kehadiran'] ?> Pertemuan</td></tr>
            <tr class="no-border"><td class="no-border">Rata-rata Nilai</td><td class="no-border">: <?= $data_mhs['nilai_mk'] ?></td></tr>
            <tr class="no-border"><td class="no-border">Status Kerja</td><td class="no-border">: <?= $data_mhs['kerja'] == 'Ya' ? 'Bekerja' : 'Tidak Bekerja' ?></td></tr>
        </table>

        <h4 style="margin-top: 20px;">B. Hasil Perhitungan Probabilitas</h4>
        <table>
            <thead>
                <tr style="background-color: #ddd;">
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Nilai Probabilitas (%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Lulus Tepat Waktu</td>
                    <td class="text-center"><?= $prob_lulus ?>%</td>
                </tr>
                <tr>
                    <td>Lulus Terlambat</td>
                    <td class="text-center"><?= $prob_telat ?>%</td>
                </tr>
            </tbody>
        </table>

        <h4 style="margin-top: 20px;">C. Kesimpulan Sistem</h4>
        <p>Berdasarkan nilai probabilitas tertinggi, maka sistem memprediksi bahwa mahasiswa tersebut berpotensi:</p>
        
        <div class="result-box">
            STATUS: <?= strtoupper($keputusan) ?>
        </div>

        <p style="font-size: 12px; font-style: italic;">Catatan: Hasil ini merupakan prediksi komputasi berdasarkan pola data latih dan dapat digunakan sebagai bahan pertimbangan pengambilan keputusan.</p>
    </div>

    <div class="ttd">
        <p>Jakarta, <?= date('d F Y') ?></p>
        <p>Mengetahui,<br>Admin Sistem</p>
        <br><br><br>
        <p><strong>( <?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Administrator' ?> )</strong></p>
    </div>

    <script>
        // Opsional: Otomatis muncul popup print saat halaman dibuka
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>