<?php 
include 'template/header.php'; 
include 'template/sidebar.php'; 
include 'config/koneksi.php';

// --- LOGIKA DATA ---

// 1. Hitung Total Data
$training_count = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM training"));
$testing_count  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM testing"));

// 2. Data untuk PIE CHART (Lulus vs Terlambat)
$q_tepat = mysqli_query($koneksi, "SELECT * FROM training WHERE kelulusan='Tepat Waktu'");
$jml_tepat = mysqli_num_rows($q_tepat);
$q_telat = mysqli_query($koneksi, "SELECT * FROM training WHERE kelulusan='Terlambat'");
$jml_telat = mysqli_num_rows($q_telat);

// 3. Data untuk BAR CHART (Hubungan Kerja vs Kelulusan)
// Kita hitung manual querynya biar ringkas
$kerja_tepat = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM training WHERE kerja='Ya' AND kelulusan='Tepat Waktu'"));
$kerja_telat = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM training WHERE kerja='Ya' AND kelulusan='Terlambat'"));
$tidak_tepat = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM training WHERE kerja='Tidak' AND kelulusan='Tepat Waktu'"));
$tidak_telat = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM training WHERE kerja='Tidak' AND kelulusan='Terlambat'"));

// 4. Data Tabel Terbaru
$q_latest = mysqli_query($koneksi, "SELECT * FROM testing ORDER BY id DESC LIMIT 5");

// --- LOGIKA WAKTU (GREETING) ---
date_default_timezone_set('Asia/Jakarta');
$jam = date('H');
if ($jam >= 5 && $jam < 11) { $sapa = "Selamat Pagi"; $emoji = "☕"; }
elseif ($jam >= 11 && $jam < 15) { $sapa = "Selamat Siang"; $emoji = "☀️"; }
elseif ($jam >= 15 && $jam < 18) { $sapa = "Selamat Sore"; $emoji = "🌇"; }
else { $sapa = "Selamat Malam"; $emoji = "🌙"; }
?>

