<?php
include('../components/header.php');
include('../components/koneksi.php');

// Query untuk mendapatkan data jabatan
$sql = "SELECT * FROM Jabatan";
$result = $conn->query($sql);

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}
?>

<div class="flex flex-col lg:flex-row">
    <div class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </div>
    <div class="container mx-auto lg:w-4/5 p-4">
        <h2 class="text-3xl font-bold mb-4">Data Jabatan</h2>
        <a href="tambah_jabatan.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-3 inline-block hover:bg-blue-600">Tambah Jabatan</a>

        <div class="overflow-x-auto mr-4">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 text-center w-28 ">ID Jabatan</th>
                        <th class="py-2 text-center px-4">Nama Jabatan</th>
                        <th class="py-2 text-center px-4">Gaji Pokok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="py-2 px-4 text-center"><?php echo $row['id_jabatan']; ?></td>
                                <td class="py-2 px-4 text-center"><?php echo $row['nama_jabatan']; ?></td>
                                <td class="py-2 px-4 text-center"><?php echo formatRupiah($row['gaji_pokok']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3" class="py-2 px-4 border-b text-center">Belum ada data jabatan.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../components/footer.php'); ?>
