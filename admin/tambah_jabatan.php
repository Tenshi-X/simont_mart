<?php
include('../components/header.php');
include('../components/sidebar.php');
include('../components/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nm_jabatan = $_POST['nm_jabatan'];
    $gaji_pokok = $_POST['gaji_pokok'];

    $sql = "INSERT INTO Jabatan (nama_jabatan, gaji_pokok) VALUES ('$nm_jabatan', '$gaji_pokok')";
    if ($conn->query($sql) === TRUE) {
        echo "Jabatan baru berhasil ditambahkan";
        header("location: admin/data_jabatan.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$jobs = $conn->query("SELECT * FROM Jabatan");
?>

<div class="container mt-5">
    <h2>Kelola Jabatan</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nm_jabatan">Nama Jabatan</label>
            <input type="text" class="form-control" name="nm_jabatan" required>
        </div>
        <div class="form-group">
            <label for="gaji_pokok">Gaji Pokok</label>
            <input type="number" class="form-control" name="gaji_pokok" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php include('../components/footer.php'); ?>
