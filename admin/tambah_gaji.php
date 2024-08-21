<?php
include('../components/header.php');
include('../components/koneksi.php');

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pegawai = $_POST['id_pegawai'];
    $jumlah_hadir = $_POST['jumlah_hadir'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $jumlah_lembur = $_POST['jumlah_lembur'];
    $kerugian_barang = $_POST['kerugian_barang'];
    $bonus_kinerja = $_POST['bonus_kinerja'];
    $bonus_jabatan = $_POST['bonus_jabatan'];
    $tot_bonus = $bonus_kinerja + $bonus_jabatan;
    $keterlambatan = $_POST['keterlambatan'];
    $nama_jabatan = $_POST['nama_jabatan'];

    // Batasi jumlah hadir maksimal 26 hari
    if ($jumlah_hadir > 26) {
        $jumlah_hadir = 26;
    }

    // Ambil bulan dan tahun dari input
    $bulan_gaji = $_POST['bulan_gaji'];
    $tahun_gaji = $_POST['tahun_gaji'];

    // Hitung tanggal gaji sebagai akhir bulan
    $tgl_gaji_manual = date("Y-m-t", strtotime("$tahun_gaji-$bulan_gaji-01"));

    // Hitung gaji_lembur
    $gaji_lembur = $jumlah_lembur * 50000;

    // Hitung tot_potongan
    if ($nama_jabatan != "kepala toko" && $nama_jabatan != "bendahara" && $kerugian_barang >= 120000) {
        $tot_potongan = (15 * $kerugian_barang) / 100;
    } else {
        $tot_potongan = 0;
    }

    // Hitung tot_gaji
    $tot_gaji = ($gaji_pokok * (100 - $tot_potongan) / 100) + $gaji_lembur + $tot_bonus;

    // Redirect ke halaman kelola_gaji.php untuk konfirmasi
    header("Location: kelola_gaji.php?id_pegawai=$id_pegawai&jumlah_hadir=$jumlah_hadir&tgl_gaji=$tgl_gaji_manual&gaji_pokok=$gaji_pokok&gaji_lembur=$gaji_lembur&tot_bonus=$tot_bonus&tot_potongan=$tot_potongan&tot_gaji=$tot_gaji&keterlambatan=$keterlambatan&bonus_kinerja=$bonus_kinerja&bonus_jabatan=$bonus_jabatan&nama_jabatan=$nama_jabatan");

    exit;
}


