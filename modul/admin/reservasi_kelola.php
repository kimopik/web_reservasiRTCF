<?php
// File: modul/admin/reservasi_kelola.php
session_start();

include_once '../../lib/koneksi.php'; 

// ==============================================
// PENGAMANAN HALAMAN
// ==============================================
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login-admin.php");
    exit();
}

// Ambil filter dari URL
$filter_status = isset($_GET['status']) ? strtolower($_GET['status']) : 'all';
$allowed_statuses = ['all', 'pending', 'confirmed', 'cancelled'];

// Pastikan filter valid
if (!in_array($filter_status, $allowed_statuses)) {
    $filter_status = 'all';
}

// Tentukan klausa WHERE
$where_clause = '';
if ($filter_status !== 'all') {
    $where_clause = "WHERE r.status = '{$filter_status}'";
}

// ==============================================
// PENGAMBILAN DATA RESERVASI (Back-end)
// ==============================================
// Query JOIN untuk mengambil semua data reservasi
$query_reservasi = "SELECT r.id, u.nama AS nama_pelanggan, t.nama_meja, r.tanggal, r.waktu, r.jumlah_orang, r.status, r.catatan, r.created_at
                     FROM reservations r 
                     JOIN users u ON r.user_id = u.id
                     JOIN tables t ON r.table_id = t.id
                     {$where_clause}
                     ORDER BY r.tanggal DESC, r.waktu DESC";
                     
$result_reservasi = $conn->query($query_reservasi);

// Fungsi untuk mendapatkan badge status
function getStatusBadge($status) {
    switch ($status) {
        case 'confirmed':
            return '<span class="badge bg-success">Terkonfirmasi</span>';
        case 'cancelled':
            return '<span class="badge bg-danger">Dibatalkan</span>';
        case 'pending':
        default:
            return '<span class="badge bg-warning text-dark">Pending</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kelola Reservasi - RTCF Bintang 50</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    
    <style>
        body { background-color: #121212 !important; color: #E0E0E0; }
        .card-luxury { background-color: #1E1E1E; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); }
        .text-gold { color: #FFD700 !important; }
        .table-dark thead th { background-color: #2D2D2D; color: #FFD700; border-color: #3A3A3A; }
    </style>
</head>
<body class="sb-nav-fixed">

<div class="container-fluid px-4 mt-5">

    <h1 class="mt-4 text-light">Manajemen Semua Reservasi</h1>
    <ol class="breadcrumb mb-4" style="background-color: transparent;">
        <li class="breadcrumb-item"><a href="dashboard.php" class="text-gold">Dashboard</a></li>
        <li class="breadcrumb-item active" style="color: #AAAAAA;">Kelola Reservasi</li>
    </ol>

    <?php 
    if (isset($_SESSION['status_msg'])): 
    ?>
        <div class="alert alert-<?php echo $_SESSION['status_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['status_msg']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php 
        unset($_SESSION['status_msg']);
        unset($_SESSION['status_type']);
    endif;
    ?>
    
    <div class="card card-luxury mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #2D2D2D;">
            <div>
                <i class="fas fa-list me-1 text-gold"></i>
                <span class="text-gold fw-bold">Daftar Reservasi</span>
            </div>
            <div class="btn-group">
                <a href="reservasi_kelola.php?status=all" class="btn btn-sm <?php echo $filter_status == 'all' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning'; ?>">Semua</a>
                <a href="reservasi_kelola.php?status=pending" class="btn btn-sm <?php echo $filter_status == 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning'; ?>">Pending</a>
                <a href="reservasi_kelola.php?status=confirmed" class="btn btn-sm <?php echo $filter_status == 'confirmed' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning'; ?>">Terkonfirmasi</a>
                <a href="reservasi_kelola.php?status=cancelled" class="btn btn-sm <?php echo $filter_status == 'cancelled' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning'; ?>">Dibatalkan</a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover" style="color: #E0E0E0;">
                    <thead>
                        <tr>
                            <th class="text-gold">ID</th>
                            <th>Nama</th>
                            <th>Meja</th>
                            <th>Tanggal & Waktu</th>
                            <th>Pax</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_reservasi && $result_reservasi->num_rows > 0) {
                            while ($data = $result_reservasi->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-gold'>{$data['id']}</td>";
                                echo "<td>" . htmlspecialchars($data['nama_pelanggan']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['nama_meja']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['tanggal']) . " @ " . htmlspecialchars($data['waktu']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['jumlah_orang']) . "</td>";
                                echo "<td>" . getStatusBadge($data['status']) . "</td>";
                                echo "<td>";
                                
                                // Aksi hanya muncul jika status masih pending
                                if ($data['status'] === 'pending') {
                                    echo "<a href='reservasi_aksi.php?id={$data['id']}&action=confirm' class='btn btn-sm btn-success me-2'>Konfirmasi</a>";
                                    echo "<a href='reservasi_aksi.php?id={$data['id']}&action=cancel' class='btn btn-sm btn-danger'>Batal</a>";
                                } else {
                                    echo "<span class='text-muted'>Selesai</span>";
                                }
                                
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center' style='color: #AAAAAA;'>Tidak ada data reservasi untuk status '{$filter_status}'.</td></tr>";
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