<?php
include('../components/header.php');
include('../components/koneksi.php');
session_start();

// Cek apakah pengguna sudah login dan memiliki peran sebagai pemilik
if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'pemilik') {
    header("location: ../index.php");
    exit;
}

// Query untuk mendapatkan daftar pegawai dengan gaji terbaru
$sql = "SELECT 
            p.nama_pegawai, 
            j.nama_jabatan, 
            g.jumlah_hadir, 
            g.tgl_gaji, 
            g.gaji_pokok, 
            g.gaji_lembur, 
            g.tot_bonus, 
            g.tot_potongan, 
            g.tot_gaji
        FROM pegawai p
        JOIN jabatan j ON p.id_jabatan = j.id_jabatan
        JOIN gaji g ON p.id_pegawai = g.id_pegawai
        WHERE g.tgl_gaji = (
            SELECT MAX(g2.tgl_gaji)
            FROM gaji g2
            WHERE g2.id_pegawai = p.id_pegawai
        )
        ORDER BY p.nama_pegawai ASC";

$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Daftar Gaji Pegawai</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Pegawai</th>
                <th>Jabatan</th>
                <th>Jumlah Hadir</th>
                <th>Tanggal Gaji</th>
                <th>Gaji Pokok</th>
                <th>Gaji Lembur</th>
                <th>Total Bonus</th>
                <th>Total Potongan</th>
                <th>Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['nama_pegawai']; ?></td>
                        <td><?php echo $row['nama_jabatan']; ?></td>
                        <td><?php echo $row['jumlah_hadir']; ?></td>
                        <td><?php echo $row['tgl_gaji']; ?></td>
                        <td><?php echo $row['gaji_pokok']; ?></td>
                        <td><?php echo $row['gaji_lembur']; ?></td>
                        <td><?php echo $row['tot_bonus']; ?></td>
                        <td><?php echo $row['tot_potongan']; ?></td>
                        <td><?php echo $row['tot_gaji']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Tidak ada data pegawai atau gaji ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
include('../components/footer.php');
?>

