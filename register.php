<?php
// File: register.php (Di Root Proyek)
session_start();
include_once 'lib/koneksi.php'; // Path ke lib/koneksi.php

$error = [];
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ... (Logika POST sama seperti sebelumnya) ...
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // 2. Validasi Input
    if (empty($nama) || empty($email) || empty($telepon) || empty($password) || empty($konfirmasi_password)) {
        $error[] = "Semua kolom wajib diisi.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Format email tidak valid.";
    }
    if ($password !== $konfirmasi_password) {
        $error[] = "Konfirmasi password tidak cocok.";
    }
    if (strlen($password) < 6) {
        $error[] = "Password minimal 6 karakter.";
    }

    // 3. Cek Duplikasi Email
    if (empty($error)) {
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error[] = "Email ini sudah terdaftar. Silakan <a href='login-user.php' class='text-warning fw-bold'>login</a>.";
        }
        $check_stmt->close();
    }
    
    // 4. Jika Validasi OK, Lakukan Registrasi
    if (empty($error)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (nama, email, telepon, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $email, $telepon, $password_hash);
        
        if ($stmt->execute()) {
            $success_msg = "Pendaftaran berhasil! Silakan login.";
            unset($nama, $email, $telepon); 
        } else {
            $error[] = "Pendaftaran gagal: " . $stmt->error;
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
    <title>Daftar Pelanggan - RTCF</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #121212; color: #E0E0E0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .card-luxury { background-color: #1E1E1E; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6); width: 100%; max-width: 450px; }
        .text-gold { color: #FFD700 !important; }
        .form-control { background-color: #2D2D2D; color: #E0E0E0; border: 1px solid #3A3A3A; }
        .form-control:focus { background-color: #2D2D2D; color: #E0E0E0; border-color: #FFD700; box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25); }
    </style>
</head>
<body>

<div class="container">
    <div class="card card-luxury p-4">
        <h3 class="text-center mb-4 text-light">Daftar Akun RTCF</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger p-2 mb-3"><ul class="mb-0 ps-3 small"><?php foreach ($error as $err) { echo "<li>{$err}</li>"; } ?></ul></div>
        <?php endif; ?>
        <?php if ($success_msg): ?>
            <div class="alert alert-success p-2 mb-3 text-center small"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required value="<?php echo htmlspecialchars($nama ?? ''); ?>">
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="mb-3">
                <input type="tel" class="form-control" name="telepon" placeholder="Nomor Telepon" required value="<?php echo htmlspecialchars($telepon ?? ''); ?>">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password (Min. 6 Karakter)" required>
            </div>
            <div class="mb-4">
                <input type="password" class="form-control" name="konfirmasi_password" placeholder="Konfirmasi Password" required>
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-bold">Daftar Sekarang</button>
            <p class="text-center small mt-3" style="color: #AAAAAA;">
                Sudah punya akun? <a href="login-user.php" class="text-gold fw-bold">Login</a>
            </p>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>