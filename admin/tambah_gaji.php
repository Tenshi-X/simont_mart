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
    $tot_bonus = $_POST['tot_bonus'];
    $keterlambatan = $_POST['keterlambatan'];

    // Hitung gaji_lembur
    $gaji_lembur = $jumlah_lembur * 50000;

    // Hitung tot_potongan
    if ($kerugian_barang >= 120000) {
        $tot_potongan = 15;
    } else {
        $tot_potongan = 0;
    }

    // Hitung tot_gaji
    $tot_gaji = ($gaji_pokok * (100 - $tot_potongan) / 100) + $gaji_lembur + $tot_bonus;

    // Redirect ke halaman kelola_gaji.php untuk konfirmasi
    header("Location: kelola_gaji.php?id_pegawai=$id_pegawai&jumlah_hadir=$jumlah_hadir&tgl_gaji=".date('Y-m-d')."&gaji_pokok=$gaji_pokok&gaji_lembur=$gaji_lembur&tot_bonus=$tot_bonus&tot_potongan=$tot_potongan&tot_gaji=$tot_gaji&keterlambatan=$keterlambatan");
    exit;
}

// Ambil data pegawai beserta gaji pokok dari tabel Jabatan
$employees = $conn->query("
    SELECT Pegawai.id_pegawai, Pegawai.nama_pegawai, Jabatan.gaji_pokok 
    FROM Pegawai 
    JOIN Jabatan ON Pegawai.id_jabatan = Jabatan.id_jabatan
");

$bonuses = $conn->query("SELECT id_bonus, nama_bonus, jumlah_bonus FROM bonus");

?>

<div class="flex flex-col lg:flex-row">
    <aside class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </aside>

    <main class="flex-1 p-6 ">
        <h2 class="text-3xl font-bold mb-8">Tambah Data Gaji</h2>
        <form action="" method="POST" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="id_pegawai" class="block text-sm font-medium text-gray-700">Nama Pegawai</label>
                    <select name="id_pegawai" id="id_pegawai" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required onchange="updateGajiPokok()">
                        <option value="">Pilih Pegawai</option>
                        <?php while ($row = $employees->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id_pegawai']; ?>" data-gaji="<?php echo $row['gaji_pokok']; ?>">
                                <?php echo $row['nama_pegawai']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="jumlah_hadir" class="block text-sm font-medium text-gray-700">Jumlah Hadir</label>
                    <input type="number" id="jumlah_hadir" name="jumlah_hadir" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
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
                    <label for="tot_bonus" class="block text-sm font-medium text-gray-700">Jumlah Bonus</label>
                    <select name="tot_bonus" id="tot_bonus" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Pilih Bonus</option>
                        <?php while ($row = $bonuses->fetch_assoc()) { ?>
                            <option value="<?php echo $row['jumlah_bonus']; ?>">
                                <?php echo $row['nama_bonus'] . ' (' . formatRupiah($row['jumlah_bonus']) . ')'; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label for="keterlambatan" class="block text-sm font-medium text-gray-700">Keterlambatan (dalam jam)</label>
                    <input type="number" id="keterlambatan" name="keterlambatan" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Hitung Gaji
            </button>
        </form>
    </main>
</div>

<script>
function updateGajiPokok() {
    var select = document.getElementById("id_pegawai");
    var gajiPokok = select.options[select.selectedIndex].getAttribute("data-gaji");
    document.getElementById("gaji_pokok").value = gajiPokok;
    document.getElementById("gaji_pokok_display").value = formatRupiah(gajiPokok);
}

function formatRupiah(angka) {
    var number_string = angka.toString().replace(/[^,\d]/g, '');
    var split = number_string.split(',');
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return 'Rp ' + rupiah;
}
</script>

<?php include('../components/footer.php'); ?>
