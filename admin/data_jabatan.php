<?php
include('../components/header.php');
include('../components/koneksi.php');

// Query untuk mendapatkan data jabatan
$sql = "SELECT * FROM Jabatan";
$result = $conn->query($sql);
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
function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
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
        <h2 class="text-2xl font-semibold mb-4">Data Jabatan</h2>
        <a href="tambah_jabatan.php" class="bg-blue-500 text-white px-3 py-2 rounded mb-3 inline-block hover:bg-blue-600 text-sm">Tambah Jabatan</a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-200 text-sm">
                    <tr>
                        <th class="py-1 text-center w-24">ID Jabatan</th>
                        <th class="py-1 text-center px-2">Nama Jabatan</th>
                        <th class="py-1 text-center px-2">Gaji Pokok</th>
                        <th class="py-1 text-center px-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if ($result->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="py-2 px-2 text-center"><?php echo $row['id_jabatan']; ?></td>
                                <td class="py-2 px-2 text-center"><?php echo $row['nama_jabatan']; ?></td>
                                <td class="py-2 px-2 text-center"><?php echo formatRupiah($row['gaji_pokok']); ?></td>
                                <td class="py-2 px-2 text-center">
                                    <a href="edit_jabatan.php?id=<?php echo $row['id_jabatan']; ?>" class="bg-blue-400 text-white px-2 py-1 rounded text-xs hover:bg-blue-500">Edit</a>
                                    <a href="hapus_jabatan.php?id=<?php echo $row['id_jabatan']; ?>" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600" onclick="return confirm('Anda yakin ingin menghapus jabatan ini?')">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" class="py-2 px-4 border-b text-center">Belum ada data jabatan.</td>
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
