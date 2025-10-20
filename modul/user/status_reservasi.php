<?php
 include '../../template/header.php'; 
 include '../../template/navbar.php'; 
include '../../lib/koneksi.php';
$result = mysqli_query($conn, "SELECT * FROM reservasi ORDER BY tanggal DESC");
?>

<div class="container mt-4">
    <h3>Status Reservasi</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['tanggal']; ?></td>
                <td><?= $row['waktu']; ?></td>
                <td><?= $row['jumlah']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../template/footer.php'; ?>
