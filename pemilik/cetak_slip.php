<?php
require '../vendor/autoload.php';
include('../components/koneksi.php');

use Dompdf\Dompdf;

// Ambil input dari form
$selected_pegawai = isset($_POST['pegawai']) ? $_POST['pegawai'] : '';
$selected_month = isset($_POST['month']) ? $_POST['month'] : '';

if (!$selected_pegawai || !$selected_month) {
    echo "<script>alert('Pegawai atau bulan tidak dipilih.'); window.location.href='rincian_pegawai.php';</script>";
    exit;
}

// Mengambil data pegawai, termasuk nama jabatan dan nomor HP
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

if ($pegawai_result->num_rows > 0) {
    $pegawai = $pegawai_result->fetch_assoc();

    // Mengambil data gaji berdasarkan bulan yang dipilih
    $gaji_sql = "SELECT * FROM Gaji WHERE id_pegawai = ? AND MONTH(tgl_gaji) = ?";
    $stmt = $conn->prepare($gaji_sql);
    $stmt->bind_param('ss', $selected_pegawai, $selected_month);
    $stmt->execute();
    $gaji_result = $stmt->get_result();

    if ($gaji_result->num_rows > 0) {
        $gaji = $gaji_result->fetch_assoc();
    } else {
        $gaji = null;
    }
} else {
    echo "<script>alert('Pegawai tidak ditemukan.'); window.location.href='rincian_pegawai.php';</script>";
    exit;
}

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


// Tidak boleh ada output sebelum ini!
$dompdf = new Dompdf();

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


$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

if ($gaji) {
    $dompdf->stream("slip_gaji_" . $pegawai['nama_pegawai'] . "_" . date('d-m-Y', strtotime($gaji['tgl_gaji'])) . ".pdf", array("Attachment" => 1));
} else {
    echo "<script>alert('Gagal mencetak PDF, gaji tidak ditemukan'); window.location.href='rincian_pegawai.php';</script>";
}
?>
