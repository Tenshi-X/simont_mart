<?php
include('../components/header.php');
include('../components/sidebar.php');
include('../components/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pegawai = $_POST['id_pegawai'];
    $jumlah_hadir = $_POST['jumlah_hadir'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $jumlah_lembur = $_POST['jumlah_lembur'];
    $kerugian_barang = $_POST['kerugian_barang'];
    $tot_bonus = $_POST['tot_bonus'];

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
    header("Location: kelola_gaji.php?id_pegawai=$id_pegawai&jumlah_hadir=$jumlah_hadir&tgl_gaji=".date('Y-m-d')."&gaji_pokok=$gaji_pokok&gaji_lembur=$gaji_lembur&tot_bonus=$tot_bonus&tot_potongan=$tot_potongan&tot_gaji=$tot_gaji");
    exit;
}

// Ambil data pegawai beserta gaji pokok dari tabel Jabatan
$employees = $conn->query("
    SELECT Pegawai.id_pegawai, Pegawai.nama_pegawai, Jabatan.gaji_pokok 
    FROM Pegawai 
    JOIN Jabatan ON Pegawai.id_jabatan = Jabatan.id_jabatan
");

?>

<div class="container mt-5">
    <h2>Tambah Data Gaji</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="id_pegawai">Nama Pegawai</label>
            <select name="id_pegawai" id="id_pegawai" class="form-control" required onchange="updateGajiPokok()">
                <option value="">Pilih Pegawai</option>
                <?php while ($row = $employees->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_pegawai']; ?>" data-gaji="<?php echo $row['gaji_pokok']; ?>">
                        <?php echo $row['nama_pegawai']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="jumlah_hadir">Jumlah Hadir</label>
            <input type="number" class="form-control" name="jumlah_hadir" required>
        </div>
        <div class="form-group">
            <label for="gaji_pokok">Gaji Pokok</label>
            <input type="number" class="form-control" name="gaji_pokok" id="gaji_pokok" readonly>
        </div>
        <div class="form-group">
            <label for="jumlah_lembur">Jumlah Lembur (Hari)</label>
            <input type="number" class="form-control" name="jumlah_lembur" required>
        </div>
        <div class="form-group">
            <label for="kerugian_barang">Jumlah Kerugian Barang (dalam sebulan)</label>
            <input type="number" class="form-control" name="kerugian_barang" required>
        </div>
        <div class="form-group">
            <label for="tot_bonus">Jumlah Bonus</label>
            <input type="number" class="form-control" name="tot_bonus" required>
        </div>
        <button type="submit" class="btn btn-primary">Hitung Gaji</button>
    </form>
</div>

<script>
function updateGajiPokok() {
    var select = document.getElementById("id_pegawai");
    var gajiPokok = select.options[select.selectedIndex].getAttribute("data-gaji");
    document.getElementById("gaji_pokok").value = gajiPokok;
}
</script>

<?php include('../components/footer.php'); ?>
