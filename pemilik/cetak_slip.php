<?php
require '../vendor/autoload.php';
include('../components/koneksi.php');

use Dompdf\Dompdf;

$selected_pegawai = $_POST['pegawai'] ?? '';
$selected_month = $_POST['month'] ?? '';
$id_gaji = $_POST['id_gaji'] ?? '';

if (!$id_gaji || !$selected_pegawai || !$selected_month) {
    echo "<script>alert('Data tidak lengkap. ID Gaji, Pegawai, atau Bulan tidak dipilih.'); window.location.href='rincian_pegawai.php';</script>";
    exit;
}

// Mengambil data pegawai
$pegawai_sql = "
    SELECT p.*, j.nama_jabatan, j.gaji_pokok 
    FROM Pegawai p 
    JOIN Jabatan j ON p.id_jabatan = j.id_jabatan 
    WHERE p.id_pegawai = ?
";
$stmt = $conn->prepare($pegawai_sql);
$stmt->bind_param('s', $selected_pegawai);
$stmt->execute();
$pegawai_result = $stmt->get_result();

if ($pegawai_result->num_rows === 0) {
    echo "<script>alert('Pegawai tidak ditemukan.'); window.location.href='rincian_pegawai.php';</script>";
    exit;
}
$pegawai = $pegawai_result->fetch_assoc();

// Mengambil data gaji berdasarkan bulan yang dipilih
$gaji_sql = "SELECT * FROM Gaji WHERE id_gaji = ? AND MONTH(tgl_gaji) = ?";
$stmt = $conn->prepare($gaji_sql);
$stmt->bind_param('ss', $id_gaji, $selected_month);
$stmt->execute();
$gaji_result = $stmt->get_result();

if ($gaji_result->num_rows === 0) {
    echo "<script>alert('Gaji tidak ditemukan untuk bulan yang dipilih.'); window.location.href='rincian_pegawai.php';</script>";
    exit;
}
$gaji = $gaji_result->fetch_assoc();

// Mengambil data bonus dan potongan
$sql_bonus = "SELECT b.nama_bonus, g.nilai_bonus FROM gaji_bonus g 
              JOIN bonus b ON g.id_bonus = b.id_bonus 
              WHERE g.id_gaji = ?";
$stmt_bonus = $conn->prepare($sql_bonus);
$stmt_bonus->bind_param('s', $id_gaji);
$stmt_bonus->execute();
$result_bonus = $stmt_bonus->get_result();

$sql_potongan = "SELECT p.nama_potongan, g.nilai_potongan FROM gaji_potongan g 
                 JOIN potongan p ON g.id_potongan = p.id_potongan 
                 WHERE g.id_gaji = ?";
$stmt_potongan = $conn->prepare($sql_potongan);
$stmt_potongan->bind_param('s', $id_gaji);
$stmt_potongan->execute();
$result_potongan = $stmt_potongan->get_result();

$stmt->close();
$conn->close();

function formatRupiah($angka) {
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
    return $nama_bulan[$bulan] ?? '';
}

// Generate HTML untuk PDF
$dompdf = new Dompdf();

$html = '
<h1 style="text-align: center; font-size: 24px; margin-bottom: 5px;">SIMONT MART</h1>
<h2 style="text-align: center; font-size: 18px; margin-top: 0;">Slip Gaji Pegawai</h2>

<div style="margin-top: 20px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 20%;"><strong>Periode</strong></td>
            <td style="width: 5%;"><strong>:</strong></td>
            <td>' . htmlspecialchars(getNamaBulan($selected_month)) . '</td>
        </tr>
        <tr>
            <td><strong>Nama</strong></td>
            <td><strong>:</strong></td>
            <td>' . htmlspecialchars($pegawai['nama_pegawai']) . '</td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td><strong>:</strong></td>
            <td>' . htmlspecialchars($pegawai['nama_jabatan']) . '</td>
        </tr>
        <tr>
            <td><strong>No. HP</strong></td>
            <td><strong>:</strong></td>
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

while ($row_bonus = $result_bonus->fetch_assoc()) {
    $html .= '
        <tr>
            <td style="text-align:center; color: #475569"></td>
            <td style="color: #475569">' . htmlspecialchars($row_bonus['nama_bonus']) . '</td>
            <td style="text-align:right; color: #475569">' . formatRupiah($row_bonus['nilai_bonus']) . '</td>
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

while ($row_potongan = $result_potongan->fetch_assoc()) {
    $html .= '
        <tr>
            <td style="text-align:center; color: #475569"> </td>
            <td style="color: #475569">' . htmlspecialchars($row_potongan['nama_potongan']) . '</td>
            <td style="text-align:right; color: #475569">' . formatRupiah($row_potongan['nilai_potongan']) . '</td>
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

?>
