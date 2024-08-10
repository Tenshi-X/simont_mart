<?php
include('../components/header.php');
include('../components/sidebar.php');
include('../components/koneksi.php');

// Query untuk mendapatkan data jabatan
$sql = "SELECT * FROM Jabatan";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Data Jabatan</h2>
    <a href="tambah_jabatan.php" class="btn btn-primary mb-3">Tambah Jabatan</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Jabatan</th>
                <th>Nama Jabatan</th>
                <th>Gaji Pokok</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id_jabatan']; ?></td>
                        <td><?php echo $row['nama_jabatan']; ?></td>
                        <td><?php echo $row['gaji_pokok']; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="3">Belum ada data jabatan.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../components/footer.php'); ?>
