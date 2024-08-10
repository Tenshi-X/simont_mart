<?php
include('../components/header.php');
include('../components/sidebar.php');
include('../components/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pegawai = $_POST['id_pegawai'];
    $jumlah_hadir = $_POST['jumlah_hadir'];
    $tgl_gaji = $_POST['tgl_gaji'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $tot_bonus = $_POST['tot_bonus'];
    $tot_potongan = $_POST['tot_potongan'];
    $tot_gaji = $_POST['tot_gaji'];

    $sql = "INSERT INTO Gaji (id_pegawai, jumlah_hadir, tgl_gaji, gaji_pokok, tot_bonus, tot_potongan, tot_gaji) 
            VALUES ('$id_pegawai', '$jumlah_hadir', '$tgl_gaji', '$gaji_pokok', '$tot_bonus', '$tot_potongan', '$tot_gaji')";
    if ($conn->query($sql) === TRUE) {
        echo "Penggajian berhasil dicatat";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$employees = $conn->query("SELECT * FROM Pegawai");
?>

<div class="container mt-5">
    <h2>Pencatatan Penggajian</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="id_pegawai">Pegawai</label>
            <select name="id_pegawai" class="form-control" required>
                <?php while ($row = $employees->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_pegawai']; ?>"><?php echo $row['nama_pegawai']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="jumlah_hadir">Jumlah Hadir</label>
            <input type="number" class="form-control" name="jumlah_hadir" required>
        </div>
        <div class="form-group">
            <label for="tgl_gaji">Tanggal Gaji</label>
            <input type="date" class="form-control" name="tgl_gaji" required>
        </div>
        <div class="form-group">
            <label for="gaji_pokok">Gaji Pokok</label>
            <input type="number" class="form-control" name="gaji_pokok" required>
        </div>
        <div class="form-group">
            <label for="tot_bonus">Total Bonus</label>
            <input type="number" class="form-control" name="tot_bonus" required>
        </div>
        <div class="form-group">
            <label for="tot_potongan">Total Potongan</label>
            <input type="number" class="form-control" name="tot_potongan" required>
        </div>
        <div class="form-group">
            <label for="tot_gaji">Total Gaji</label>
            <input type="number" class="form-control" name="tot_gaji" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php include('../components/footer.php'); ?>
