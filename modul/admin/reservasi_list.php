<?php
include '../../lib/koneksi.php';
include '../../template/header.php';
include '../../template/navbar.php';

// Query gabungan antar tabel
$query = "
    SELECT 
        r.id_reservasi,
        u.nama AS nama_user,
        m.nomor_meja,
        m.kapasitas,
        r.tanggal_reservasi,
        r.waktu_reservasi,
        r.jumlah_orang,
        r.status,
        r.catatan
    FROM reservasi r
    JOIN user u ON r.id_user = u.id_user
    JOIN meja m ON r.id_meja = m.id_meja
    ORDER BY r.tanggal_reservasi DESC, r.waktu_reservasi ASC
";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h3 class="mb-4">Daftar Reservasi Pelanggan</h3>

    <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Nomor Meja</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Jumlah Orang</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($result)) { 
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama_user']); ?></td>
                <td><?= htmlspecialchars($row['nomor_meja']); ?> (<?= $row['kapasitas']; ?> org)</td>
                <td><?= $row['tanggal_reservasi']; ?></td>
                <td><?= $row['waktu_reservasi']; ?></td>
                <td><?= $row['jumlah_orang']; ?></td>
                <td>
                    <?php
                        switch ($row['status']) {
                            case 'pending': 
                                echo "<span class='badge bg-warning text-dark'>Pending</span>"; break;
                            case 'diterima': 
                                echo "<span class='badge bg-success'>Diterima</span>"; break;
                            case 'ditolak': 
                                echo "<span class='badge bg-danger'>Ditolak</span>"; break;
                            case 'selesai': 
                                echo "<span class='badge bg-secondary'>Selesai</span>"; break;
                        }
                    ?>
                </td>
                <td><?= $row['catatan'] ?: '-'; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../../template/footer.php'; ?>
