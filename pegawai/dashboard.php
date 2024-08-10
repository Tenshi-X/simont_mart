<?php
include('../components/header.php');
include('../components/koneksi.php');
session_start();

if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'pegawai') {
    header("location: ../index.php");
    exit;
}

$username = $_SESSION['login_user'];
// Ambil data pegawai, termasuk nama jabatan dan nomor HP
$pegawai_sql = "
    SELECT p.*, j.nama_jabatan, j.gaji_pokok 
    FROM pegawai p 
    JOIN jabatan j ON p.id_jabatan = j.id_jabatan 
    WHERE p.username = '$username'
";
$pegawai_result = $conn->query($pegawai_sql);

if ($pegawai_result->num_rows > 0) {
    $pegawai = $pegawai_result->fetch_assoc();
    $id_pegawai = $pegawai['id_pegawai'];

    // Ambil data gaji terbaru berdasarkan tanggal gaji
    $gaji_sql = "SELECT * FROM gaji WHERE id_pegawai = '$id_pegawai' ORDER BY tgl_gaji DESC LIMIT 1";
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

    <!-- Segmen Profil Pegawai -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Profil Pegawai</h4>
        </div>
        <div class="card-body">
            <p><strong>Nama Pegawai:</strong> <?php echo $pegawai['nama_pegawai']; ?></p>
            <p><strong>Jabatan:</strong> <?php echo $pegawai['nama_jabatan']; ?></p>
            <p><strong>No. HP:</strong> <?php echo $pegawai['no_hp']; ?></p>
        </div>
    </div>

    <!-- Segmen Slip Gaji -->
    <div class="card">
        <div class="card-header">
            <h4>Rincian Gaji</h4>
        </div>
        <div class="card-body">
            <p><strong>Jumlah Hadir:</strong> <?php echo $gaji['jumlah_hadir']; ?></p>
            <p><strong>Tanggal Gaji:</strong> <?php echo $gaji['tgl_gaji']; ?></p>
            <p><strong>Gaji Pokok:</strong> <?php echo $gaji['gaji_pokok']; ?></p>
            <p><strong>Total Bonus:</strong> <?php echo $gaji['tot_bonus']; ?></p>
            <p><strong>Total Potongan:</strong> <?php echo $gaji['tot_potongan']; ?></p>
            <p><strong>Total Gaji:</strong> <?php echo $gaji['tot_gaji']; ?></p>
        </div>
    </div>
</div>

<?php include('../components/footer.php'); ?>
