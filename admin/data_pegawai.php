<?php
include('../components/header.php');
include('../components/sidebar.php');
include('../components/koneksi.php');

// Query untuk mendapatkan data pegawai
$sql = "SELECT p.id_pegawai, p.nama_pegawai, j.nama_jabatan, p.alamat, p.tempat_lahir, p.tanggal_lahir, p.tgl_masuk_kerja, p.username, p.no_hp 
        FROM Pegawai p 
        JOIN Jabatan j ON p.id_jabatan = j.id_jabatan";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Data Pegawai</h2>
    <a href="tambah_pegawai.php" class="btn btn-primary mb-3">Tambah Pegawai</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pegawai</th>
                <th>Nama Pegawai</th>
                <th>Jabatan</th>
                <th>Alamat</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Tanggal Masuk Kerja</th>
                <th>Username</th>
                <th>No HP</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id_pegawai']; ?></td>
                        <td><?php echo $row['nama_pegawai']; ?></td>
                        <td><?php echo $row['nama_jabatan']; ?></td>
                        <td><?php echo $row['alamat']; ?></td>
                        <td><?php echo $row['tempat_lahir']; ?></td>
                        <td><?php echo $row['tanggal_lahir']; ?></td>
                        <td><?php echo $row['tgl_masuk_kerja']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['no_hp']; ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="9">Belum ada data pegawai.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../components/footer.php'); ?>
