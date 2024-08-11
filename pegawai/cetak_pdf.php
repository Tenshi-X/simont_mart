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
    echo "Pegawai tidak ditemukan";
    exit;
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();

function formatRupiah($angka){
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

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
    $html .= '
    <p><strong>Jumlah Hadir:</strong> ' . htmlspecialchars($gaji['jumlah_hadir']) . '</p>
    <p><strong>Tanggal Gaji:</strong> ' . htmlspecialchars($gaji['tgl_gaji']) . '</p>
    <p><strong>Gaji Pokok:</strong> ' . formatRupiah($gaji['gaji_pokok']) . '</p>
    <p><strong>Total Bonus:</strong> ' . formatRupiah($gaji['tot_bonus']) . '</p>
    <p><strong>Total Potongan:</strong> ' . formatRupiah($gaji['tot_potongan']) . '</p>
    <p><strong>Total Gaji:</strong> ' . formatRupiah($gaji['tot_gaji']) . '</p>
    ';
} else {
    $html .= '<p>Gaji tidak ditemukan.</p>';
}

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// Set ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Kirimkan output ke browser sebagai PDF
$dompdf->stream("slip_gaji_" . $pegawai['nama_pegawai'] . ".pdf", array("Attachment" => 1));
