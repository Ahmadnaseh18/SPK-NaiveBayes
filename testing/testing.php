<?php
include '../template/header.php';
include '../template/sidebar.php';
include '../config/koneksi.php';

// Cek Login (User Biasa Boleh Akses)
if (!isset($_SESSION['login'])) {
    echo "<script>alert('Silahkan Login Terlebih Dahulu!'); window.location='../auth/login.php';</script>";
    exit;
}

// --- LOGIKA PENCARIAN ---
$keyword = "";
if (isset($_POST['cari'])) {
    $keyword = $_POST['keyword'];
    // Mencari berdasarkan IPK, SKS, atau Status Kerja
    $query_str = "SELECT * FROM testing WHERE 
                  ipk LIKE '%$keyword%' OR 
                  sks LIKE '%$keyword%' OR 
                  kerja LIKE '%$keyword%' 
                  ORDER BY id DESC";
} else {
    // Default: Tampilkan semua
    $query_str = "SELECT * FROM testing ORDER BY id DESC";
}

$data = mysqli_query($koneksi, $query_str);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-success"><i class="bi bi-clipboard-check me-2"></i>Data Testing</h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        
        <div class="row mb-3 g-2">
            <div class="col-md-6">
                <a href="tambah.php" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Data Testing
                </a>
            </div>
            <div class="col-md-6">
                <form method="POST" class="d-flex gap-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari IPK, SKS, atau Status..." value="<?= $keyword ?>">
                    <button type="submit" name="cari" class="btn btn-secondary">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if($keyword != ""): ?>
                        <a href="testing.php" class="btn btn-outline-danger" title="Reset"><i class="bi bi-x-lg"></i></a>
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
                                <a href="edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-warning text-white me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <?php if($_SESSION['role'] == 'admin'): ?>
                                <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data testing ini?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                        } 
                    } else { 
                    ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-clipboard-x fs-1 d-block mb-2 opacity-50"></i>
                                Data testing tidak ditemukan.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>