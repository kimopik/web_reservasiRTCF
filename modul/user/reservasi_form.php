<?php
 include '../../template/header.php'; 
 include '../../template/navbar.php'; 
include '../../lib/koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $jumlah = $_POST['jumlah'];

    $sql = "INSERT INTO reservasi (nama, tanggal, waktu, jumlah) VALUES ('$nama', '$tanggal', '$waktu', '$jumlah')";
    mysqli_query($conn, $sql);

    echo "<script>alert('Reservasi berhasil dibuat!');window.location='status_reservasi.php';</script>";
}
?>

<div class="container mt-4">
    <h3>Form Reservasi</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Waktu</label>
            <input type="time" name="waktu" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jumlah Orang</label>
            <input type="number" name="jumlah" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Kirim Reservasi</button>
    </form>
</div>

<?php include '../template/footer.php'; ?>