// Ambil data pegawai beserta gaji pokok, bonus jabatan, dan nama jabatan dari tabel Jabatan
$employees = $conn->query("
    SELECT Pegawai.id_pegawai, Pegawai.nama_pegawai, Jabatan.gaji_pokok, Jabatan.gaji_bonus, Jabatan.nama_jabatan
    FROM Pegawai 
    JOIN Jabatan ON Pegawai.id_jabatan = Jabatan.id_jabatan
");

$bonus_kinerja = $conn->query("SELECT id_bonus, nama_bonus, jumlah_bonus FROM bonus WHERE id_bonus = 2 OR id_bonus = 5");

?>

<div class="flex flex-col lg:flex-row">
    <aside class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </aside>

    <main class="flex-1 p-6">
        <h2 class="text-3xl font-bold mb-8">Tambah Data Gaji</h2>
        <form action="" method="POST" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="id_pegawai" class="block text-sm font-medium text-gray-700">Nama Pegawai</label>
                    <select name="id_pegawai" id="id_pegawai" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required onchange="updateGajiPokokDanBonusJabatan()">
                        <option value="">Pilih Pegawai</option>
                        <?php while ($row = $employees->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id_pegawai']; ?>" data-gaji="<?php echo $row['gaji_pokok']; ?>" data-bonus-jabatan="<?php echo $row['gaji_bonus']; ?>" data-jabatan="<?php echo $row['nama_jabatan']; ?>">
                                <?php echo $row['nama_pegawai']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="jumlah_hadir" class="block text-sm font-medium text-gray-700">Jumlah Hadir (Maksimal 26 Hari)</label>
                    <input type="number" id="jumlah_hadir" name="jumlah_hadir" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required max="26">
                </div>
                <div>
                    <label for="gaji_pokok_display" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
                    <input type="text" id="gaji_pokok_display" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
                    <input type="hidden" id="gaji_pokok" name="gaji_pokok">
                </div>
                <div>
                    <label for="jumlah_lembur" class="block text-sm font-medium text-gray-700">Jumlah Lembur (Hari)</label>
                    <input type="number" id="jumlah_lembur" name="jumlah_lembur" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="kerugian_barang" class="block text-sm font-medium text-gray-700">Jumlah Kerugian Barang (rupiah dalam sebulan)</label>
                    <input type="number" id="kerugian_barang" name="kerugian_barang" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="keterlambatan" class="block text-sm font-medium text-gray-700">Keterlambatan (dalam jam)</label>
                    <input type="number" id="keterlambatan" name="keterlambatan" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="bonus_kinerja" class="block text-sm font-medium text-gray-700">Bonus Kinerja</label>
                    <select name="bonus_kinerja" id="bonus_kinerja" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Pilih Bonus</option>
                        <?php while ($row = $bonus_kinerja->fetch_assoc()) { ?>
                            <option value="<?php echo $row['jumlah_bonus']; ?>">
                                <?php echo $row['nama_bonus'] . ' (' . formatRupiah($row['jumlah_bonus']) . ')'; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="bonus_jabatan" class="block text-sm font-medium text-gray-700">Bonus Jabatan</label>
                    <input type="text" id="bonus_jabatan_display" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
                    <input type="hidden" id="bonus_jabatan" name="bonus_jabatan">
                </div>
                <div>
                    <label for="bulan_gaji" class="block text-sm font-medium text-gray-700">Bulan Gaji</label>
                    <select name="bulan_gaji" id="bulan_gaji" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <?php 
                        $bulan_arr = array(
                            "01" => "Januari", "02" => "Februari", "03" => "Maret",
                            "04" => "April", "05" => "Mei", "06" => "Juni",
                            "07" => "Juli", "08" => "Agustus", "09" => "September",
                            "10" => "Oktober", "11" => "November", "12" => "Desember"
                        );
                        foreach ($bulan_arr as $num => $name) {
                            echo "<option value='$num'>$name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="tahun_gaji" class="block text-sm font-medium text-gray-700">Tahun Gaji</label>
                    <select name="tahun_gaji" id="tahun_gaji" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <?php 
                        $current_year = date("Y");
                        for ($i = $current_year; $i >= $current_year - 10; $i--) {
                        echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" id="nama_jabatan" name="nama_jabatan">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Tambah Data Gaji</button>
            </div>
        </form>
    </main>
</div>

<script>
function formatRupiah(angka) {
    var number_string = angka.toString(),
    sisa  = number_string.length % 3,
    rupiah  = number_string.substr(0, sisa),
    ribuan  = number_string.substr(sisa).match(/\d{3}/g);
        
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return 'Rp ' + rupiah;
}

function updateGajiPokokDanBonusJabatan() {
    var select = document.getElementById("id_pegawai");
    var gajiPokok = select.options[select.selectedIndex].getAttribute("data-gaji");
    var bonusJabatan = select.options[select.selectedIndex].getAttribute("data-bonus-jabatan");
    var namaJabatan = select.options[select.selectedIndex].getAttribute("data-jabatan");

    document.getElementById("gaji_pokok").value = gajiPokok;
    document.getElementById("gaji_pokok_display").value = formatRupiah(gajiPokok);

    document.getElementById("bonus_jabatan").value = bonusJabatan;
    document.getElementById("bonus_jabatan_display").value = formatRupiah(bonusJabatan);

    document.getElementById("nama_jabatan").value = namaJabatan;
}
</script>

<?php
include('../components/footer.php');
?>
