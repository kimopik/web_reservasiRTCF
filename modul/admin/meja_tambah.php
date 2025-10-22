<?php
// File: modul/admin/meja_tambah.php
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
// LOGIKA TAMBAH MEJA (Back-end)
// ==============================================
$error = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil dan sanitasi input
    $nama_meja = trim($_POST['nama_meja']);
    $jumlah_kursi = intval($_POST['jumlah_kursi']);
    $lokasi = trim($_POST['lokasi']);

    // 2. Validasi Input
    if (empty($nama_meja)) {
        $error[] = "Nama Meja tidak boleh kosong.";
    }
    if ($jumlah_kursi <= 0) {
        $error[] = "Jumlah Kursi harus angka positif.";
    }
    if (empty($lokasi)) {
        $error[] = "Lokasi Meja tidak boleh kosong.";
    }
    
    // 3. Jika tidak ada error, lakukan INSERT
    if (empty($error)) {
        // Cek apakah nama meja sudah ada (optional)
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM tables WHERE nama_meja = ?");
        $check_stmt->bind_param("s", $nama_meja);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $count = $check_result->fetch_row()[0];
        $check_stmt->close();
        
        if ($count > 0) {
             $error[] = "Nama Meja '{$nama_meja}' sudah ada dalam database.";
        } else {
            // Prepared Statement untuk mencegah SQL Injection
            $stmt = $conn->prepare("INSERT INTO tables (nama_meja, jumlah_kursi, lokasi) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $nama_meja, $jumlah_kursi, $lokasi);
            
            if ($stmt->execute()) {
                $success = "Meja '{$nama_meja}' berhasil ditambahkan!";
                // Setelah berhasil, kosongkan variabel POST agar form bersih
                unset($nama_meja, $jumlah_kursi, $lokasi);
            } else {
                $error[] = "Gagal menyimpan data meja: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Tambah Meja - RTCF Bintang 50</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    
    <style>
        body { background-color: #121212 !important; color: #E0E0E0; }
        .card-luxury { background-color: #1E1E1E; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); }
        .text-gold { color: #FFD700 !important; }
        .form-control, .form-select { background-color: #2D2D2D; color: #E0E0E0; border: 1px solid #3A3A3A; }
        .form-control:focus, .form-select:focus { background-color: #2D2D2D; color: #E0E0E0; border-color: #FFD700; box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25); }
    </style>
</head>
<body class="sb-nav-fixed">

<div class="container-fluid px-4 mt-5">

    <h1 class="mt-4 text-light">Tambah Meja Baru</h1>
    <ol class="breadcrumb mb-4" style="background-color: transparent;">
        <li class="breadcrumb-item"><a href="dashboard.php" class="text-gold">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="meja_kelola.php" class="text-gold">Kelola Meja</a></li>
        <li class="breadcrumb-item active" style="color: #AAAAAA;">Tambah</li>
    </ol>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                <?php foreach ($error as $err) { echo "<li>{$err}</li>"; } ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="card card-luxury mb-4 p-4">
        <form method="POST">
            
            <div class="mb-3">
                <label for="nama_meja" class="form-label text-gold">Nama Meja (Contoh: Meja A1)</label>
                <input type="text" class="form-control" id="nama_meja" name="nama_meja" required 
                       value="<?php echo htmlspecialchars($nama_meja ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="jumlah_kursi" class="form-label text-gold">Jumlah Kursi</label>
                <input type="number" class="form-control" id="jumlah_kursi" name="jumlah_kursi" required min="1"
                       value="<?php echo htmlspecialchars($jumlah_kursi ?? 4); ?>">
            </div>

            <div class="mb-4">
                <label for="lokasi" class="form-label text-gold">Lokasi Meja</label>
                <select class="form-select" id="lokasi" name="lokasi" required>
                    <option value="" disabled selected>Pilih Lokasi</option>
                    <option value="Indoor" <?php echo (isset($lokasi) && $lokasi == 'Indoor') ? 'selected' : ''; ?>>Indoor</option>
                    <option value="Outdoor/Teras" <?php echo (isset($lokasi) && $lokasi == 'Outdoor/Teras') ? 'selected' : ''; ?>>Outdoor/Teras</option>
                    <option value="VIP Room" <?php echo (isset($lokasi) && $lokasi == 'VIP Room') ? 'selected' : ''; ?>>VIP Room</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-bold">Simpan Meja Baru</button>
            <a href="meja_kelola.php" class="btn btn-secondary w-100 mt-2">Batal dan Kembali</a>
        </form>
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