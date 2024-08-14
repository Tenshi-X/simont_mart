<?php
include('../components/header.php');
include('../components/koneksi.php');
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'pegawai'
if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'pegawai') {
    header("location: ../index.php");
    exit;
}
$username = $_SESSION['login_user'];

// Mengambil data pegawai, termasuk nama jabatan dan nomor HP, dengan prepared statement
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

    // Mengambil data gaji terbaru berdasarkan tanggal gaji dengan prepared statement
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
    $stmt->close();
    $conn->close();
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

$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="flex flex-col lg:flex-row">
    
    <div class="lg:hidden flex items-center justify-between bg-gray-900 p-4">
        <a href="#" class="text-xl font-bold text-white">Pegawai Simont Mart</a>
        <button id="hamburger-btn" class="text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>
    <div id="sidebar" class="fixed lg:relative transform lg:transform-none -translate-x-full lg:translate-x-0 flex flex-col w-64 h-screen bg-gray-900 text-white transition-transform duration-300 ease-in-out  lg:sticky lg:top-0">
        <div class="hidden lg:flex items-center justify-center h-16 bg-gray-800">
            <a href="#" class="text-xl font-bold text-white">Pegawai Simont Mart</a>
        </div>
        <nav class="flex-grow">
            <ul class="flex flex-col space-y-2 p-4">
                <li>
                    <a href="dashboard.php" class="block py-2 px-4 rounded <?php echo $current_page == 'dashboard.php' ? 'bg-gray-700' : ''; ?>">Rincian Pegawai</a>
                </li>
            </ul>
        </nav>
        <div class="flex items-center justify-center h-16 bg-gray-800">
            <a href="../logout.php" class="block py-2 px-4 rounded hover:bg-gray-700">Logout</a>
        </div>
    </div>

    <div class="container mx-auto lg:w-4/5 p-4">
        <h2 class="text-3xl font-bold mb-6">Slip Gaji</h2>
        <div class="bg-white max-w-xl border border-grey-600 shadow-md   rounded-lg p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4">Profil Pegawai</h3>
            <div class="grid grid-cols-1 gap-2">
                <p><strong>Nama Pegawai:</strong> <?php echo htmlspecialchars($pegawai['nama_pegawai']); ?></p>
                <p><strong>Jabatan:</strong> <?php echo htmlspecialchars($pegawai['nama_jabatan']); ?></p>
                <p><strong>No. HP:</strong> <?php echo htmlspecialchars($pegawai['no_hp']); ?></p>
            </div>
        </div>

        <!-- Segmen Slip Gaji -->   
        <?php if ($gaji) { 
            $gaji_kotor = $gaji['gaji_pokok'] + $gaji['gaji_lembur'] + $gaji['tot_bonus'];
        ?>
            <div class="bg-white max-w-4xl p-4 rounded-lg shadow-lg">
                <h1 class="text-center font-bold text-xl">SIMONT MART</h1>
                <h2 class="text-center text-lg">Slip Gaji Pegawai</h2>
                <div class="flex justify-between mt-4">
                    <div>
                        <p>Periode: <?php echo  htmlspecialchars(getNamaBulan($selected_month)); ?></p>
                        <p>Nama: <?php echo htmlspecialchars($pegawai['nama_pegawai']); ?></p>
                        <p>Jabatan: <?php echo htmlspecialchars($pegawai['nama_jabatan']); ?></p>
                        <p>No HP: <?php echo htmlspecialchars($pegawai['no_hp']); ?></p>
                    </div>
                    <div >
                        <form method="POST" action="cetak_pdf.php" class="mt-4">
                            <input type="hidden" name="pegawai" value="<?php echo $selected_pegawai; ?>">
                            <input type="hidden" name="month" value="<?php echo $selected_month; ?>">
                            <button type="submit" name="cetak" class="bg-green-500 cetak-pdf-btn text-white px-3 py-1 rounded hover:bg-green-600">Cetak PDF</button>
                        </form>
                    </div>
                </div>

                <table class="w-full mt-4 border-collapse border">
                    <thead>
                        <tr>
                            <th class="border px-2 py-1">NO</th>
                            <th class="border px-2 py-1">Ket</th>
                            <th class="border px-2 py-1">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1 text-center">1</td>
                            <td class="border px-2 py-1">Gaji Pokok</td>
                            <td class="border px-2 py-1"><?php echo formatRupiah($gaji['gaji_pokok']); ?></td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 text-center">2</td>
                            <td class="border px-2 py-1">Bonus</td>
                            <td class="border px-2 py-1"><?php echo formatRupiah($gaji['tot_bonus']); ?></td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 text-center">3</td>
                            <td class="border px-2 py-1">Lembur</td>
                            <td class="border px-2 py-1"><?php echo formatRupiah($gaji['gaji_lembur']); ?></td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td class="border px-2 py-1 text-center">4</td>
                            <td class="border px-2 py-1 font-semibold">Gaji Kotor</td>
                            <td class="border px-2 py-1 font-semibold"><?php echo formatRupiah($gaji_kotor); ?></td>
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 text-center">5</td>
                            <td class="border px-2 py-1">Potongan</td>
                            <td class="border px-2 py-1"><?php echo formatRupiah($gaji['tot_potongan']); ?></td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td class="border px-2 py-1 text-center">6</td>
                            <td class="border px-2 py-1 font-semibold">Gaji Bersih</td>
                            <td class="border px-2 py-1 font-semibold"><?php echo formatRupiah($gaji['tot_gaji']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4 px-6 flex justify-end">
                    <div class="flex flex-col justify-center items-center h-full">
                        <p>Diterima Oleh:</p>
                        <p class="mt-16 mx-auto"><?php echo htmlspecialchars($pegawai['nama_pegawai']); ?></p>
                    </div>
                </div>
            </div>
            <?php } else {
                        echo '<p class="text-gray-500">Gaji Periode ini belum ditambahkan</p>';
                    }
                    ?>
            </div>
        </div>
    </div>
</div>
<script>
    const sidebar = document.getElementById('sidebar');
    const hamburgerBtn = document.getElementById('hamburger-btn');

    hamburgerBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
</script>

<?php include('../components/footer.php'); ?>
