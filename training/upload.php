<?php
include "../template/header.php";
include "../template/sidebar.php";
?>

<h3 class="mb-3">Upload Data Training (Excel)</h3>

<div class="card shadow-sm col-md-6">
    <div class="card-body">
        <form action="proses_upload.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">File Excel (.xlsx)</label>
                <input type="file" name="file_excel" class="form-control" accept=".xlsx" required>
            </div>

            <button class="btn btn-success" name="upload">
                <i class="bi bi-upload"></i> Upload
            </button>
            <a href="training.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

</div></div>

