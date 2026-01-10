<?php
include '../template/header.php';
include '../template/sidebar.php';
include '../config/koneksi.php';

// --- LOGIKA PENCARIAN ---
$keyword = "";
if (isset($_POST['cari'])) {
    $keyword = $_POST['keyword'];
    // Cari berdasarkan IPK, Kerja, atau Hasil Prediksi
    $query_str = "SELECT * FROM testing WHERE hasil_prediksi IS NOT NULL AND (
                  ipk LIKE '%$keyword%' OR 
                  kerja LIKE '%$keyword%' OR 
                  hasil_prediksi LIKE '%$keyword%'
                  ) ORDER BY id DESC";
} else {
    // Default: Tampilkan semua data yang sudah ada hasilnya
    $query_str = "SELECT * FROM testing WHERE hasil_prediksi IS NOT NULL ORDER BY id DESC";
}

$query = mysqli_query($koneksi, $query_str);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-dark"><i class="bi bi-clock-history me-2"></i>Riwayat Hasil Prediksi</h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        
        <div class="row mb-3 g-2">
            <div class="col-md-6">
                <form method="POST" class="d-flex gap-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari Hasil (Tepat/Telat) atau IPK..." value="<?= $keyword ?>">
                    <button type="submit" name="cari" class="btn btn-secondary">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if($keyword != ""): ?>
                        <a href="hasil.php" class="btn btn-outline-danger" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="col-md-6 text-md-end">
                 </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>IPK</th>
                        <th>SKS</th>
                        <th>Kerja</th>
                        <th>Probabilitas</th>
                        <th>Hasil Prediksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($query) > 0) {
                        while($d = mysqli_fetch_array($query)){ 
                            // Format Angka Persen
                            $p_lulus = number_format($d['angka_lulus'], 1);
                            $p_telat = number_format($d['angka_tidak'], 1);
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-bold"><?= $d['ipk'] ?></td>
                            <td><?= $d['sks'] ?></td>
                            <td>
                                <?= ($d['kerja']=='Ya' || $d['kerja']=='Iya') ? '<span class="badge bg-warning text-dark">Kerja</span>' : 'Tidak' ?>
                            </td>
                            <td class="small text-start" style="width: 150px;">
                                <div class="d-flex justify-content-between">
                                    <span>Tepat:</span> <strong class="text-success"><?= $p_lulus ?>%</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Telat:</span> <strong class="text-danger"><?= $p_telat ?>%</strong>
                                </div>
                            </td>
                            <td>
                                <?php if($d['hasil_prediksi'] == 'Tepat Waktu'): ?>
                                    <span class="badge bg-success px-3 py-2 rounded-pill">TEPAT WAKTU</span>
                                <?php else: ?>
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">TERLAMBAT</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="detail.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-info text-white shadow-sm" title="Lihat Detail & Cetak">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php 
                        } 
                    } else { 
                    ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-1 d-block mb-3 opacity-50"></i>
                                Data riwayat tidak ditemukan.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>