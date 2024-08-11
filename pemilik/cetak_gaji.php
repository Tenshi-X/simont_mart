<?php
include('../components/header.php');
include('../components/koneksi.php');

// Handle form submission
$selected_pegawai = isset($_GET['pegawai']) ? $_GET['pegawai'] : '';
$selected_month = isset($_GET['month']) ? $_GET['month'] : '';

$gaji = null;
if ($selected_pegawai && $selected_month) {
    $sql = "SELECT g.id_gaji, g.id_pegawai, p.nama_pegawai, g.jumlah_hadir, g.tgl_gaji, g.gaji_pokok, g.gaji_lembur, g.tot_bonus, g.tot_potongan, g.tot_gaji 
            FROM Gaji g 
            JOIN Pegawai p ON g.id_pegawai = p.id_pegawai
            WHERE g.id_pegawai = ? AND MONTH(g.tgl_gaji) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $selected_pegawai, $selected_month);
    $stmt->execute();
    $gaji = $stmt->get_result()->fetch_assoc();
}

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}
?>
<div class="flex flex-col lg:flex-row">
    <div class="lg:w-1/5">
        <?php include('sidebar.php'); ?>
    </div>
    <div class="container mx-auto lg:w-4/5 py-4">
        <h2 class="text-2xl font-semibold mb-4">Cetak Slip Gaji</h2>

        <form method="GET" class="mb-4">
            <label for="pegawai" class="mr-2">Pilih Pegawai:</label>
            <select id="pegawai" name="pegawai" class="border-gray-300 p-2" required>
                <option value="" disabled selected>Pilih Pegawai</option>
                <?php
                $pegawaiQuery = "SELECT id_pegawai, nama_pegawai FROM Pegawai";
                $pegawaiResult = $conn->query($pegawaiQuery);
                while ($pegawaiRow = $pegawaiResult->fetch_assoc()) {
                    echo "<option value='" . $pegawaiRow['id_pegawai'] . "' " . ($selected_pegawai == $pegawaiRow['id_pegawai'] ? 'selected' : '') . ">" . htmlspecialchars($pegawaiRow['nama_pegawai']) . "</option>";
                }
                ?>
            </select>

            <label for="month" class="ml-4 mr-2">Pilih Bulan:</label>
            <select id="month" name="month" class="border-gray-300 p-2" required>
            <option value="" disabled selected>Pilih Bulan</option>
                <option value="01" <?php echo $selected_month == '01' ? 'selected' : ''; ?>>Januari</option>
                <option value="02" <?php echo $selected_month == '02' ? 'selected' : ''; ?>>Februari</option>
                <option value="03" <?php echo $selected_month == '03' ? 'selected' : ''; ?>>Maret</option>
                <option value="04" <?php echo $selected_month == '04' ? 'selected' : ''; ?>>April</option>
                <option value="05" <?php echo $selected_month == '05' ? 'selected' : ''; ?>>Mei</option>
                <option value="06" <?php echo $selected_month == '06' ? 'selected' : ''; ?>>Juni</option>
                <option value="07" <?php echo $selected_month == '07' ? 'selected' : ''; ?>>Juli</option>
                <option value="08" <?php echo $selected_month == '08' ? 'selected' : ''; ?>>Agustus</option>
                <option value="09" <?php echo $selected_month == '09' ? 'selected' : ''; ?>>September</option>
                <option value="10" <?php echo $selected_month == '10' ? 'selected' : ''; ?>>Oktober</option>
                <option value="11" <?php echo $selected_month == '11' ? 'selected' : ''; ?>>November</option>
                <option value="12" <?php echo $selected_month == '12' ? 'selected' : ''; ?>>Desember</option>
            </select>

            <button type="submit" class="ml-4 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Tampilkan</button>
        </form>

        <?php if ($gaji) { ?>
            <div class="bg-white w-1/2 p-4 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold mb-2">Slip Gaji - <?php echo htmlspecialchars($gaji['nama_pegawai']); ?></h3>
                <p>ID Gaji: <?php echo $gaji['id_gaji']; ?></p>
                <p>Jumlah Hadir: <?php echo $gaji['jumlah_hadir']; ?></p>
                <p>Tanggal Gaji: <?php echo date('d-m-Y', strtotime($gaji['tgl_gaji'])); ?></p>
                <p>Gaji Pokok: <?php echo formatRupiah($gaji['gaji_pokok']); ?></p>
                <p>Gaji Lembur: <?php echo formatRupiah($gaji['gaji_lembur']); ?></p>
                <p>Total Bonus: <?php echo formatRupiah($gaji['tot_bonus']); ?></p>
                <p>Total Potongan: <?php echo formatRupiah($gaji['tot_potongan']); ?></p>
                <p>Total Gaji: <?php echo formatRupiah($gaji['tot_gaji']); ?></p>

                <form method="POST" action="cetak_slip.php" class="mt-4">
                    <input type="hidden" name="pegawai" value="<?php echo $selected_pegawai; ?>">
                    <input type="hidden" name="month" value="<?php echo $selected_month; ?>">
                    <button type="submit" name="cetak" class="bg-green-500 cetak-pdf-btn text-white px-3 py-1 rounded hover:bg-green-600">Cetak PDF</button>
                </form>
            </div>
        <?php } else if ($selected_pegawai && $selected_month) { ?>
            <p class="text-red-500">Data gaji tidak ditemukan untuk pegawai dan bulan yang dipilih.</p>
        <?php } ?>
    </div>
</div>
<?php include('../components/footer.php'); ?>
