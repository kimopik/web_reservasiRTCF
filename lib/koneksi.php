<?php
$host = "localhost";
$user = "root";
// Baris '<<<<<<< HEAD' yang error sudah dihapus dari sini
$pass = "rpl12345";
$db = "finedining_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

echo "Koneksi berhasil!";
?>