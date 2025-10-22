<?php
// File: index.php
session_start();
include_once 'lib/koneksi.php'; 

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$user_name = $is_logged_in ? htmlspecialchars($_SESSION['user_nama']) : 'Tamu';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Selamat Datang di RTCF - Bintang 50</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    
    <style>
        body { background-color: #121212; color: #E0E0E0; }
        .navbar-luxury { background-color: #1E1E1E; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4); }
        .text-gold { color: #FFD700 !important; }
        .hero-section { 
            background: url('asset/img/hero_bg.jpg') no-repeat center center; /* Ganti dengan gambar BG Anda */
            background-size: cover; 
            height: 80vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            text-align: center;
            position: relative;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6); /* Overlay gelap */
        }
        .card-info { background-color: #1E1E1E; border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-luxury sticky-top">
    <div class="container">
        <a class="navbar-brand text-gold fw-bold" href="index.php">RTCF <span style="font-size: 0.8em;">Bintang 50</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-gold" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-gold" href="menu.php">Menu</a> </li>
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <a class="nav-link text-gold" href="reservasi_form.php"><i class="fas fa-calendar-check"></i> Reservasi</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-warning" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?php echo $user_name; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown" style="background-color: #2D2D2D;">
                            <li><a class="dropdown-item" href="profile.php">Profil & Reservasi Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-outline-warning me-2" href="login-user.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-warning text-dark fw-bold" href="register.php">Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<header class="hero-section mb-5">
    <div class="hero-overlay"></div>
    <div class="container" style="z-index: 1;">
        <h1 class="display-2 fw-bold text-white">Pengalaman Kuliner Bintang 50</h1>
        <p class="lead text-gold mb-4">Pesan meja Anda sekarang dan nikmati keindahan cita rasa yang mewah.</p>
        <a href="<?php echo $is_logged_in ? 'reservasi_form.php' : 'register.php'; ?>" class="btn btn-warning btn-lg fw-bold px-5">
            <i class="fas fa-calendar-alt me-2"></i> PESAN MEJA SEKARANG
        </a>
    </div>
</header>

<div class="container mb-5">
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card card-info p-4 h-100">
                <i class="fas fa-calendar-day fa-3x text-gold mb-3"></i>
                <h5 class="text-white">Reservasi Instan</h5>
                <p class="small" style="color: #AAAAAA;">Proses pemesanan meja yang cepat dan mudah melalui platform online kami.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card card-info p-4 h-100">
                <i class="fas fa-utensils fa-3x text-gold mb-3"></i>
                <h5 class="text-white">Menu Eksklusif</h5>
                <p class="small" style="color: #AAAAAA;">Pilihan hidangan mewah yang dibuat oleh Chef internasional berpengalaman.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card card-info p-4 h-100">
                <i class="fas fa-map-marker-alt fa-3x text-gold mb-3"></i>
                <h5 class="text-white">Lokasi Terbaik</h5>
                <p class="small" style="color: #AAAAAA;">Pilih meja indoor, outdoor, atau private room dengan pemandangan kota.</p>
            </div>
        </div>
    </div>
</div>

<footer class="py-4 mt-auto" style="background-color: #1E1E1E;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-center small">
            <div style="color: #AAAAAA;">&copy; Sistem Reservasi RTCF Bintang 50 <?php echo date('Y'); ?></div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>