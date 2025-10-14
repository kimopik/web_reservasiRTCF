<?php
$host = "localhost";
$user = "root";
$password = "rpl12345";
$database = "db_reservasi";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

echo "Koneksi berhasil!";
?>
