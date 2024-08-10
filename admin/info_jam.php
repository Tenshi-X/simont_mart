<?php
include('../components/header.php');
include('../components/sidebar.php');
include('../components/koneksi.php');

// Ambil data jam kerja dari database
$sql = "SELECT * FROM jam";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Informasi Jam Kerja</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Jam</th>
                <th>Jam</th>
                <th>Nama Jam Kerja</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id_jam']; ?></td>
                    <td><?php echo $row['jam']; ?></td>
                    <td><?php echo $row['nama_jam_kerja']; ?></td>
                    <td><?php echo $row['keterangan']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
include('../components/footer.php');
?>

