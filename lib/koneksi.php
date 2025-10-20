<?php
$host = "localhost";
$user = "root"; // Sesuaikan
$pass = "rpl12345";     // Sesuaikan
$db_name = "finedining_db"; // Sesuaikan

// Pastikan variabel koneksi bernama $koneksi
$koneksi = new mysqli($host, $user, $pass, $db_name); 

// Handle error jika koneksi gagal
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
// Tidak perlu ada 'echo "Koneksi berhasil!";' di sini
?>