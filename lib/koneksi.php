<?php
$host = "localhost";
$user = "root";
<<<<<<< HEAD
$pass = "rpl12345";
$db = "finedining_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
=======
$password = "rpl12345";
$database = "db_reservasi";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

echo "Koneksi berhasil!";
>>>>>>> dadd1547c8d8c93103b571905d21c43dcc2fbac0
?>
