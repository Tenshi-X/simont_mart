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
        <?php include('../components/sidebar.php'); ?>
    </div>
    <div class="container mx-auto lg:w-4/5 p-4">
        <h2 class="text-3xl font-bold mb-4">Data Pegawai</h2>
        <a href="tambah_pegawai.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded mb-4">Tambah Pegawai</a>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-center border-b">ID Pegawai</th>
                        <th class="px-4 py-2 text-center border-b">Nama Pegawai</th>
                        <th class="px-4 py-2 text-center border-b">Jabatan</th>
                        <th class="px-4 py-2 text-center border-b">Alamat</th>
                        <th class="px-4 py-2 text-center border-b">Tempat Lahir</th>
                        <th class="px-4 py-2 text-center border-b">Tanggal Lahir</th>
                        <th class="px-4 py-2 text-center border-b">Tanggal Masuk Kerja</th>
                        <th class="px-4 py-2 text-center border-b">Username</th>
                        <th class="px-4 py-2 text-center border-b">No HP</th>
                        <th class="px-4 py-2 text-center border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr class="text-center">
                                <td class="px-4 py-2 border-b"><?php echo $row['id_pegawai']; ?></td>
                                <td class="px-4 py-2 border-b"><?php echo $row['nama_pegawai']; ?></td>
                                <td class="px-4 py-2 border-b"><?php echo $row['nama_jabatan']; ?></td>
                                <td class="px-4 py-2 border-b"><?php echo $row['alamat']; ?></td>
                                <td class="px-4 py-2 border-b"><?php echo $row['tempat_lahir']; ?></td>
                                <td class="px-4 py-2 border-b"><?php echo $row['tanggal_lahir']; ?></td>
                                <td class="px-4 py-2 border-b"><?php echo $row['tgl_masuk_kerja']; ?></td>
                                <td class="px-4 py-2 border-b"><?php echo $row['username']; ?></td>
                                <td class="px-4 py-2 border-b"><?php echo $row['no_hp']; ?></td>
                                <td class="px-4 py-2 border-b">
                                    <a href="edit_pegawai.php?id=<?php echo $row['id_pegawai']; ?>" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">Edit</a>
                                    <a href="data_pegawai.php?delete_id=<?php echo $row['id_pegawai']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pegawai ini?');" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</a>
                                </td>
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
