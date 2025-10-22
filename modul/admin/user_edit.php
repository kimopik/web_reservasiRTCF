<?php
// File: modul/admin/user_edit.php
session_start();

include_once '../../lib/koneksi.php'; 

// ==============================================
// PENGAMANAN HALAMAN & AMBIL ID
// ==============================================
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login-admin.php");
    exit();
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($user_id === 0) {
    header("Location: user_kelola.php");
    exit();
}

// ==============================================
// LOGIKA AMBIL DATA USER SAAT INI
// ==============================================
$error = [];
$success = '';
$data_user = null;

// Ambil data user saat ini
$stmt_select = $conn->prepare("SELECT nama, email, telepon FROM users WHERE id = ?");
$stmt_select->bind_param("i", $user_id);
$stmt_select->execute();
$result_select = $stmt_select->get_result();

if ($result_select->num_rows === 1) {
    $data_user = $result_select->fetch_assoc();
    $nama = $data_user['nama'];
    $email = $data_user['email'];
    $telepon = $data_user['telepon'];
} else {
    $_SESSION['status_msg'] = "Pengguna dengan ID {$user_id} tidak ditemukan.";
    $_SESSION['status_type'] = "danger";
    header("Location: user_kelola.php");
    exit();
}
$stmt_select->close();

// ==============================================
// LOGIKA UPDATE USER (Back-end)
// ==============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil dan sanitasi input baru
    $nama_new = trim($_POST['nama']);
    $email_new = trim($_POST['email']);
    $telepon_new = trim($_POST['telepon']);

    // 2. Validasi Input
    if (empty($nama_new) || empty($email_new) || empty($telepon_new) || !filter_var($email_new, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Semua kolom harus diisi dengan benar, termasuk format email.";
    }
    
    // 3. Jika tidak ada error, lakukan UPDATE
    if (empty($error)) {
        // Cek duplikasi email (kecuali email milik user ini)
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?");
        $check_stmt->bind_param("si", $email_new, $user_id);
        $check_stmt->execute();
        $count = $check_stmt->get_result()->fetch_row()[0];
        $check_stmt->close();

        if ($count > 0) {
             $error[] = "Email '{$email_new}' sudah digunakan oleh pengguna lain.";
        } else {
            // Prepared Statement untuk UPDATE
            $stmt_update = $conn->prepare("UPDATE users SET nama = ?, email = ?, telepon = ? WHERE id = ?");
            $stmt_update->bind_param("sssi", $nama_new, $email_new, $telepon_new, $user_id);
            
            if ($stmt_update->execute()) {
                $success = "Data pengguna '{$nama_new}' berhasil diperbarui!";
                // Update variabel form agar tampilan form terbaru
                $nama = $nama_new;
                $email = $email_new;
                $telepon = $telepon_new;
            } else {
                $error[] = "Gagal memperbarui data pengguna: " . $stmt_update->error;
            }
            $stmt_update->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Pengguna #<?php echo $user_id; ?> - RTCF Bintang 50</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    
    <style>
        body { background-color: #121212 !important; color: #E0E0E0; }
        .card-luxury { background-color: #1E1E1E; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); }
        .text-gold { color: #FFD700 !important; }
        .form-control { background-color: #2D2D2D; color: #E0E0E0; border: 1px solid #3A3A3A; }
        .form-control:focus { background-color: #2D2D2D; color: #E0E0E0; border-color: #FFD700; box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25); }
    </style>
</head>
<body class="sb-nav-fixed">

<div class="container-fluid px-4 mt-5">

    <h1 class="mt-4 text-light">Edit Data Pengguna: <span class="text-gold"><?php echo htmlspecialchars($nama); ?></span></h1>
    <ol class="breadcrumb mb-4" style="background-color: transparent;">
        <li class="breadcrumb-item"><a href="dashboard.php" class="text-gold">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="user_kelola.php" class="text-gold">Kelola Pengguna</a></li>
        <li class="breadcrumb-item active" style="color: #AAAAAA;">Edit #<?php echo $user_id; ?></li>
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
                <label for="nama" class="form-label text-gold">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" required 
                       value="<?php echo htmlspecialchars($nama); ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label text-gold">Email</label>
                <input type="email" class="form-control" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="mb-4">
                <label for="telepon" class="form-label text-gold">Telepon</label>
                <input type="text" class="form-control" id="telepon" name="telepon" required 
                       value="<?php echo htmlspecialchars($telepon); ?>">
            </div>

            <button type="submit" class="btn btn-warning w-100 fw-bold">Perbarui Data Pengguna</button>
            <a href="user_kelola.php" class="btn btn-secondary w-100 mt-2">Batal dan Kembali</a>
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