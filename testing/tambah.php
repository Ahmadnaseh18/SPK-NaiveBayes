<?php
include "../template/header.php";
include "../template/sidebar.php";
include "../config/koneksi.php";

// Cek Login
if (!isset($_SESSION['login'])) {
    echo "<script>alert('Akses Ditolak! Harap Login.'); window.location='../auth/login.php';</script>";
    exit;
}

if(isset($_POST['simpan'])){
    // Ambil data dari form
    $ipk = $_POST['ipk'];
    $sks = $_POST['sks'];
    $kehadiran = $_POST['kehadiran'];
    $nilai = $_POST['nilai_mk'];
    $kerja = $_POST['kerja'];

    // Insert ke database
    $query = mysqli_query($koneksi,"INSERT INTO testing (ipk, sks, kehadiran, nilai_mk, kerja) VALUES ('$ipk', '$sks', '$kehadiran', '$nilai', '$kerja')");
    
    if($query){
        echo "<script>alert('Data Berhasil Disimpan!'); window.location='testing.php';</script>";
    } else {
        echo "<script>alert('Gagal Menyimpan Data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">Tambah Data Testing</h3>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-input-cursor-text"></i> Form Input Data Numerik</h6>
            </div>
            <div class="card-body">
                <form method="post">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">IPK Mahasiswa</label>
                        <input type="number" step="0.01" name="ipk" class="form-control" placeholder="Contoh: 3.50" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Jumlah SKS</label>
                        <input type="number" name="sks" class="form-control" placeholder="Contoh: 140" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Kehadiran (Pertemuan/Persen)</label>
                        <input type="number" name="kehadiran" class="form-control" placeholder="Contoh: 90" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Rata-rata Nilai MK</label>
                        <input type="number" name="nilai_mk" class="form-control" placeholder="Contoh: 85" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Status Pekerjaan</label>
                        <select name="kerja" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Status --</option>
                            <option value="Ya">Ya (Bekerja)</option>
                            <option value="Tidak">Tidak Bekerja</option>
                        </select>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" type="submit" name="simpan">
                            <i class="bi bi-save"></i> Simpan Data
                        </button>
                        <a href="testing.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="alert alert-warning shadow-sm">
            <h5><i class="bi bi-exclamation-triangle"></i> Perhatian</h5>
            <p class="small mb-0">
                Sistem ini menggunakan metode <strong>Naive Bayes Numerik</strong>. 
                Pastikan Anda memasukkan <strong>Angka</strong> (bukan kategori Tinggi/Rendah) agar perhitungan akurat sesuai dengan Data Training yang sudah ada.
            </p>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>