<style>
    .card-stat {
        border: none;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s;
        color: white;
    }
    .card-stat:hover { transform: translateY(-5px); }
    .card-stat .icon-bg {
        position: absolute;
        right: 10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.2;
        transform: rotate(-15deg);
    }
    .bg-gradient-primary { background: linear-gradient(45deg, #4e73df, #224abe); }
    .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); }
    .bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #dda20a); }
    .bg-gradient-danger  { background: linear-gradient(45deg, #e74a3b, #be2617); }
    
    .welcome-banner {
        background: linear-gradient(135deg, #ffffff 0%, #e3f2fd 100%);
        border-radius: 20px;
        border-left: 5px solid #0d6efd;
    }
</style>

<div class="welcome-banner p-4 mb-4 shadow-sm d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold text-primary mb-1"><?= $sapa ?>! <?= $emoji ?></h4>
        <p class="text-muted mb-0 small">
            <i class="bi bi-calendar-event me-1"></i> <?= date('l, d F Y') ?> &bull; 
            Sistem siap digunakan untuk prediksi hari ini.
        </p>
    </div>
    <div class="d-none d-md-block">
        <a href="testing/tambah.php" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i> Prediksi Baru
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat bg-gradient-primary h-100 py-2">
            <div class="card-body">
                <div class="text-uppercase text-xs fw-bold mb-1 opacity-75">Data Training</div>
                <div class="h2 mb-0 fw-bold"><?= $training_count ?></div>
                <div class="small opacity-75 mt-2">Data Pengetahuan</div>
                <i class="bi bi-database-fill icon-bg"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat bg-gradient-success h-100 py-2">
            <div class="card-body">
                <div class="text-uppercase text-xs fw-bold mb-1 opacity-75">Total Prediksi</div>
                <div class="h2 mb-0 fw-bold"><?= $testing_count ?></div>
                <div class="small opacity-75 mt-2">Mahasiswa Diuji</div>
                <i class="bi bi-clipboard-data-fill icon-bg"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat bg-gradient-warning h-100 py-2">
            <div class="card-body">
                <div class="text-uppercase text-xs fw-bold mb-1 opacity-75">Tepat Waktu</div>
                <div class="h2 mb-0 fw-bold">
                    <?= ($training_count > 0) ? round(($jml_tepat / $training_count) * 100) : 0 ?>%
                </div>
                <div class="small opacity-75 mt-2">Dominasi Data</div>
                <i class="bi bi-pie-chart-fill icon-bg"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat bg-gradient-danger h-100 py-2">
            <div class="card-body">
                <div class="text-uppercase text-xs fw-bold mb-1 opacity-75">Terlambat</div>
                <div class="h2 mb-0 fw-bold"><?= $jml_telat ?></div>
                <div class="small opacity-75 mt-2">Mahasiswa</div>
                <i class="bi bi-hourglass-bottom icon-bg"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-7 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-bar-chart-line me-2"></i>Analisa: Pekerjaan vs Kelulusan</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="barChartKerja"></canvas>
                </div>
                <hr>
                <small class="text-muted">
                    Grafik ini menunjukkan perbandingan jumlah kelulusan berdasarkan status mahasiswa (Bekerja / Tidak).
                </small>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 fw-bold text-success"><i class="bi bi-pie-chart me-2"></i>Komposisi Data Latih</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="pieChartLulus"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="me-2"><i class="bi bi-circle-fill text-success"></i> Tepat Waktu</span>
                    <span class="me-2"><i class="bi bi-circle-fill text-danger"></i> Terlambat</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-secondary"><i class="bi bi-lightning-charge me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="training/tambah.php" class="btn btn-outline-primary text-start">
                        <i class="bi bi-database-add me-2"></i> Tambah Data Training
                    </a>
                    <a href="testing/testing.php" class="btn btn-outline-success text-start">
                        <i class="bi bi-table me-2"></i> Lihat Semua Data Testing
                    </a>
                    <a href="naivebayes/proses.php" class="btn btn-outline-warning text-start">
                        <i class="bi bi-calculator me-2"></i> Hitung Ulang Naive Bayes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-clock-history me-2"></i>Riwayat Input Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>IPK</th>
                                <th>SKS</th>
                                <th>Status Kerja</th>
                                <th>Waktu Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no_rt = 1;
                            if (mysqli_num_rows($q_latest) > 0) {
                                while($rt = mysqli_fetch_array($q_latest)) { 
                            ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= $no_rt++ ?></td>
                                <td class="fw-bold text-primary"><?= $rt['ipk'] ?></td>
                                <td><?= $rt['sks'] ?></td>
                                <td>
                                    <?php if($rt['kerja'] == 'Ya'): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-briefcase"></i> Kerja</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Fokus Kuliah</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small">Baru saja</td>
                            </tr>
                            <?php } } else { echo "<tr><td colspan='5' class='text-center py-3'>Belum ada data.</td></tr>"; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. CONFIG BAR CHART (KERJA VS KELULUSAN)
    const ctxBar = document.getElementById('barChartKerja').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Bekerja', 'Tidak Bekerja'],
            datasets: [
                {
                    label: 'Tepat Waktu',
                    data: [<?= $kerja_tepat ?>, <?= $tidak_tepat ?>],
                    backgroundColor: '#1cc88a',
                    borderRadius: 5
                },
                {
                    label: 'Terlambat',
                    data: [<?= $kerja_telat ?>, <?= $tidak_telat ?>],
                    backgroundColor: '#e74a3b',
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { position: 'top' } }
        }
    });

    // 2. CONFIG PIE CHART (SEBARAN DATA)
    const ctxPie = document.getElementById('pieChartLulus').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Tepat Waktu', 'Terlambat'],
            datasets: [{
                data: [<?= $jml_tepat ?>, <?= $jml_telat ?>],
                backgroundColor: ['#1cc88a', '#e74a3b'],
                hoverOffset: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            cutout: '70%',
        }
    });
</script>

<?php include 'template/footer.php'; ?>