<?php
include('../components/header.php');
include('../components/koneksi.php');

// Query untuk mendapatkan data gaji beserta nama pegawai
$sql = "SELECT g.id_gaji, g.id_pegawai, p.nama_pegawai, g.jumlah_hadir, g.tgl_gaji, g.gaji_pokok, g.gaji_lembur, g.tot_bonus, g.tot_potongan, g.tot_gaji 
        FROM Gaji g 
        JOIN Pegawai p ON g.id_pegawai = p.id_pegawai";
$result = $conn->query($sql);

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

function potongNama($nama, $maxLength = 20) {
    if (strlen($nama) > $maxLength) {
        return substr($nama, 0, $maxLength) . '...';
    } else {
        return $nama;
    }
}

if (isset($_GET['delete_id'])) {
    $id_gaji = $_GET['delete_id'];
    $sql_delete = "DELETE FROM Gaji WHERE id_gaji = '$id_gaji'";
    if ($conn->query($sql_delete) === TRUE) {
        header("Location: data_gaji.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = $_GET['status'];
    $message = urldecode($_GET['message']);

    if ($status === 'error') {
        $alert_color = 'bg-red-500 text-white';
    } elseif ($status === 'success') {
        $alert_color = 'bg-green-500 text-white';
    } else {
        $alert_color = 'bg-gray-500 text-white';
    }
} else {
    $status = '';
    $message = '';
    $alert_color = '';
}
?>

<div class="flex flex-col lg:flex-row">
    <div class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </div>
    <div class="container mx-auto lg:w-4/5 py-4">
        <?php if (!empty($status) && !empty($message)): ?>
            <div id="alert" class="hidden <?php echo $alert_color; ?> px-4 py-3 w-4/5 rounded absolute text-left top-0 right-0" role="alert">
                <strong class="font-bold"><?php echo htmlspecialchars($message); ?></strong>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const alert = document.getElementById("alert");
                    alert.classList.remove("hidden");
                    alert.classList.add("slide-down");

                    setTimeout(() => {
                        alert.classList.remove("slide-down");
                        alert.classList.add("slide-up");
                    }, 3000); 
                });
            </script>
        <?php endif; ?>
        <h2 class="text-3xl font-semibold mb-4">Data Gaji</h2>

        <div class="overflow-x-auto pr-4">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200 text-sm">
                    <tr>
                        <th class="py-1 text-center py-2 w-20">ID Gaji</th>
                        <th class="py-1 text-center py-2 px-2">Nama Pegawai</th>
                        <th class="py-1 text-center py-2 px-2">Jumlah Hadir</th>
                        <th class="py-1 text-center py-2 px-2">Tanggal Gaji</th>
                        <th class="py-1 text-center py-2 px-2">Gaji Pokok</th>
                        <th class="py-1 text-center py-2 px-2">Gaji Lembur</th>
                        <th class="py-1 text-center py-2 px-2">Total Bonus</th>
                        <th class="py-1 text-center py-2 px-2">Total Potongan</th>
                        <th class="py-1 text-center py-2 px-2">Total Gaji</th>
                        <th class="px-2 py-1 text-center border-b">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="py-2 px-2 text-center"><?php echo $row['id_gaji']; ?></td>
                                <td class="py-2 px-2 text-center"><?php echo htmlspecialchars(potongNama($row['nama_pegawai'])); ?></td>
                                <td class="py-2 px-2 text-center"><?php echo $row['jumlah_hadir']; ?></td>
                                <td class="py-2 px-2 text-center"><?php echo date('d-m-Y', strtotime($row['tgl_gaji'])); ?></td>
                                <td class="py-2 px-2 text-center"><?php echo formatRupiah($row['gaji_pokok']); ?></td>
                                <td class="py-2 px-2 text-center"><?php echo formatRupiah($row['gaji_lembur']); ?></td>
                                <td class="py-2 px-2 text-center"><?php echo formatRupiah($row['tot_bonus']); ?></td>
                                <td class="py-2 px-2 text-center"><?php echo formatRupiah($row['tot_potongan']); ?></td>
                                <td class="py-2 px-2 font-semibold text-center"><?php echo formatRupiah($row['tot_gaji']); ?></td>
                                <td class="px-2 py-1 border-b flex justify-center space-x-2">
                                    <a href="edit_gaji.php?id=<?php echo $row['id_gaji']; ?>" class="bg-blue-400 text-white px-2 py-1 rounded text-xs hover:bg-blue-500">Edit</a>
                                    <a href="data_gaji.php?delete_id=<?php echo $row['id_gaji']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data gaji ini?');" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9" class="py-2 px-4 border-b text-center">Belum ada data gaji.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .slide-down {
        animation: slideDown 0.4s ease-out forwards;
    }
    .slide-up {
        animation: slideUp 0.3s ease-in forwards;
    }
    @keyframes slideDown {
        from { transform: translateY(-100%); }
        to { transform: translateY(0); }
    }
    @keyframes slideUp {
        from { transform: translateY(0); }
        to { transform: translateY(-100%); }
    }
</style>

<?php include('../components/footer.php'); ?>
