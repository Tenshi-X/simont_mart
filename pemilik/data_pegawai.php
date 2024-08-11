<?php
include('../components/header.php');
include('../components/koneksi.php');

// Fungsi untuk menghapus pegawai
if (isset($_GET['delete_id'])) {
    $id_pegawai = $_GET['delete_id'];
    $sql_delete = "DELETE FROM Pegawai WHERE id_pegawai = '$id_pegawai'";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: data_pegawai.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Query untuk mendapatkan data pegawai
$sql = "SELECT p.id_pegawai, p.nama_pegawai, j.nama_jabatan, p.alamat, p.tempat_lahir, p.tanggal_lahir, p.tgl_masuk_kerja, p.username, p.no_hp 
        FROM Pegawai p 
        JOIN Jabatan j ON p.id_jabatan = j.id_jabatan";
$result = $conn->query($sql);
?>

<div class="flex flex-col lg:flex-row">
        
    <div class="lg:w-1/5">
        <?php include('sidebar.php'); ?>
    </div>
    <div class="container mx-4  lg:w-4/5 py-4">
        <h2 class="text-2xl font-semibold mb-4">Data Pegawai</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200 text-sm">
                    <tr>
                        <th class="px-1 py-1 w-12 text-center border-b">ID</th>
                        <th class="px-2 py-1 text-center border-b">Nama Pegawai</th>
                        <th class="px-2 py-1 text-center border-b">Jabatan</th>
                        <th class="px-2 py-1 text-center border-b">Alamat</th>
                        <th class="px-2 py-1 text-center border-b">Tempat Lahir</th>
                        <th class="px-2 py-1 text-center border-b">Tanggal Lahir</th>
                        <th class="px-2 py-1 text-center border-b">Tanggal Masuk Kerja</th>
                        <th class="px-2 py-1 text-center border-b">Username</th>
                        <th class="px-2 py-1 text-center border-b">No HP</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr class="text-center">
                                <td class="px-1 py-1 border-b"><?php echo $row['id_pegawai']; ?></td>
                                <td class="px-2 py-1 border-b"><?php echo $row['nama_pegawai']; ?></td>
                                <td class="px-2 py-1 border-b"><?php echo $row['nama_jabatan']; ?></td>
                                <td class="px-2 py-1 border-b"><?php echo $row['alamat']; ?></td>
                                <td class="px-2 py-1 border-b"><?php echo $row['tempat_lahir']; ?></td>
                                <td class="px-2 py-1 border-b"><?php echo $row['tanggal_lahir']; ?></td>
                                <td class="px-2 py-1 border-b"><?php echo $row['tgl_masuk_kerja']; ?></td>
                                <td class="px-2 py-1 border-b"><?php echo $row['username']; ?></td>
                                <td class="px-2 py-1 border-b"><?php echo $row['no_hp']; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="10" class="text-center px-4 py-2 border-b">Belum ada data pegawai.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../components/footer.php'); ?>
