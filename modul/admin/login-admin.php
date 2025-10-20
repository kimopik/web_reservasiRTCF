<?php
session_start();
// Pastikan path ke koneksi.php sudah benar sesuai struktur Anda
require '../../lib/koneksi.php'; 

$error = ''; // Variabel untuk menyimpan pesan error

// Cek jika pengguna sudah login, langsung redirect ke dashboard
if (isset($_SESSION['admin_username'])) {
    header("Location: dashboard.php");
    exit();
}

// Logic untuk memproses form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Ambil dan sanitasi input dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // 2. Query untuk mengambil data admin berdasarkan username (menggunakan Prepared Statement)
    // Asumsi: Tabel admin memiliki kolom 'username', 'password' (hash), dan 'nama_lengkap'
    $sql = "SELECT username, password, nama_lengkap FROM admins WHERE username = ?";
    
    // Perhatikan: $koneksi adalah variabel koneksi yang didefinisikan di koneksi.php
    if ($stmt = $koneksi->prepare($sql)) {
        
        // Bind parameter
        $stmt->bind_param("s", $username); 
        
        // Eksekusi statement
        if ($stmt->execute()) {
            
            $result = $stmt->get_result();
            
            // Cek apakah username ditemukan
            if ($result->num_rows == 1) {
                $admin = $result->fetch_assoc();
                
                // 3. Verifikasi Password dengan HASH (Wajib untuk keamanan)
                if (password_verify($password, $admin['password'])) {
                    
                    // 4. Login Berhasil - Set Sesi
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_nama_lengkap'] = $admin['nama_lengkap'];
                    
                    // 5. Redirect ke Dashboard
                    header("Location: dashboard.php");
                    exit(); // Penting: Hentikan script setelah header()
                } else {
                    // Password salah
                    $error = "Username atau password salah."; 
                }
            } else {
                // Username tidak ditemukan
                $error = "Username atau password salah.";
            }
        } else {
            // Error saat eksekusi query
            $error = "Terjadi kesalahan sistem saat login.";
        }
        
        $stmt->close();
    } else {
        // Error saat prepared statement
        $error = "Terjadi kesalahan sistem (Prepared Statement).";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS untuk memposisikan kotak login di tengah */
        body {
            background-color: #f8f9fa; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            width: 100%;
            max-width: 400px; 
            padding: 15px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="card shadow-lg p-4">
            <h3 class="card-title text-center mb-4">Login Admin</h3>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login-admin.php" method="POST">
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>