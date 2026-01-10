<?php
include '../template/header.php';
include '../template/sidebar.php';
include '../config/koneksi.php';

// Cek Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location='../dashboard.php';</script>";
    exit;
}

// --- 1. SIAPKAN DATA & STATISTIK ---
// Kita ambil semua data training untuk diuji kembali (Self-Consistency Test)
$q_data = mysqli_query($koneksi, "SELECT * FROM training");
$data_test = [];
$data_lulus = []; // Kelompok Tepat Waktu
$data_telat = []; // Kelompok Terlambat

while ($row = mysqli_fetch_assoc($q_data)) {
    $data_test[] = $row; // Simpan untuk diloop nanti
    if ($row['kelulusan'] == 'Tepat Waktu') $data_lulus[] = $row;
    else $data_telat[] = $row;
}

// Hitung Prior Probability
$total_data = count($data_test);
if ($total_data == 0) {
    echo "<div class='alert alert-danger m-4'>Data Training Masih Kosong!</div>"; 
    include '../template/footer.php'; exit; 
}

$p_lulus = count($data_lulus) / $total_data;
$p_telat = count($data_telat) / $total_data;

// FUNGSI BANTU (Sama seperti di proses.php)
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

// Hitung Statistik Sekali Saja
$s_ipk_lulus = get_stats($data_lulus, 'ipk');
$s_sks_lulus = get_stats($data_lulus, 'sks');
$s_hadir_lulus = get_stats($data_lulus, 'kehadiran');
$s_nilai_lulus = get_stats($data_lulus, 'nilai_mk');

$s_ipk_telat = get_stats($data_telat, 'ipk');
$s_sks_telat = get_stats($data_telat, 'sks');
$s_hadir_telat = get_stats($data_telat, 'kehadiran');
$s_nilai_telat = get_stats($data_telat, 'nilai_mk');

// --- 2. MULAI PENGUJIAN OTOMATIS ---
$benar = 0;
$salah = 0;

// Variabel Confusion Matrix
$TP = 0; // Prediksi Tepat, Asli Tepat
$TN = 0; // Prediksi Telat, Asli Telat
$FP = 0; // Prediksi Tepat, Padahal Telat
$FN = 0; // Prediksi Telat, Padahal Tepat

$hasil_uji = []; // Untuk tabel detail

foreach ($data_test as $dt) {
    // Hitung Probabilitas Lulus
    $prob_lulus_val = $p_lulus * prob_gauss($dt['ipk'], $s_ipk_lulus) * prob_gauss($dt['sks'], $s_sks_lulus) * prob_gauss($dt['kehadiran'], $s_hadir_lulus) * prob_gauss($dt['nilai_mk'], $s_nilai_lulus) * prob_kategori($dt['kerja'], $data_lulus, 'kerja');

    // Hitung Probabilitas Telat
    $prob_telat_val = $p_telat * prob_gauss($dt['ipk'], $s_ipk_telat) * prob_gauss($dt['sks'], $s_sks_telat) * prob_gauss($dt['kehadiran'], $s_hadir_telat) * prob_gauss($dt['nilai_mk'], $s_nilai_telat) * prob_kategori($dt['kerja'], $data_telat, 'kerja');

    // Keputusan Sistem
    $prediksi = ($prob_lulus_val >= $prob_telat_val) ? "Tepat Waktu" : "Terlambat";
    $asli = $dt['kelulusan'];

    // Cek Benar/Salah & Matrix
    if ($prediksi == $asli) {
        $benar++;
        $status = "Sesuai";
        if($prediksi == "Tepat Waktu") $TP++;
        else $TN++;
    } else {
        $salah++;
        $status = "Meleset";
        if($prediksi == "Tepat Waktu") $FP++; // Prediksi Tepat, Padahal Aslinya Terlambat
        else $FN++; // Prediksi Terlambat, Padahal Aslinya Tepat
    }

    // Simpan ke array untuk ditampilkan di tabel bawah
    $hasil_uji[] = [
        'nama' => 'Data ID-'.$dt['id'], // Atau nama mahasiswa jika ada
        'asli' => $asli,
        'prediksi' => $prediksi,
        'status' => $status
    ];
}

