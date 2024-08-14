<?php
include('../components/header.php');
include('../components/koneksi.php');

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query untuk mendapatkan data gaji beserta nama pegawai sesuai tanggal yang dipilih
$sql = "SELECT g.id_gaji, g.id_pegawai, p.nama_pegawai, g.jumlah_hadir, g.tgl_gaji, g.gaji_pokok, g.gaji_lembur, g.tot_bonus, g.tot_potongan, g.tot_gaji 
        FROM Gaji g 
        JOIN Pegawai p ON g.id_pegawai = p.id_pegawai";

if (!empty($start_date) || !empty($end_date)) {
    $conditions = [];
    if (!empty($start_date)) {
        $conditions[] = "DATE(g.tgl_gaji) >= ?";
    }
    if (!empty($end_date)) {
        $conditions[] = "DATE(g.tgl_gaji) <= ?";
    }
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

$stmt = $conn->prepare($sql);

$params = [];
$types = '';
if (!empty($start_date)) {
    $types .= 's';
    $params[] = $start_date;
}
if (!empty($end_date)) {
    $types .= 's';
    $params[] = $end_date;
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}


$stmt->execute();
$result = $stmt->get_result();


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
?>

<div class="flex flex-col lg:flex-row">
    <div class="lg:w-1/5">
        <?php include('sidebar.php'); ?>
    </div>
    <div class="container mx-auto lg:w-4/5 py-4">
        <h2 class="text-2xl font-semibold mb-4">Data Gaji</h2>
        <form method="GET" class="mb-4">
            <label for="start_date" class="mr-2">Tanggal Mulai:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>" class="border-gray-300 p-2">
            
            <label for="end_date" class="mr-2 ml-4">Tanggal Akhir:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" class="border-gray-300 p-2">
            
            <button type="submit" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Filter</button>
        </form>

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
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <!-- Menampilkan Data Gaji -->
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
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9" class="py-2 px-4 border-b text-center">Belum ada data gaji untuk bulan yang dipilih.</td>
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
