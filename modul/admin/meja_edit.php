<?php
// File: modul/admin/meja_edit.php
session_start();

include_once '../../lib/koneksi.php'; 

// ==============================================
// PENGAMANAN HALAMAN & AMBIL ID
// ==============================================
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login-admin.php");
    exit();
}

$meja_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($meja_id === 0) {
    // Jika ID tidak valid, redirect kembali ke halaman kelola
    header("Location: meja_kelola.php");
    exit();
}

// ==============================================
// LOGIKA AMBIL DATA MEJA SAAT INI
// ==============================================
$error = [];
$success = '';
$data_meja = null;

// Ambil data saat ini
$stmt_select = $conn->prepare("SELECT nama_meja, jumlah_kursi, lokasi FROM tables WHERE id = ?");
$stmt_select->bind_param("i", $meja_id);
$stmt_select->execute();
$result_select = $stmt_select->get_result();

if ($result_select->num_rows === 1) {
    $data_meja = $result_select->fetch_assoc();
    // Inisialisasi variabel form dengan data dari DB
    $nama_meja = $data_meja['nama_meja'];
    $jumlah_kursi = $data_meja['jumlah_kursi'];
    $lokasi = $data_meja['lokasi'];
} else {
    // Jika ID tidak ditemukan di DB
    $_SESSION['status_msg'] = "Meja dengan ID {$meja_id} tidak ditemukan.";
    $_SESSION['status_type'] = "danger";
    header("Location: meja_kelola.php");
    exit();
}
$stmt_select->close();

// ==============================================
// LOGIKA UPDATE MEJA (Back-end)
// ==============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil dan sanitasi input baru
    $nama_meja_new = trim($_POST['nama_meja']);
    $jumlah_kursi_new = intval($_POST['jumlah_kursi']);
    $lokasi_new = trim($_POST['lokasi']);

    // 2. Validasi Input
    if (empty($nama_meja_new) || $jumlah_kursi_new <= 0 || empty($lokasi_new)) {
        $error[] = "Semua kolom harus diisi dengan benar.";
    }
    
    // 3. Jika tidak ada error, lakukan UPDATE
    if (empty($error)) {
        // Prepared Statement untuk UPDATE
        $stmt_update = $conn->prepare("UPDATE tables SET nama_meja = ?, jumlah_kursi = ?, lokasi = ? WHERE id = ?");
        $stmt_update->bind_param("sisi", $nama_meja_new, $jumlah_kursi_new, $lokasi_new, $meja_id);
        
        if ($stmt_update->execute()) {
            $success = "Meja '{$nama_meja_new}' berhasil diperbarui!";
            // Update variabel form agar tampilan form terbaru
            $nama_meja = $nama_meja_new;
            $jumlah_kursi = $jumlah_kursi_new;
            $lokasi = $lokasi_new;
        } else {
            $error[] = "Gagal memperbarui data meja: " . $stmt_update->error;
        }
        $stmt_update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Meja #<?php echo $meja_id; ?> - RTCF Bintang 50</title>
    
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

    <h1 class="mt-4 text-light">Edit Meja: <span class="text-gold"><?php echo htmlspecialchars($nama_meja); ?></span></h1>
    <ol class="breadcrumb mb-4" style="background-color: transparent;">
        <li class="breadcrumb-item"><a href="dashboard.php" class="text-gold">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="meja_kelola.php" class="text-gold">Kelola Meja</a></li>
        <li class="breadcrumb-item active" style="color: #AAAAAA;">Edit #<?php echo $meja_id; ?></li>
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
                <label for="nama_meja" class="form-label text-gold">Nama Meja</label>
                <input type="text" class="form-control" id="nama_meja" name="nama_meja" required 
                       value="<?php echo htmlspecialchars($nama_meja); ?>">
            </div>

            <div class="mb-3">
                <label for="jumlah_kursi" class="form-label text-gold">Jumlah Kursi</label>
                <input type="number" class="form-control" id="jumlah_kursi" name="jumlah_kursi" required min="1"
                       value="<?php echo htmlspecialchars($jumlah_kursi); ?>">
            </div>

            <div class="mb-4">
                <label for="lokasi" class="form-label text-gold">Lokasi Meja</label>
                <select class="form-select" id="lokasi" name="lokasi" required>
                    <option value="Indoor" <?php echo ($lokasi == 'Indoor') ? 'selected' : ''; ?>>Indoor</option>
                    <option value="Outdoor/Teras" <?php echo ($lokasi == 'Outdoor/Teras') ? 'selected' : ''; ?>>Outdoor/Teras</option>
                    <option value="VIP Room" <?php echo ($lokasi == 'VIP Room') ? 'selected' : ''; ?>>VIP Room</option>
                </select>
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-bold">Perbarui Meja</button>
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