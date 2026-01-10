<?php
include "../template/header.php";
include "../template/sidebar.php";
include "../config/koneksi.php";

// 1. Cek Login & Parameter ID
if (!isset($_SESSION['login'])) {
    echo "<script>window.location='../auth/login.php';</script>";
    exit;
}
if(!isset($_GET['id'])){
    echo "<script>alert('ID tidak ditemukan!'); window.location='testing.php';</script>";
    exit;
}

$id = $_GET['id'];

// 2. Ambil Data Lama
$query = mysqli_query($koneksi, "SELECT * FROM testing WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if(!$data){
    echo "<script>alert('Data tidak ada!'); window.location='testing.php';</script>";
    exit;
}

// 3. Proses Update
if(isset($_POST['update'])){
    $ipk = $_POST['ipk'];
    $sks = $_POST['sks'];
    $kehadiran = $_POST['kehadiran'];
    $nilai = $_POST['nilai_mk'];
    $kerja = $_POST['kerja'];

    $update = mysqli_query($koneksi, "UPDATE testing SET 
        ipk='$ipk', 
        sks='$sks', 
        kehadiran='$kehadiran', 
        nilai_mk='$nilai', 
        kerja='$kerja'
        WHERE id='$id'");
    
    if($update){
        echo "<script>alert('Data Testing Berhasil Diubah!'); window.location='testing.php';</script>";
    } else {
        echo "<script>alert('Gagal Update: ".mysqli_error($koneksi)."');</script>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-warning"><i class="bi bi-pencil-square me-2"></i>Edit Data Testing</h3>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark fw-bold">
                <i class="bi bi-card-checklist"></i> Form Edit Data Uji
            </div>
            <div class="card-body">
                <form method="post">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">IPK Mahasiswa</label>
                        <input type="number" step="0.01" name="ipk" class="form-control" value="<?= $data['ipk'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Jumlah SKS</label>
                        <input type="number" name="sks" class="form-control" value="<?= $data['sks'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kehadiran (Pertemuan)</label>
                        <input type="number" name="kehadiran" class="form-control" value="<?= $data['kehadiran'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Rata-rata Nilai MK</label>
                        <input type="number" name="nilai_mk" class="form-control" value="<?= $data['nilai_mk'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Status Pekerjaan</label>
                        <select name="kerja" class="form-select" required>
                            <option value="Tidak" <?= ($data['kerja'] == 'Tidak') ? 'selected' : '' ?>>Tidak Bekerja</option>
                            <option value="Ya" <?= ($data['kerja'] == 'Ya' || $data['kerja'] == 'Iya') ? 'selected' : '' ?>>Bekerja (Ya)</option>
                        </select>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning fw-bold" type="submit" name="update">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="testing.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="alert alert-info shadow-sm">
            <h5><i class="bi bi-info-circle-fill"></i> Informasi</h5>
            <p class="small mb-0 text-justify">
                Anda sedang mengubah data yang akan diuji (Data Testing).
                <br><br>
                Setelah mengubah data ini, Anda disarankan untuk melakukan <strong>Hitung Ulang</strong> di menu Dashboard atau tombol Proses agar hasil prediksinya diperbarui sesuai data terbaru.
            </p>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>