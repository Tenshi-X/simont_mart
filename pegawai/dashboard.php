<?php
include('../components/header.php');
include('../components/koneksi.php');
session_start();

if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'pegawai') {
    header("location: ../index.php");
    exit;
}

$username = $_SESSION['login_user'];
$pegawai_sql = "SELECT * FROM pegawai WHERE username = '$username'";
$pegawai_result = $conn->query($pegawai_sql);

if ($pegawai_result->num_rows > 0) {
    $pegawai = $pegawai_result->fetch_assoc();
    $id_pegawai = $pegawai['id_pegawai'];

    $gaji_sql = "SELECT * FROM Gaji WHERE id_pegawai = '$id_pegawai'";
    $gaji_result = $conn->query($gaji_sql);

    if ($gaji_result->num_rows > 0) {
        $gaji = $gaji_result->fetch_assoc();
    } else {
        echo "Gaji tidak ditemukan";
        exit;
    }
} else {
    echo "Pegawai tidak ditemukan";
    exit;
}
?>

<div class="container mt-5">
    <h2>Slip Gaji</h2>
    <p><strong>Nama Pegawai:</strong> <?php echo $pegawai['nm_pegawai']; ?></p>
    <p><strong>No Pegawai:</strong> <?php echo $pegawai['no_pegawai']; ?></p>
    <p><strong>Jabatan:</strong> <?php echo $pegawai['kd_jabatan']; ?></p>
    <p><strong>Jumlah Hadir:</strong> <?php echo $gaji['jumlah_hadir']; ?></p>
    <p><strong>Tanggal Gaji:</strong> <?php echo $gaji['tgl_gaji']; ?></p>
    <p><strong>Gaji Pokok:</strong> <?php echo $gaji['gaji_pokok']; ?></p>
    <p><strong>Total Bonus:</strong> <?php echo $gaji['tot_bonus']; ?></p>
    <p><strong>Total Potongan:</strong> <?php echo $gaji['tot_potongan']; ?></p>
    <p><strong>Total Gaji:</strong> <?php echo $gaji['tot_gaji']; ?></p>
</div>

<?php include('../components/footer.php'); ?>
