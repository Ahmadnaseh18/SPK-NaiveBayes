<?php
include '../template/header.php';
include '../template/sidebar.php';
include '../config/koneksi.php';

// Cek Role Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses Ditolak!'); window.location='../dashboard.php';</script>";
    exit;
}

// LOGIKA PENCARIAN
$keyword = "";
if (isset($_POST['cari'])) {
    $keyword = $_POST['keyword'];
    // Mencari data berdasarkan IPK, Kelulusan, atau Status Kerja
    $query_str = "SELECT * FROM training WHERE 
                  kelulusan LIKE '%$keyword%' OR 
                  kerja LIKE '%$keyword%' OR 
                  ipk LIKE '%$keyword%' 
                  ORDER BY id DESC";
} else {
    // Jika tidak mencari, tampilkan semua
    $query_str = "SELECT * FROM training ORDER BY id DESC";
}

$data = mysqli_query($koneksi, $query_str);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-primary"><i class="bi bi-database me-2"></i>Data Training</h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        
        <div class="row mb-3 g-2">
            <div class="col-md-6">
                <a href="tambah.php" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Data
                </a>
                <a href="upload.php" class="btn btn-success shadow-sm ms-1">
                    <i class="bi bi-file-earmark-excel me-1"></i> Upload Excel
                </a>
            </div>
            <div class="col-md-6">
                <form method="POST" class="d-flex gap-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari IPK, Status, atau Kelulusan..." value="<?= $keyword ?>">
                    <button type="submit" name="cari" class="btn btn-secondary">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if($keyword != ""): ?>
                        <a href="training.php" class="btn btn-outline-danger" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>IPK</th>
                        <th>SKS</th>
                        <th>Kehadiran</th>
                        <th>Nilai MK</th>
                        <th>Kerja</th>
                        <th>Kelulusan</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($data && mysqli_num_rows($data) > 0) {
                        while($d = mysqli_fetch_array($data)){ 
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $d['ipk'] ?></td>
                            <td><?= $d['sks'] ?></td>
                            <td><?= $d['kehadiran'] ?></td>
                            <td><?= $d['nilai_mk'] ?></td>
                            <td>
                                <?php if($d['kerja'] == 'Ya' || $d['kerja'] == 'Iya'): ?>
                                    <span class="badge bg-warning text-dark">Bekerja</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tidak</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $d['kelulusan'] == 'Tepat Waktu' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $d['kelulusan'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-warning text-white me-1" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus Data">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php 
                        } 
                    } else { 
                    ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-search fs-1 d-block mb-2 opacity-50"></i>
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>