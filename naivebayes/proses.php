<?php
session_start();
include '../config/koneksi.php';

// --- 1. SIAPKAN DATA TRAINING ---
$q_training = mysqli_query($koneksi, "SELECT * FROM training");
$data_lulus = [];
$data_telat = [];

if (mysqli_num_rows($q_training) < 1) {
    echo "<script>alert('Data Training kosong!'); window.location='../training/training.php';</script>";
    exit;
}

while ($row = mysqli_fetch_assoc($q_training)) {
    if ($row['kelulusan'] == 'Tepat Waktu') $data_lulus[] = $row;
    else $data_telat[] = $row;
}

// Prior Probability
$total_data = count($data_lulus) + count($data_telat);
$p_lulus = count($data_lulus) / $total_data;
$p_telat = count($data_telat) / $total_data;

// Fungsi Statistik
function get_stats($data, $col) {
    $vals = array_column($data, $col);
    $n = count($vals);
    if ($n == 0) return ['mean' => 0, 'stdev' => 1];
    $mean = array_sum($vals) / $n;
    $variance = 0;
    foreach ($vals as $v) { $variance += pow($v - $mean, 2); }
    $stdev = sqrt($variance / ($n - 1));
    return ['mean' => $mean, 'stdev' => $stdev];
}
function prob_gauss($x, $stats) {
    if ($stats['stdev'] == 0) return 0;
    $exponent = exp(-pow($x - $stats['mean'], 2) / (2 * pow($stats['stdev'], 2)));
    return (1 / (sqrt(2 * M_PI) * $stats['stdev'])) * $exponent;
}
function prob_kategori($val, $data, $col) {
    $count = 0;
    foreach ($data as $d) { if ($d[$col] == $val) $count++; }
    return ($count + 1) / (count($data) + 2);
}

// Hitung Statistik Training
$s_ipk_lulus = get_stats($data_lulus, 'ipk');
$s_sks_lulus = get_stats($data_lulus, 'sks');
$s_hadir_lulus = get_stats($data_lulus, 'kehadiran');
$s_nilai_lulus = get_stats($data_lulus, 'nilai_mk');

$s_ipk_telat = get_stats($data_telat, 'ipk');
$s_sks_telat = get_stats($data_telat, 'sks');
$s_hadir_telat = get_stats($data_telat, 'kehadiran');
$s_nilai_telat = get_stats($data_telat, 'nilai_mk');

// --- 2. AMBIL DATA TESTING TERAKHIR SAJA ---
$q_testing = mysqli_query($koneksi, "SELECT * FROM testing ORDER BY id DESC LIMIT 1");
$test = mysqli_fetch_array($q_testing);

if (!$test) {
    echo "<script>alert('Belum ada data testing!'); window.location='../testing/testing.php';</script>";
    exit;
}

// Hitung Probabilitas
$prob_lulus_val = $p_lulus * prob_gauss($test['ipk'], $s_ipk_lulus) * prob_gauss($test['sks'], $s_sks_lulus) * prob_gauss($test['kehadiran'], $s_hadir_lulus) * prob_gauss($test['nilai_mk'], $s_nilai_lulus) * prob_kategori($test['kerja'], $data_lulus, 'kerja');

$prob_telat_val = $p_telat * prob_gauss($test['ipk'], $s_ipk_telat) * prob_gauss($test['sks'], $s_sks_telat) * prob_gauss($test['kehadiran'], $s_hadir_telat) * prob_gauss($test['nilai_mk'], $s_nilai_telat) * prob_kategori($test['kerja'], $data_telat, 'kerja');

// Normalisasi Persen
$total = $prob_lulus_val + $prob_telat_val;
if ($total == 0) { $persen_lulus = 0; $persen_telat = 0; }
else {
    $persen_lulus = ($prob_lulus_val / $total) * 100;
    $persen_telat = ($prob_telat_val / $total) * 100;
}

$hasil = ($prob_lulus_val >= $prob_telat_val) ? "Tepat Waktu" : "Terlambat";

// --- 3. SIMPAN HASIL KE DATABASE ---
$id_test = $test['id'];
mysqli_query($koneksi, "UPDATE testing SET 
    hasil_prediksi = '$hasil',
    angka_lulus = '$persen_lulus',
    angka_tidak = '$persen_telat'
    WHERE id = '$id_test'");

// --- 4. REDIRECT KE TAMPILAN SATU HASIL (DETAIL) ---
header("Location: ../hasil/detail.php?id=$id_test");
exit;
?>