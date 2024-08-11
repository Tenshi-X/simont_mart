<?php
include('../components/header.php');
include('../components/koneksi.php');

// Set timezone ke GMT+7
date_default_timezone_set('Asia/Jakarta');

// Tangkap data yang dikirim dari tambah_gaji.php
$id_pegawai = isset($_GET['id_pegawai']) ? $_GET['id_pegawai'] : '';
$jumlah_hadir = isset($_GET['jumlah_hadir']) ? $_GET['jumlah_hadir'] : '';
$tgl_gaji = date('Y-m-d H:i:s'); // Otomatis dengan waktu sekarang di GMT+7
$gaji_pokok = isset($_GET['gaji_pokok']) ? $_GET['gaji_pokok'] : '';
$gaji_lembur = isset($_GET['gaji_lembur']) ? $_GET['gaji_lembur'] : '';
$tot_bonus = isset($_GET['tot_bonus']) ? $_GET['tot_bonus'] : '';
$tot_potongan = isset($_GET['tot_potongan']) ? $_GET['tot_potongan'] : '';

// Hitung total potongan tambahan jika kehadiran kurang dari 26 hari
$potongan_kehadiran = 0;
$potongan_kerugian = 0;
$potongan_per_hari = 100000;

if ($jumlah_hadir < 26) {
    $potongan_kehadiran = (26 - $jumlah_hadir) * $potongan_per_hari;
}

// Ambil potongan kerugian barang dari input user
$potongan_kerugian = ($gaji_pokok * ($tot_potongan / 100));

// Perhitungan total potongan
$tot_potongan_final = $potongan_kehadiran + $potongan_kerugian;

// Perhitungan total gaji setelah potongan
$tot_gaji_final = $gaji_pokok - $tot_potongan_final + $gaji_lembur + $tot_bonus;
$status = "";
$alert_color = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pegawai = $_POST['id_pegawai'];
    $jumlah_hadir = $_POST['jumlah_hadir'];
    $tgl_gaji = date('Y-m-d H:i:s');
    $gaji_pokok = $_POST['gaji_pokok'];
    $gaji_lembur = $_POST['gaji_lembur'];
    $tot_bonus = $_POST['tot_bonus'];
    $tot_potongan = $_POST['tot_potongan'];
    $tot_gaji = $_POST['tot_gaji'];

    $sql = "INSERT INTO Gaji (id_pegawai, jumlah_hadir, tgl_gaji, gaji_pokok, gaji_lembur, tot_bonus, tot_potongan, tot_gaji) 
            VALUES ('$id_pegawai', '$jumlah_hadir', '$tgl_gaji', '$gaji_pokok', '$gaji_lembur', '$tot_bonus', '$tot_potongan_final', '$tot_gaji')";
    if ($conn->query($sql) === TRUE) {
        $status = "Pencatatan berhasil";
        $alert_color = "bg-green-100 border-green-400 text-green-700";
    } else {
        $status = "Pencatatan gagal";
        $alert_color = "bg-red-100 border-red-400 text-red-700";
    }
}

$employees = $conn->query("SELECT * FROM Pegawai");
?>


<div class="flex flex-col lg:flex-row">
    <aside class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </aside>

    <main class="flex-1 p-6">
    <?php if (!empty($status)): ?>
    <div id="alert" class="hidden <?php echo $alert_color; ?> px-4 py-3 w-full rounded absolute top-0 left-0" role="alert">
        <strong class="font-bold"><?php echo $status; ?></strong>
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
        <h2 class="text-3xl font-bold mb-6">Pencatatan Penggajian</h2>
        
        <form action="" method="POST" class="max-w-3xl py-4 bg-white rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-1">
                    <label for="id_pegawai" class="block text-sm font-medium text-gray-700">Pegawai</label>
                    <select name="id_pegawai" id="id_pegawai" class=" block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <?php while ($row = $employees->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id_pegawai']; ?>" <?php if($row['id_pegawai'] == $id_pegawai) echo 'selected'; ?>>
                                <?php echo $row['nama_pegawai']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-1">
                    <label for="jumlah_hadir" class="block text-sm font-medium text-gray-700">Jumlah Hadir</label>
                    <input type="number" id="jumlah_hadir" name="jumlah_hadir" class=" block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo $jumlah_hadir; ?>" required>
                </div>
                <div class="mb-1">
                    <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
                    <input type="number" id="gaji_pokok" name="gaji_pokok" class=" block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo $gaji_pokok; ?>" required>
                </div>
                <div class="mb-1">
                    <label for="gaji_lembur" class="block text-sm font-medium text-gray-700">Gaji Lembur</label>
                    <input type="number" id="gaji_lembur" name="gaji_lembur" class=" block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo $gaji_lembur; ?>" required>
                </div>
                <div class="mb-1">
                    <label for="tot_bonus" class="block text-sm font-medium text-gray-700">Total Bonus</label>
                    <input type="number" id="tot_bonus" name="tot_bonus" class=" block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo $tot_bonus; ?>" required>
                </div>

                <div class="mb-1 col-span-2">
                    <label for="tot_potongan" class="block text-sm font-medium text-gray-700">Total Potongan (Rp)</label>
                    <input type="number" id="tot_potongan" name="tot_potongan" class=" block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo $tot_potongan_final; ?>" readonly required>
                    <p class="mt-2 text-sm text-gray-600">
                        Potongan Kehadiran: Rp <?php echo number_format($potongan_kehadiran, 0, ',', '.'); ?> 
                        (<?php echo (26 - $jumlah_hadir); ?> hari tidak hadir x Rp <?php echo number_format($potongan_per_hari, 0, ',', '.'); ?> per hari)
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Potongan Kerugian Barang: Rp <?php echo number_format($potongan_kerugian, 0, ',', '.'); ?> 
                        (<?php echo $tot_potongan; ?>% dari gaji pokok)
                    </p>
                </div>

                <div class="mb-4 col-span-2">
                    <label for="tot_gaji_display" class="block text-sm font-medium text-gray-700">Total Gaji</label>
                    <input type="text" id="tot_gaji_display" class="block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo number_format($tot_gaji_final, 0, ',', '.'); ?>" readonly required>
                    <input type="hidden" id="tot_gaji" name="tot_gaji" value="<?php echo $tot_gaji_final; ?>">
                </div>

            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </form>
    </main>
</div>

<style>
    .slide-down {
        animation: slideDown 0.2    s ease-out forwards;
    }
    .slide-up {
        animation: slideUp 0.5s ease-in forwards;
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
<script>
document.getElementById('tot_gaji_display').addEventListener('input', function(e) {
    var value = e.target.value.replace(/[^,\d]/g, '').toString();
    var number_string = value.replace(/[^,\d]/g, '').toString();
    var split = number_string.split(',');
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    e.target.value = 'Rp ' + rupiah;

    document.getElementById('tot_gaji').value = value.replace(/\./g, '').replace('Rp ', '');
});

document.getElementById('tot_gaji_display').addEventListener('focus', function(e) {
    e.target.value = document.getElementById('tot_gaji').value;
});

document.getElementById('tot_gaji_display').addEventListener('blur', function(e) {
    var value = document.getElementById('tot_gaji').value;
    var number_string = value.replace(/[^,\d]/g, '').toString();
    var split = number_string.split(',');
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    e.target.value = 'Rp ' + rupiah;
});

</script>
<?php include('../components/footer.php'); ?>
