<?php
include('../components/header.php');
include('../components/koneksi.php');

function getNamaBulan($bulan) {
    $nama_bulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];
    return $nama_bulan[$bulan];
}

$selected_pegawai = isset($_GET['pegawai']) ? $_GET['pegawai'] : '';
$selected_month = isset($_GET['month']) ? $_GET['month'] : '';

$gaji = null;
if ($selected_pegawai && $selected_month) {
    $sql = "SELECT g.id_gaji, 
       g.id_pegawai, 
       p.nama_pegawai, 
       p.no_hp, 
       p.id_jabatan, 
       j.nama_jabatan, 
       g.jumlah_hadir, 
       g.tgl_gaji, 
       g.gaji_pokok, 
       g.gaji_lembur, 
       g.tot_bonus, 
       g.tot_potongan, 
       g.tot_gaji 
        FROM Gaji g 
        JOIN Pegawai p ON g.id_pegawai = p.id_pegawai
        JOIN Jabatan j ON p.id_jabatan = j.id_jabatan
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
    <div class="container mx-auto lg:w-4/5 py-2">
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

    <?php if ($gaji) { 
            $gaji_kotor = $gaji['gaji_pokok'] + $gaji['gaji_lembur'] + $gaji['tot_bonus'];
        ?>
            <div class="bg-white max-w-4xl p-4 rounded-lg shadow-lg">
                <h1 class="text-center font-bold text-xl">SIMONT MART</h1>
                <h2 class="text-center text-lg">Slip Gaji Pegawai</h2>

                <div class="flex justify-between mt-4">
                    <div>
                        <p>Periode: <?php echo  htmlspecialchars(getNamaBulan($selected_month)); ?></p>
                        <p>Nama: <?php echo htmlspecialchars($gaji['nama_pegawai']); ?></p>
                        <p>Jabatan: <?php echo htmlspecialchars($gaji['nama_jabatan']); ?></p>
                        <p>No HP: <?php echo htmlspecialchars($gaji['no_hp']); ?></p>
                    </div>
                    <div >
                        <form method="POST" action="cetak_slip.php" class="mt-4">
                            <input type="hidden" name="pegawai" value="<?php echo $selected_pegawai; ?>">
                            <input type="hidden" name="month" value="<?php echo $selected_month; ?>">
                            <button type="submit" name="cetak" class="bg-green-500 cetak-pdf-btn text-white px-3 py-1 rounded hover:bg-green-600">Cetak PDF</button>
                        </form>
                    </div>
                </div>

                <table class="w-full mt-4 border-collapse border">
                    <thead>
                        <tr>
                            <th class="border px-2 py-1">NO</th>
                            <th class="border px-2 py-1">Ket</th>
                            <th class="border px-2 py-1">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1 text-center">1</td>
                            <td class="border px-2 py-1">Gaji Pokok</td>
                            <td class="border px-2 py-1"><?php echo formatRupiah($gaji['gaji_pokok']); ?></td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 text-center">2</td>
                            <td class="border px-2 py-1">Bonus</td>
                            <td class="border px-2 py-1"><?php echo formatRupiah($gaji['tot_bonus']); ?></td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 text-center">3</td>
                            <td class="border px-2 py-1">Lembur</td>
                            <td class="border px-2 py-1"><?php echo formatRupiah($gaji['gaji_lembur']); ?></td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td class="border px-2 py-1 text-center">4</td>
                            <td class="border px-2 py-1 font-semibold">Gaji Kotor</td>
                            <td class="border px-2 py-1 font-semibold"><?php echo formatRupiah($gaji_kotor); ?></td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 text-center">5</td>
                            <td class="border px-2 py-1">Potongan</td>
                            <td class="border px-2 py-1"><?php echo formatRupiah($gaji['tot_potongan']); ?></td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td class="border px-2 py-1 text-center">6</td>
                            <td class="border px-2 py-1 font-semibold">Gaji Bersih</td>
                            <td class="border px-2 py-1 font-semibold"><?php echo formatRupiah($gaji['tot_gaji']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4 px-6 flex justify-end">
                    <div class="flex flex-col justify-center items-center h-full">
                        <p>Diterima Oleh:</p>
                        <p class="mt-16 mx-auto"><?php echo htmlspecialchars($gaji['nama_pegawai']); ?></p>
                    </div>
                </div>
            </div>
        <?php } else if ($selected_pegawai && $selected_month) {
            echo '<p class="text-red-500">Data gaji tidak ditemukan untuk pegawai dan bulan yang dipilih.</p>';
        } else {
            echo '<p class="text-gray-500">Silahkan pilih pegawai dan periode.</p>';
        }
        ?>
    </div>
</div>
<?php include('../components/footer.php'); ?>
