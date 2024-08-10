<?php
include('../components/header.php');
include('../components/sidebar.php');
include('../components/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_jabatan = $_POST['id_jabatan'];
    $nama_pegawai = $_POST['nama_pegawai'];
    $alamat = $_POST['alamat'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $tgl_masuk_kerja = $_POST['tgl_masuk_kerja'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $no_hp = $_POST['no_hp'];

    $sql = "INSERT INTO Pegawai (id_jabatan, nama_pegawai, alamat, tempat_lahir, tanggal_lahir, tgl_masuk_kerja, username, password, no_hp) 
            VALUES ('$id_jabatan', '$nama_pegawai', '$alamat', '$tempat_lahir', '$tanggal_lahir', '$tgl_masuk_kerja', '$username', '$password', '$no_hp')";

    if ($conn->query($sql) === TRUE) {
        echo "Pegawai baru berhasil ditambahkan";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$jobs = $conn->query("SELECT * FROM Jabatan");
?>

<div class="container mt-5">
    <h2>Tambah Pegawai</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nama_pegawai">Nama Pegawai</label>
            <input type="text" class="form-control" name="nama_pegawai" required>
        </div>
        <div class="form-group">
            <label for="id_jabatan">Jabatan</label>
            <select name="id_jabatan" class="form-control" required>
                <?php while ($row = $jobs->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_jabatan']; ?>"><?php echo $row['nama_jabatan']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="form-group">
            <label for="tgl_masuk_kerja">Tanggal Masuk Kerja</label>
            <input type="date" class="form-control" name="tgl_masuk_kerja" required>
        </div>
        <div class="form-group">
            <label for="tempat_lahir">Tempat Lahir</label>
            <input type="text" class="form-control" name="tempat_lahir" required>
        </div>
        <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" class="form-control" name="tanggal_lahir" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" name="alamat" required>
        </div>
        <div class="form-group">
            <label for="no_hp">No HP</label>
            <input type="text" class="form-control" name="no_hp" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php include('../components/footer.php'); ?>
