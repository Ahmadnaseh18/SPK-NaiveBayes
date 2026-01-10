<?php
include '../config/koneksi.php';

if (isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user'; // Secara default pendaftar adalah user biasa

    $cek_user = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $query = "INSERT INTO user (username, password, role) VALUES ('$username', '$password', '$role')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Pendaftaran Berhasil!'); window.location='login.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - SPK Naive Bayes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #198754 0%, #0d5a35 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .signup-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .btn-success {
            border-radius: 10px;
            padding: 12px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card signup-card p-4">
                <div class="text-center mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-block p-3 mb-2">
                        <i class="bi bi-person-plus fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-success">Buat Akun</h4>
                    <p class="text-muted">Daftar untuk akses terbatas sistem</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger py-2 small text-center"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="Password" required>
                        </div>
                    </div>
                    <button type="submit" name="signup" class="btn btn-success w-100 fw-bold shadow-sm">
                        Daftar Sekarang
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted">Sudah punya akun? <a href="login.php" class="text-success text-decoration-none fw-bold">Masuk Log In</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>