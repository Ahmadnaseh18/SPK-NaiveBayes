<?php
include "../template/header.php";
include "../template/sidebar.php";
include "../config/koneksi.php";

if(isset($_POST['simpan'])){
    mysqli_query($koneksi,"INSERT INTO training VALUES(
        NULL,
        '$_POST[ipk]',
        '$_POST[sks]',
        '$_POST[kehadiran]',
        '$_POST[nilai_mk]',
        '$_POST[kerja]',
        '$_POST[kelulusan]'
    )");
    header("location:training.php");
}
?>

<h3 class="mb-3">Tambah Data Training</h3>

<div class="card shadow-sm col-md-6">
    <div class="card-body">
        <form method="post">
            <?php
            function select($name,$opts){
                echo "<select name='$name' class='form-select mb-3' required>";
                foreach($opts as $o) echo "<option value='$o'>$o</option>";
                echo "</select>";
            }
            select('ipk',['Tinggi','Sedang','Rendah']);
            select('sks',['Memenuhi','Kurang']);
            select('kehadiran',['Tinggi','Sedang','Rendah']);
            select('nilai_mk',['Baik','Cukup','Kurang']);
            select('kerja',['Ya','Tidak']);
            select('kelulusan',['Lulus','Tidak']);
            ?>
            <button class="btn btn-success" name="simpan">
                <i class="bi bi-save"></i> Simpan
            </button>
            <a href="training.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

</div></div>
