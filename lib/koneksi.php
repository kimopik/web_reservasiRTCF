<?php
$host = "localhost";
$user = "root";
$pass = "rpl12345";
$db = "finedining_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
