<?php $is_logged_in = isset($_SESSION['login']); 

?>

<div class="bg-dark text-white shadow" id="sidebar-wrapper">
    <div class="p-3 border-bottom border-secondary">
        <h5 class="mb-0"><i class="bi bi-speedometer2"></i> Menu</h5>
    </div>
    <ul class="nav nav-pills flex-column p-2 gap-1">
        <li class="nav-item">
            <a href="<?= $base_url ?>dashboard.php" class="nav-link text-white">
                <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
        </li>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li class="nav-item">
                <a href="<?= $base_url ?>training/training.php" class="nav-link text-white">
                    <i class="bi bi-database me-2"></i> Data Training
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $base_url ?>naivebayes/proses.php" class="nav-link text-white">
                    <i class="bi bi-cpu me-2"></i> Proses Naive Bayes
                </a>
            </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li class="nav-item">
                <a href="<?= $base_url ?>akurasi/akurasi.php" class="nav-link text-white">
                    <i class="bi bi-bullseye me-2"></i> Uji Akurasi
                </a>
            </li>
        <?php endif; ?>

        <?php if (isset($_SESSION['login'])): ?>
            <li class="nav-item">
                <a href="<?= $base_url ?>testing/testing.php" class="nav-link text-white">
                    <i class="bi bi-clipboard-check me-2"></i> Data Testing
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a href="<?= $base_url ?>hasil/hasil.php" class="nav-link text-white">
                <i class="bi bi-bar-chart me-2"></i> Hasil Prediksi
            </a>
        </li>
    </ul>
</div>
<div class="container-fluid p-4">