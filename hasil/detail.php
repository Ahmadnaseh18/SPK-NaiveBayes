<?php
include '../template/header.php';
include '../template/sidebar.php';
include '../config/koneksi.php';

// Ambil ID dari URL, jika tidak ada ambil yang terakhir
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($koneksi, "SELECT * FROM testing WHERE id='$id'");
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM testing ORDER BY id DESC LIMIT 1");
}

$data = mysqli_fetch_assoc($query);

// Jika data kosong atau belum diproses
if (!$data || $data['hasil_prediksi'] == NULL) {
    echo "<script>alert('Data belum diproses! Silahkan klik menu Proses Naive Bayes.'); window.location='../naivebayes/proses.php';</script>";
    exit;
}

// Siapkan Variabel Tampilan
$keputusan = $data['hasil_prediksi'];
$prob_lulus = number_format($data['angka_lulus'], 2);
$prob_telat = number_format($data['angka_tidak'], 2);
$badge_class = ($keputusan == 'Tepat Waktu') ? 'bg-success' : 'bg-danger';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Hasil Prediksi Terakhir</h3>
    <div>
        <a href="hasil.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-table"></i> Lihat Semua Riwayat</a>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white text-center py-3">
                <h5 class="mb-0 fw-bold text-uppercase">Keputusan Sistem</h5>
            </div>
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <h6 class="text-muted mb-3">Mahasiswa ini diprediksi akan lulus:</h6>
                
                <div class="display-6 fw-bold text-white p-4 rounded-3 shadow-sm <?= $badge_class ?> mb-4">
                    <?= strtoupper($keputusan) ?>
                </div>

                <div class="row mt-2">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block">Peluang Tepat Waktu</small>
                        <span class="fs-4 fw-bold text-success"><?= $prob_lulus ?>%</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Peluang Terlambat</small>
                        <span class="fs-4 fw-bold text-danger"><?= $prob_telat ?>%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill"></i> Data yang Diuji</h6>
            </div>
            <div class="card-body">
                <table class="table table-striped mb-0">
                    <tr><td width="40%">IPK</td><td class="fw-bold"><?= $data['ipk'] ?></td></tr>
                    <tr><td>SKS</td><td class="fw-bold"><?= $data['sks'] ?></td></tr>
                    <tr><td>Kehadiran</td><td class="fw-bold"><?= $data['kehadiran'] ?></td></tr>
                    <tr><td>Nilai MK</td><td class="fw-bold"><?= $data['nilai_mk'] ?></td></tr>
                    <tr><td>Bekerja?</td>
                        <td class="fw-bold">
                            <?= ($data['kerja'] == 'Ya' || $data['kerja'] == 'Iya') ? '<span class="badge bg-warning text-dark">Ya</span>' : 'Tidak' ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="text-center mb-5 d-flex justify-content-center gap-3">
    <a href="../testing/tambah.php" class="btn btn-outline-secondary btn-lg px-4">
        <i class="bi bi-plus-lg"></i> Input Data Baru
    </a>
    <a href="cetak.php?id=<?= $data['id'] ?>" target="_blank" class="btn btn-primary btn-lg px-4">
        <i class="bi bi-printer-fill"></i> Cetak PDF
    </a>
</div>

<?php include '../template/footer.php'; ?>