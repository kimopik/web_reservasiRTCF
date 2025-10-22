<?php
// File: login-user.php (Di Root Proyek)
session_start();
include_once 'lib/koneksi.php'; // Path ke lib/koneksi.php

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: modul/user/profile.php"); 
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT id, nama, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Login Berhasil!
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nama'] = $user['nama'];
                
                header("Location: modul/user/reservasi_form.php"); // Arahkan ke form reservasi
                exit();
            } else {
                $error = "Email atau password salah.";
            }
        } else {
            $error = "Email atau password salah.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login Pelanggan - RTCF</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #121212; color: #E0E0E0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .card-luxury { background-color: #1E1E1E; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6); width: 100%; max-width: 400px; }
        .text-gold { color: #FFD700 !important; }
        .form-control { background-color: #2D2D2D; color: #E0E0E0; border: 1px solid #3A3A3A; }
        .form-control:focus { border-color: #FFD700; box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25); }
    </style>
</head>
<body>

<div class="container">
    <div class="card card-luxury p-4">
        <h3 class="text-center mb-4 text-light">Login Pelanggan RTCF</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger p-2 mb-3 text-center small"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email Terdaftar" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="mb-4">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-bold">Login</button>
            <p class="text-center small mt-3" style="color: #AAAAAA;">
                Belum punya akun? <a href="register.php" class="text-gold fw-bold">Daftar Sekarang</a>
            </p>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>