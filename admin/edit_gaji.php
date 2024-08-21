<?php
include('../components/header.php');
include('../components/koneksi.php');

// Set timezone ke GMT+7
date_default_timezone_set('Asia/Jakarta');
function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}
if (isset($_GET['id'])) {
    $id_gaji = $_GET['id'];

    // Ambil data pegawai berdasarkan ID
    $sql = "SELECT g.id_gaji, g.id_pegawai, p.nama_pegawai, g.jumlah_hadir, g.tgl_gaji, g.gaji_pokok, g.gaji_lembur, g.tot_bonus, g.tot_potongan, g.tot_gaji 
            FROM Gaji g 
            JOIN Pegawai p ON g.id_pegawai = p.id_pegawai
            WHERE g.id_gaji = '$id_gaji'";
    $result = $conn->query($sql);
    $gaji = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_pegawai = $_POST['id_pegawai'];
        $jumlah_hadir = $_POST['jumlah_hadir'];
        $gaji_pokok = $_POST['gaji_pokok'];
        $jumlah_lembur = $_POST['jumlah_lembur'];
        $kerugian_barang = $_POST['kerugian_barang'];
        $bonus_kinerja =  $_POST['bonus_kinerja'];
        $bonus_jabatan = $_POST['bonus_jabatan'];
        $tot_bonus = $bonus_kinerja + $bonus_jabatan;
        $keterlambatan = $_POST['keterlambatan'];
        $tgl_gaji_manual = $_POST['tgl_gaji_manual'];
    
        // Hitung gaji_lembur
        $gaji_lembur = $jumlah_lembur * 50000;
    
        // Hitung tot_potongan
        if ($kerugian_barang >= 120000) {
            $tot_potongan = (15*$kerugian_barang)/100;
        } else {
            $tot_potongan = 0;
        }
    
        // Hitung tot_gaji
        $tot_gaji = $gaji_pokok - $tot_potongan + $gaji_lembur + $tot_bonus;
    
        // Redirect ke halaman konfirmasi_edit_gaji.php untuk konfirmasi
        header("Location: konfirmasi_edit_gaji.php?id_gaji=$id_gaji&id_pegawai=$id_pegawai&jumlah_hadir=$jumlah_hadir&tgl_gaji=$tgl_gaji_manual&gaji_pokok=$gaji_pokok&gaji_lembur=$gaji_lembur&tot_bonus=$tot_bonus&tot_potongan=$tot_potongan&tot_gaji=$tot_gaji&keterlambatan=$keterlambatan&bonus_kinerja=$bonus_kinerja&bonus_jabatan=$bonus_jabatan");
        exit;

        if ($conn->query($sql_update) === TRUE) {
            $status = "Data pegawai berhasil diperbarui";
            $alert_color = "bg-green-100 border-green-400 text-green-700";
            $redirect = true;
        } else {
            $status = "Update gagal";
            $alert_color = "bg-red-100 border-red-400 text-red-700";
        }
    }

    $employees = $conn->query("
        SELECT Pegawai.id_pegawai, Pegawai.nama_pegawai, Jabatan.gaji_pokok 
        FROM Pegawai 
        JOIN Jabatan ON Pegawai.id_jabatan = Jabatan.id_jabatan
    ");

} else {
    header("Location: data_gaji.php");
    exit;
}
$bonus_kinerja = $conn->query("SELECT id_bonus, nama_bonus, jumlah_bonus FROM bonus WHERE id_bonus = 2 OR id_bonus = 5");
$bonus_jabatan = $conn->query("SELECT id_bonus, nama_bonus, jumlah_bonus FROM bonus WHERE id_bonus = 3 OR id_bonus = 4 OR id_bonus = 5");


?>

<div class="flex flex-col lg:flex-row">
    <aside class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </aside>

    <main class="flex-1 p-6">
    <?php if (!empty($status)): ?>
    <div id="alert" class="hidden <?php echo $alert_color; ?> px-4 py-3 w-4/5 rounded absolute text-left top-0 right-0" role="alert">
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
                setTimeout(() => {
                    <?php if ($redirect): ?>
                        window.location.href = "tambah_gaji.php";
                    <?php endif; ?>
                }, 300);
            }, 3000); 
        });
    </script>
    <?php endif; ?>
        <h2 class="text-2xl font-bold mb-6">Ubah Pencatatan Penggajian</h2>
        
        <form action="" method="POST" class="max-w-5xl py-4 bg-white rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="id_pegawai" class="block text-sm font-medium text-gray-700">Nama Pegawai</label>
                    <select name="id_pegawai" id="id_pegawai" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required onchange="updateGajiPokok()">
                        <option value="">Pilih Pegawai</option>
                        <?php while ($row = $employees->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id_pegawai']; ?>" data-gaji="<?php echo $row['gaji_pokok']; ?>" <?php if($gaji['id_pegawai'] == $row['id_pegawai']) echo 'selected'; ?>>
                                <?php echo $row['nama_pegawai']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="jumlah_hadir" class="block text-sm font-medium text-gray-700">Jumlah Hadir</label>
                    <input type="number" id="jumlah_hadir" name="jumlah_hadir" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo $gaji['jumlah_hadir']; ?>" required>
                </div>
                <div>
                    <label for="gaji_pokok_display" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
                    <input type="text" id="gaji_pokok_display" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo 'Rp ' . number_format($gaji['gaji_pokok'], 0, ',', '.'); ?>" readonly>
                    <input type="hidden" id="gaji_pokok" name="gaji_pokok" value="<?php echo $gaji['gaji_pokok']; ?>">
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
                    <select name="bonus_jabatan" id="bonus_jabatan" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Pilih Bonus</option>
                        <?php while ($row = $bonus_jabatan->fetch_assoc()) { ?>
                            <option value="<?php echo $row['jumlah_bonus']; ?>">
                                <?php echo $row['nama_bonus'] . ' (' . formatRupiah($row['jumlah_bonus']) . ')'; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="tgl_gaji_manual" class="block text-sm font-medium text-gray-700">Tanggal Gaji</label>
                    <input type="date" id="tgl_gaji_manual" name="tgl_gaji_manual" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 mt-3 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </form>
    </main>
</div>

<style>
    .slide-down {
        animation: slideDown 0.2s ease-out forwards;
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
function updateGajiPokok() {
    const selectedPegawai = document.querySelector("#id_pegawai");
    const gajiPokok = selectedPegawai.options[selectedPegawai.selectedIndex].dataset.gaji;
    document.querySelector("#gaji_pokok_display").value = formatRupiah(gajiPokok);
    document.querySelector("#gaji_pokok").value = gajiPokok;
}

function formatRupiah(angka) {
    return "Rp " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>

<?php include('../components/footer.php'); ?>
