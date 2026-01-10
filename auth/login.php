<?php
session_start();
include '../config/koneksi.php';

// Jika sudah login, lempar kembali ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: ../dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cek username di database
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Cek password hash
        if (password_verify($password, $data['password'])) {
            // Set Session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role']; // 'admin' atau 'user'
            
            // Redirect Sukses
            echo "<script>alert('Login Berhasil! Selamat Datang.'); window.location='../dashboard.php';</script>";
            exit;
        } else {
            $error = "Password yang Anda masukkan salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SPK Naive Bayes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0d6efd 0%, #0042a5 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .card-header-custom {
            background: #fff;
            padding-top: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="card-header-custom">
            <div class="d-inline-block bg-primary text-white rounded-circle p-3 mb-2">
                <i class="bi bi-person-lock fs-1"></i>
            </div>
            <h4 class="fw-bold mt-2">Login System</h4>
            <p class="text-muted small">Masuk untuk mengakses Dashboard</p>
        </div>
        
        <div class="card-body p-4 pt-2">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div class="small"><?= $error ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                    MASUK SEKARANG <i class="bi bi-arrow-right-short"></i>
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <a href="../dashboard.php" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
                <br>
                <span class="small text-muted">Belum punya akun? <a href="signup.php" class="text-primary fw-bold text-decoration-none">Daftar</a></span>
            </div>
        </div>
    </div>

</body>
</html>