<?php
// File: modul/admin/dashboard.php
session_start();

// Include koneksi DB
include_once '../../lib/koneksi.php'; 

// ==============================================
// PENGAMANAN HALAMAN
// ==============================================
// Jika sesi admin tidak ada atau tidak valid, redirect ke halaman login.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect ke halaman login admin
    header("Location: login-admin.php");
    exit();
}
// Ambil username admin dari sesi
$admin_username = isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Administrator';


// ==============================================
// PENGAMBILAN DATA STATISTIK
// ==============================================

function getTotalData($conn, $query) {
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_row();
        return $row[0];
    }
    return 0;
}

// A. Total Reservasi Pending (status = 'pending')
$query_pending = "SELECT COUNT(*) FROM reservations WHERE status = 'pending'";
$total_pending = getTotalData($conn, $query_pending);

// B. Total Reservasi Terkonfirmasi (status = 'confirmed')
$query_confirmed = "SELECT COUNT(*) FROM reservations WHERE status = 'confirmed'";
$total_confirmed = getTotalData($conn, $query_confirmed);

// C. Total Pelanggan (Dari tabel 'users')
$query_users = "SELECT COUNT(*) FROM users"; 
$total_users = getTotalData($conn, $query_users);

// D. Total Meja (Dari tabel 'tables')
$query_tables = "SELECT COUNT(*) FROM tables"; 
$total_tables = getTotalData($conn, $query_tables);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard Admin - RTCF Bintang 50</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    
    <style>
        body { background-color: #121212 !important; color: #E0E0E0; }
        .card-luxury { background-color: #1E1E1E; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; transition: transform 0.3s, box-shadow 0.3s; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); }
        .card-luxury:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.6); }
        .text-gold { color: #FFD700 !important; }
        .card-luxury .fa-3x { color: #B8860B; opacity: 0.7; }
        .card-footer-luxury { background-color: #2D2D2D; border-top: 1px solid rgba(255, 255, 255, 0.05); }
        .card-footer-luxury a { color: #FFD700 !important; }
    </style>
</head>
<body class="sb-nav-fixed">

<div class="container-fluid px-4 mt-5">

    <h1 class="mt-4 text-light">Dashboard Admin</h1>
    <ol class="breadcrumb mb-4" style="background-color: transparent;">
        <li class="breadcrumb-item active" style="color: #AAAAAA;">
            Selamat datang di Konsol Manajemen, <span class="text-gold fw-bold"><?php echo $admin_username; ?>!</span>
        </li>
    </ol>
    
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-luxury text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-clock fa-3x"></i></div>
                        <div class="text-end">
                            <div class="fs-1 fw-bold text-gold"><?php echo $total_pending; ?></div>
                            <div class="text-uppercase small" style="color: #AAAAAA;">Reservasi Pending</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer card-footer-luxury d-flex align-items-center justify-content-between">
                    <a class="small text-gold stretched-link" href="reservasi_kelola.php">Kelola Reservasi</a>
                    <div class="small text-gold"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-luxury text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-check-circle fa-3x"></i></div>
                        <div class="text-end">
                            <div class="fs-1 fw-bold text-gold"><?php echo $total_confirmed; ?></div>
                            <div class="text-uppercase small" style="color: #AAAAAA;">Reservasi Terkonfirmasi</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer card-footer-luxury d-flex align-items-center justify-content-between">
                    <a class="small text-gold stretched-link" href="reservasi_kelola.php">Lihat Riwayat</a>
                    <div class="small text-gold"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-luxury text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-users fa-3x"></i></div>
                        <div class="text-end">
                            <div class="fs-1 fw-bold text-gold"><?php echo $total_users; ?></div>
                            <div class="text-uppercase small" style="color: #AAAAAA;">Total Pelanggan</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer card-footer-luxury d-flex align-items-center justify-content-between">
                    <a class="small text-gold stretched-link" href="user_kelola.php">Manajemen Pengguna</a>
                    <div class="small text-gold"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-luxury text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-chair fa-3x"></i></div>
                        <div class="text-end">
                            <div class="fs-1 fw-bold text-gold"><?php echo $total_tables; ?></div>
                            <div class="text-uppercase small" style="color: #AAAAAA;">Total Meja Tersedia</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer card-footer-luxury d-flex align-items-center justify-content-between">
                    <a class="small text-gold stretched-link" href="meja_kelola.php">Konfigurasi Meja</a>
                    <div class="small text-gold"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-luxury mb-4">
        <div class="card-header text-light" style="background-color: #2D2D2D;">
            <i class="fas fa-table me-1 text-gold"></i>
            <span class="text-gold fw-bold">Reservasi Terbaru</span> (5 Data Pending)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover" style="color: #E0E0E0;">
                    <thead>
                        <tr>
                            <th class="text-gold">ID</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Meja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query JOIN untuk mengambil data reservasi pending terbaru
                        $query_latest = "SELECT r.id, u.nama AS nama_pelanggan, r.tanggal, r.waktu, t.nama_meja
                                         FROM reservations r 
                                         JOIN users u ON r.user_id = u.id
                                         JOIN tables t ON r.table_id = t.id
                                         WHERE r.status = 'pending' 
                                         ORDER BY r.created_at DESC 
                                         LIMIT 5";
                        $result_latest = $conn->query($query_latest);

                        if ($result_latest && $result_latest->num_rows > 0) {
                            while ($data = $result_latest->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-gold'>{$data['id']}</td>";
                                echo "<td>" . htmlspecialchars($data['nama_pelanggan']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['tanggal']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['waktu']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['nama_meja']) . "</td>";
                                echo "<td>";
                                echo "<a href='reservasi_aksi.php?id={$data['id']}&action=confirm' class='btn btn-sm btn-success me-2'>Konfirmasi</a>";
                                echo "<a href='reservasi_aksi.php?id={$data['id']}&action=cancel' class='btn btn-sm btn-danger'>Batal</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center' style='color: #AAAAAA;'>Tidak ada reservasi pending saat ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div> 

<footer class="py-4 mt-auto" style="background-color: #1E1E1E;">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div style="color: #AAAAAA;">&copy; Sistem Reservasi RTCF <?php echo date('Y'); ?></div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>