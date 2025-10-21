<?php
// File: modul/admin/user_kelola.php
session_start();

// Include koneksi DB
include_once '../../lib/koneksi.php'; 

// ==============================================
// PENGAMANAN HALAMAN
// ==============================================
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login-admin.php");
    exit();
}
$admin_username = isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Administrator';

// ==============================================
// LOGIKA PENGHAPUSAN USER (Back-end)
// ==============================================
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Karena tabel 'reservations' punya Foreign Key ke 'users',
    // kita tidak bisa langsung hapus user yang masih punya reservasi.
    $check_reservasi = $conn->query("SELECT COUNT(*) FROM reservations WHERE user_id = $user_id");
    $count = $check_reservasi->fetch_row()[0];

    if ($count > 0) {
        $_SESSION['status_msg'] = "Gagal menghapus! User ID {$user_id} masih memiliki {$count} reservasi aktif/riwayat. Hapus data reservasi terkait terlebih dahulu.";
        $_SESSION['status_type'] = "danger";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['status_msg'] = "User ID {$user_id} berhasil dihapus.";
            $_SESSION['status_type'] = "success";
        } else {
            $_SESSION['status_msg'] = "Gagal menghapus user: " . $conn->error;
            $_SESSION['status_type'] = "danger";
        }
        $stmt->close();
    }
    header("Location: user_kelola.php");
    exit();
}

// ==============================================
// PENGAMBILAN DATA USERS
// ==============================================
$query_users = "SELECT id, nama, email, telepon, created_at FROM users ORDER BY created_at DESC";
$result_users = $conn->query($query_users);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kelola Pelanggan - RTCF Bintang 50</title>
    
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

    <h1 class="mt-4 text-light">Manajemen Data Pelanggan</h1>
    <ol class="breadcrumb mb-4" style="background-color: transparent;">
        <li class="breadcrumb-item active" style="color: #AAAAAA;">
            Konsol Administrasi / Pelanggan
        </li>
    </ol>

    <?php 
    // Tampilkan notifikasi status dari aksi sebelumnya
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
        <div class="card-header" style="background-color: #2D2D2D;">
            <i class="fas fa-users me-1 text-gold"></i>
            <span class="text-gold fw-bold">Data Seluruh Pelanggan</span>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover" style="color: #E0E0E0;">
                    <thead>
                        <tr>
                            <th class="text-gold">ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Terdaftar Sejak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_users && $result_users->num_rows > 0) {
                            while ($data = $result_users->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-gold'>{$data['id']}</td>";
                                echo "<td>" . htmlspecialchars($data['nama']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($data['telepon']) . "</td>";
                                echo "<td>" . date('d M Y H:i', strtotime($data['created_at'])) . "</td>";
                                echo "<td>";
                                echo "<a href='user_edit.php?id={$data['id']}' class='btn btn-sm btn-info me-2'><i class='fas fa-edit'></i> Edit</a>";
                                // Tambahkan konfirmasi penghapusan
                                echo "<a href='user_kelola.php?action=delete&id={$data['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('PERINGATAN! Menghapus user ini juga menghapus semua data reservasi terkait. Lanjutkan?');\"><i class='fas fa-trash'></i> Hapus</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center' style='color: #AAAAAA;'>Belum ada data pelanggan terdaftar.</td></tr>";
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