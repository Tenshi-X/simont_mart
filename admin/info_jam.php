<?php
include('../components/header.php');
include('../components/koneksi.php');

$sql = "SELECT * FROM jam";
$result = $conn->query($sql);
?>

<div class="flex flex-col lg:flex-row">
    <aside class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </aside>

    <main class="flex-1 p-6">
        <h2 class="text-3xl font-bold mb-4">Informasi Jam Kerja</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 text-sm lg:text-base">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-2 md lg:px-6 text-center py-2 border-b">ID Jam</th>
                        <th class="px-2 lg:px-6 text-center py-2 border-b">Jam</th>
                        <th class="px-2 lg:px-6 text-center py-2 border-b">Nama Jam Kerja</th>
                        <th class="px-2 lg:px-6 text-center py-2 border-b w-1/2 lg:w-auto">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td class="px-2 text-center lg:px-6 py-2 border-b"><?php echo $row['id_jam']; ?></td>
                            <td class="px-2 text-center lg:px-6 py-2 border-b"><?php echo $row['jam']; ?></td>
                            <td class="px-2 text-center lg:px-6 py-2 border-b"><?php echo $row['nama_jam_kerja']; ?></td>
                            <td class="px-2 text-left lg:px-6 py-2 border-b w-1/2 lg:w-auto"><?php echo $row['keterangan']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php
include('../components/footer.php');
?>
