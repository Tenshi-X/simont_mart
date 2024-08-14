<?php
require '../vendor/autoload.php';
include('../components/koneksi.php');
session_start();

use Dompdf\Dompdf;

// Cek apakah pengguna sudah login dan memiliki role 'pegawai'
if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'pegawai') {
    header("location: ../index.php");
    exit;
}

$username = $_SESSION['login_user'];

// Mengambil data pegawai, termasuk nama jabatan dan nomor HP
$pegawai_sql = "
    SELECT p.*, j.nama_jabatan, j.gaji_pokok 
    FROM pegawai p 
    JOIN jabatan j ON p.id_jabatan = j.id_jabatan 
    WHERE p.username = ?
";
$stmt = $conn->prepare($pegawai_sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$pegawai_result = $stmt->get_result();

if ($pegawai_result->num_rows > 0) {
    $pegawai = $pegawai_result->fetch_assoc();
    $id_pegawai = $pegawai['id_pegawai'];

    // Mengambil data gaji terbaru
    $gaji_sql = "SELECT * FROM gaji WHERE id_pegawai = ? ORDER BY tgl_gaji DESC LIMIT 1";
    $stmt = $conn->prepare($gaji_sql);
    $stmt->bind_param('i', $id_pegawai);
    $stmt->execute();
    $gaji_result = $stmt->get_result();

    if ($gaji_result->num_rows > 0) {
        $gaji = $gaji_result->fetch_assoc();
    } else {
        $gaji = null;
    }
} else {
    echo "<script>alert('Pegawai tidak ditemukan'); window.location.href='rincian_pegawai.php';</script>";
    exit;
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();

function formatRupiah($angka){
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
function getNamaBulan($bulan) {
    $nama_bulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];
    return $nama_bulan[$bulan];
}
$selected_month = date('m', strtotime($gaji['tgl_gaji']));
// Buat instance Dompdf
$dompdf = new Dompdf();

// Buat HTML untuk PDF

$html = '
<h2>Slip Gaji</h2>
<h3>Profil Pegawai</h3>
<p><strong>Nama Pegawai:</strong> ' . htmlspecialchars($pegawai['nama_pegawai']) . '</p>
<p><strong>Jabatan:</strong> ' . htmlspecialchars($pegawai['nama_jabatan']) . '</p>
<p><strong>No. HP:</strong> ' . htmlspecialchars($pegawai['no_hp']) . '</p>

<h3>Rincian Gaji</h3>
';

if ($gaji) {
    $gaji_kotor = $gaji['gaji_pokok'] + $gaji['gaji_lembur'] + $gaji['tot_bonus'];
    $html = '
<h1 style="text-align: center; font-size: 24px; margin-bottom: 5px;">SIMONT MART</h1>
<h2 style="text-align: center; font-size: 18px; margin-top: 0;">Slip Gaji Pegawai</h2>

<div style="margin-top: 20px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 5%;"><strong>Periode</strong></td>
            <td style="width: 5%;"><strong>:</strong></td>
            <td>' . htmlspecialchars(getNamaBulan($selected_month)) . '</td>
        </tr>
        <tr>
            <td><strong>Nama</strong></td>
            <td ><strong>:</strong></td>
            <td>' . htmlspecialchars($pegawai['nama_pegawai']) . '</td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td ><strong>:</strong></td>
            <td>' . htmlspecialchars($pegawai['nama_jabatan']) . '</td>
        </tr>
        <tr>
            <td><strong>No. HP</strong></td>
            <td ><strong>:</strong></td>
            <td>' . htmlspecialchars($pegawai['no_hp']) . '</td>
        </tr>
    </table>
</div>

<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-top: 20px;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th style="width: 10%; text-align:center;">NO</th>
            <th style="width: 45%; text-align:left;">Ket</th>
            <th style="width: 45%; text-align:right;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center;">1</td>
            <td>Gaji Pokok</td>
            <td style="text-align:right;">' . formatRupiah($gaji['gaji_pokok']) . '</td>
        </tr>
        <tr>
            <td style="text-align:center;">2</td>
            <td>Bonus</td>
            <td style="text-align:right;">' . formatRupiah($gaji['tot_bonus']) . '</td>
        </tr>
        <tr>
            <td style="text-align:center;">3</td>
            <td>Lembur</td>
            <td style="text-align:right;">' . formatRupiah($gaji['gaji_lembur']) . '</td>
        </tr>
        <tr style="background-color: #e6e6e6;">
            <td style="text-align:center;">4</td>
            <td><strong>Gaji Kotor</strong></td>
            <td style="text-align:right;"><strong>' . formatRupiah($gaji_kotor) . '</strong></td>
        </tr>
        <tr>
            <td style="text-align:center;">5</td>
            <td>Potongan</td>
            <td style="text-align:right;">' . formatRupiah($gaji['tot_potongan']) . '</td>
        </tr>
        <tr style="background-color: #e6e6e6;">
            <td style="text-align:center;">6</td>
            <td><strong>Gaji Bersih</strong></td>
            <td style="text-align:right;"><strong>' . formatRupiah($gaji['tot_gaji']) . '</strong></td>
        </tr>
    </tbody>
</table>

<div style="margin-top: 20px; text-align: right;">
    <p>Diterima Oleh:</p>
    <p style="margin-top: 60px;">' . htmlspecialchars($pegawai['nama_pegawai']) . '</p>
</div>
';

} else {
    $html .= '<p>Gaji tidak ditemukan untuk bulan yang dipilih.</p>';
}


// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// Set ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Kirimkan output ke browser sebagai PDF
if ($gaji) {
    $dompdf->stream("slip_gaji_" . $pegawai['nama_pegawai'] . ".pdf", array("Attachment" => 1));
    echo "<script>alert('PDF berhasil dicetak');</script>";
} else {
    echo "<script>alert('Gagal mencetak PDF, gaji tidak ditemukan'); window.location.href='rincian_pegawai.php';</script>";
}
