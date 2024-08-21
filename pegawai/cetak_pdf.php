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
    $gaji_sql = "SELECT g.id_gaji, g.id_pegawai, p.nama_pegawai, p.no_hp, p.id_jabatan, 
                        j.nama_jabatan, g.jumlah_hadir, g.tgl_gaji, g.gaji_pokok, 
                        g.gaji_lembur, g.tot_bonus, g.tot_potongan, g.tot_gaji 
                 FROM Gaji g 
                 JOIN Pegawai p ON g.id_pegawai = p.id_pegawai
                 JOIN Jabatan j ON p.id_jabatan = j.id_jabatan
                 WHERE g.id_pegawai = ? 
                 ORDER BY g.tgl_gaji DESC 
                 LIMIT 1";
    $stmt = $conn->prepare($gaji_sql);
    $stmt->bind_param('s', $id_pegawai);
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

// Mengambil data bonus
$sql_bonus = "SELECT b.nama_bonus, g.nilai_bonus 
              FROM gaji_bonus g 
              JOIN bonus b ON g.id_bonus = b.id_bonus 
              WHERE g.id_gaji = ?";
$stmt_bonus = $conn->prepare($sql_bonus);
$stmt_bonus->bind_param('s', $gaji['id_gaji']);
$stmt_bonus->execute();
$result_bonus = $stmt_bonus->get_result();

// Mengambil data potongan
$sql_potongan = "SELECT p.nama_potongan, g.nilai_potongan 
                 FROM gaji_potongan g 
                 JOIN potongan p ON g.id_potongan = p.id_potongan 
                 WHERE g.id_gaji = ?";
$stmt_potongan = $conn->prepare($sql_potongan);
$stmt_potongan->bind_param('s', $gaji['id_gaji']);
$stmt_potongan->execute();
$result_potongan = $stmt_potongan->get_result();

$stmt->close();

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
$dompdf = new Dompdf();

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
';

$no = 3;

while ($row_bonus = mysqli_fetch_assoc($result_bonus)) {
    $html .= '
        <tr>
            <td style="text-align:center;"></td>
            <td style="color: #475569">' . htmlspecialchars($row_bonus['nama_bonus']) . '</td>
            <td style="text-align:right;">' . formatRupiah($row_bonus['nilai_bonus']) . '</td>
        </tr>
    ';
}

$html .= '
        <tr>
            <td style="text-align:center;">' . $no++ . '</td>
            <td>Lembur</td>
            <td style="text-align:right;">' . formatRupiah($gaji['gaji_lembur']) . '</td>
        </tr>
        <tr style="background-color: #e6e6e6;">
            <td style="text-align:center;">' . $no++ . '</td>
            <td><strong>Gaji Kotor</strong></td>
            <td style="text-align:right;"><strong>' . formatRupiah($gaji['gaji_pokok'] + $gaji['gaji_lembur'] + $gaji['tot_bonus']) . '</strong></td>
        </tr>
        <tr>
            <td style="text-align:center;">' . $no++ . '</td>
            <td>Potongan</td>
            <td style="text-align:right;">' . formatRupiah($gaji['tot_potongan']) . '</td>
        </tr>
';

while ($row_potongan = mysqli_fetch_assoc($result_potongan)) {
    $html .= '
        <tr>
            <td style="text-align:center;"></td>
            <td style="color: #475569">' . htmlspecialchars($row_potongan['nama_potongan']) . '</td>
            <td style="text-align:right;">' . formatRupiah($row_potongan['nilai_potongan']) . '</td>
        </tr>
    ';
}

$html .= '
        <tr style="background-color: #e6e6e6;">
            <td style="text-align:center;">' . $no++ . '</td>
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

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("slip_gaji_" . $pegawai['nama_pegawai'] . "_" . date('d-m-Y', strtotime($gaji['tgl_gaji'])) . ".pdf", ["Attachment" => 1]);
