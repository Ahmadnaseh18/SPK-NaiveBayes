<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menentukan path dasar
$base_url = (file_exists('dashboard.php')) ? "" : "../";

// Menentukan halaman aktif untuk highlight menu
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Naive Bayes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #0b5ed7;
        }
        body { 
            background-color: #f0f2f5; 
            font-family: 'Poppins', sans-serif;
        }
        html { scroll-behavior: smooth; }
        .wrapper { display: flex; flex-direction: column; min-height: 100vh; }
        .main-content { flex: 1; }
        
        /* --- NAVBAR STYLING --- */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }
        .navbar-brand { 
            font-weight: 700; 
            letter-spacing: 1px; 
            font-size: 1.3rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        /* Menu Links */
        .nav-link { 
            font-weight: 500;
            color: rgba(255,255,255,0.85) !important;
            transition: all 0.3s ease;
            position: relative;
            margin: 0 5px;
        }
        .nav-link:hover { 
            color: #fff !important; 
            transform: translateY(-2px);
        }
        
        /* Garis bawah animasi saat hover */
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #fff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover::after {
            width: 80%;
        }

        /* Menu Aktif */
        .nav-link.active {
            color: #fff !important;
            font-weight: 700;
        }
        .nav-link.active::after {
            width: 80%;
        }

        /* User Profile Badge */
        .user-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 5px 15px;
            transition: all 0.3s;
        }
        .user-badge:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="h-100">
<div class="wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container px-4">
            <a class="navbar-brand" href="<?= $base_url ?>dashboard.php">
                <i class="bi bi-cpu-fill me-2"></i> SPK NAIVE BAYES
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="<?= $base_url ?>dashboard.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($current_page == 'training.php' || $current_page == 'testing.php') ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                            Data Master
                        </a>
                        <ul class="dropdown-menu shadow border-0 mt-2 rounded-3 overflow-hidden">
                            <li><a class="dropdown-item py-2" href="<?= $base_url ?>training/training.php"><i class="bi bi-database me-2 text-primary"></i> Data Training</a></li>
                            <li><a class="dropdown-item py-2" href="<?= $base_url ?>testing/testing.php"><i class="bi bi-clipboard-check me-2 text-success"></i> Data Testing</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'hasil.php') ? 'active' : '' ?>" href="<?= $base_url ?>hasil/hasil.php">
                            Hasil Prediksi
                        </a>
                    </li>

                    <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>
                        <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                            <div class="user-badge d-flex align-items-center text-white gap-3">
                                <div class="text-end lh-1">
                                    <small class="d-block opacity-75" style="font-size: 0.75rem;">Login as</small>
                                    <span class="fw-bold text-uppercase"><?= $_SESSION['username'] ?></span>
                                </div>
                                <div class="vr bg-white opacity-50"></div>
                                <a class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" href="<?= $base_url ?>auth/logout.php" title="Keluar">
                                    <i class="bi bi-power"></i>
                                </a>
                            </div>
                        </li>
                    <?php else : ?>
                        <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                            <a class="btn btn-light text-primary fw-bold btn-sm shadow px-4 rounded-pill" href="<?= $base_url ?>auth/login.php">
                                Login <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
    
    <div class="d-flex main-content">