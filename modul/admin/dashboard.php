<?php
session_start();

// 1. KEAMANAN: Periksa apakah admin sudah login
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_username'])) {
    // Jika belum login, tendang ke halaman login
    // Sesuaikan path ini dengan lokasi file login admin Anda
    header("Location: ../../login-admin.php"); 
    exit;
}

// 2. KONEKSI: Sertakan file koneksi database
// (Naik 2 level, lalu masuk ke folder 'lib')
require_once '../../lib/koneksi.php';

// 3. LOGIKA: Ambil Data Statistik dari Database (finedining_db)

// -- Total Reservasi Pending --
$query_pending = "SELECT COUNT(id) as total_pending FROM reservations WHERE status = 'pending'";
$result_pending = mysqli_query($conn, $query_pending);
$data_pending = mysqli_fetch_assoc($result_pending);
$total_pending = $data_pending['total_pending'];

// -- Total Reservasi Terkonfirmasi --
$query_confirmed = "SELECT COUNT(id) as total_confirmed FROM reservations WHERE status = 'confirmed'";
$result_confirmed = mysqli_query($conn, $query_confirmed);
$data_confirmed = mysqli_fetch_assoc($result_confirmed);
$total_confirmed = $data_confirmed['total_confirmed'];

// -- Total Pelanggan (Users) --
$query_users = "SELECT COUNT(id) as total_users FROM users";
$result_users = mysqli_query($conn, $query_users);
$data_users = mysqli_fetch_assoc($result_users);
$total_users = $data_users['total_users'];

// -- Total Meja (Tables) --
$query_tables = "SELECT COUNT(id) as total_tables FROM tables";
$result_tables = mysqli_query($conn, $query_tables);
$data_tables = mysqli_fetch_assoc($result_tables);
$total_tables = $data_tables['total_tables'];


// 4. TEMPLATE: Sertakan Header
// (Header biasanya berisi <head>, <body>, dan navigasi/sidebar)
include_once '../../template/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Selamat datang, <?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?>!</li>
    </ol>
    
    <div class="row">

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <div>
                            <div class="fs-1 fw-bold"><?php echo $total_pending; ?></div>
                            <div>Reservasi Pending</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="reservasi_kelola.php">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-check-circle fa-3x"></i>
                        </div>
                        <div>
                            <div class="fs-1 fw-bold"><?php echo $total_confirmed; ?></div>
                            <div>Reservasi Terkonfirmasi</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="reservasi_kelola.php">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <div>
                            <div class="fs-1 fw-bold"><?php echo $total_users; ?></div>
                            <div>Total Pelanggan</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="user_kelola.php">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <div>
                            <i class="fas fa-chair fa-3x"></i>
                        </div>
                        <div>
                            <div class="fs-1 fw-bold"><?php echo $total_tables; ?></div>
                            <div>Jumlah Meja</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="meja_kelola.php">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Reservasi Terbaru (Pending)
        </div>
        <div class="card-body">
            <p>Tabel data reservasi terbaru bisa ditampilkan di sini...</p>
        </div>
    </div>

</div>
<?php
// 5. TEMPLATE: Sertakan Footer
// (Footer biasanya berisi script JS dan tag penutup </body> </html>)
include_once '../../template/footer.php';
?>