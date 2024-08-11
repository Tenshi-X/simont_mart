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
    $html .= '
    <p><strong>Tanggal Gaji:</strong> ' . htmlspecialchars(date('d-m-Y', strtotime($gaji['tgl_gaji']))) . '</p>
    <p><strong>Jumlah Hadir:</strong> ' . htmlspecialchars($gaji['jumlah_hadir']) . '</p>
    
    <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="text-align:left;">Keterangan</th>
                <th style="text-align:right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td style="text-align:right;">' . formatRupiah($gaji['gaji_pokok']) . '</td>
            </tr>
            <tr>
                <td>Gaji Lembur</td>
                <td style="text-align:right;">' . formatRupiah($gaji['gaji_lembur']) . '</td>
            </tr>
            <tr>
                <td>Total Bonus</td>
                <td style="text-align:right;">' . formatRupiah($gaji['tot_bonus']) . '</td>
            </tr>
            <tr>
                <td>Total Potongan</td>
                <td style="text-align:right;">' . formatRupiah($gaji['tot_potongan']) . '</td>
            </tr>
        </tbody>
    </table>
    <p><strong>Total Gaji:</strong> ' . formatRupiah($gaji['tot_gaji']) . '</p>
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