// Hitung Persentase Akurasi
$akurasi = ($benar / $total_data) * 100;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-primary"><i class="bi bi-bullseye me-2"></i>Uji Akurasi Sistem</h3>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm h-100">
            <div class="card-body text-center">
                <h6 class="text-uppercase opacity-75">Total Data Uji</h6>
                <h2 class="fw-bold mb-0"><?= $total_data ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm h-100">
            <div class="card-body text-center">
                <h6 class="text-uppercase opacity-75">Prediksi Benar</h6>
                <h2 class="fw-bold mb-0"><?= $benar ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white shadow-sm h-100">
            <div class="card-body text-center">
                <h6 class="text-uppercase opacity-75">Prediksi Salah</h6>
                <h2 class="fw-bold mb-0"><?= $salah ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm h-100">
            <div class="card-body text-center">
                <h6 class="text-uppercase opacity-75">Tingkat Akurasi</h6>
                <h2 class="fw-bold mb-0"><?= round($akurasi, 2) ?>%</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-grid-3x3 me-2"></i> Confusion Matrix
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <table class="table table-bordered text-center w-100" style="border-width: 2px;">
                    <thead class="bg-light">
                        <tr>
                            <th class="align-middle p-3"></th>
                            <th class="align-middle p-3">Prediksi: <br><span class="text-success">Tepat Waktu</span></th>
                            <th class="align-middle p-3">Prediksi: <br><span class="text-danger">Terlambat</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="align-middle bg-light">Aktual: <br><span class="text-success">Tepat Waktu</span></th>
                            <td class="align-middle p-4">
                                <h3 class="fw-bold text-primary mb-0"><?= $TP ?></h3>
                                <small class="text-muted">True Positive</small>
                            </td>
                            <td class="align-middle p-4 bg-light">
                                <h4 class="fw-bold text-secondary mb-0"><?= $FN ?></h4>
                                <small class="text-muted">False Negative</small>
                            </td>
                        </tr>
                        <tr>
                            <th class="align-middle bg-light">Aktual: <br><span class="text-danger">Terlambat</span></th>
                            <td class="align-middle p-4 bg-light">
                                <h4 class="fw-bold text-secondary mb-0"><?= $FP ?></h4>
                                <small class="text-muted">False Positive</small>
                            </td>
                            <td class="align-middle p-4">
                                <h3 class="fw-bold text-primary mb-0"><?= $TN ?></h3>
                                <small class="text-muted">True Negative</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-info-circle me-2"></i> Analisis Hasil
            </div>
            <div class="card-body">
                <p class="text-justify">
                    Berdasarkan pengujian terhadap <strong><?= $total_data ?> data training</strong>, sistem Naive Bayes memiliki tingkat akurasi sebesar <strong><?= round($akurasi, 2) ?>%</strong>.
                </p>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><strong class="text-primary">True Positive (TP)</strong>: Sistem benar memprediksi 'Tepat Waktu'.</div>
                        <span class="badge bg-primary rounded-pill"><?= $TP ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><strong class="text-primary">True Negative (TN)</strong>: Sistem benar memprediksi 'Terlambat'.</div>
                        <span class="badge bg-primary rounded-pill"><?= $TN ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><strong class="text-secondary">False Positive (FP)</strong>: Salah prediksi (Dikira Tepat, padahal Telat).</div>
                        <span class="badge bg-secondary rounded-pill"><?= $FP ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><strong class="text-secondary">False Negative (FN)</strong>: Salah prediksi (Dikira Telat, padahal Tepat).</div>
                        <span class="badge bg-secondary rounded-pill"><?= $FN ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-bold d-flex justify-content-between">
        <span><i class="bi bi-table me-2"></i>Rincian Prediksi Per Data</span>
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetail">
            Tampilkan/Sembunyikan
        </button>
    </div>
    <div class="collapse show" id="collapseDetail">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover table-striped mb-0 text-center small">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>No</th>
                            <th>Status Asli</th>
                            <th>Prediksi Sistem</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($hasil_uji as $h): 
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?= ($h['asli']=='Tepat Waktu') 
                                    ? '<span class="badge bg-success">Tepat Waktu</span>' 
                                    : '<span class="badge bg-danger">Terlambat</span>' ?>
                            </td>
                            <td>
                                <?= ($h['prediksi']=='Tepat Waktu') 
                                    ? '<span class="text-success fw-bold">Tepat Waktu</span>' 
                                    : '<span class="text-danger fw-bold">Terlambat</span>' ?>
                            </td>
                            <td>
                                <?php if($h['status'] == 'Sesuai'): ?>
                                    <span class="badge bg-info text-dark"><i class="bi bi-check-lg"></i> Sesuai</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><i class="bi bi-x-lg"></i> Meleset</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>