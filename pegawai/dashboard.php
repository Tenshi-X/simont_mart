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
    <div id="sidebar" class="fixed lg:relative transform lg:transform-none -translate-x-full lg:translate-x-0 flex flex-col w-64 h-screen bg-gray-900 text-white transition-transform duration-300 ease-in-out">
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
        <div class="bg-white  max-w-xl border border-grey-600 border-opacity-75 shadow-md shadow-md rounded-lg p-6">
            <div class="flex justify-between mt-2">
                <h3 class="text-xl font-semibold mb-4">Rincian Gaji</h3>
                <a href="cetak_pdf.php" class="bg-blue-500 h-full text-white px-4 py-2 rounded hover:bg-blue-600 cetak-pdf-btn">Cetak PDF</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <?php if ($gaji): ?>
                    <p><strong>Jumlah Hadir:</strong> <?php echo htmlspecialchars($gaji['jumlah_hadir']); ?></p>
                    <p><strong>Tanggal Gaji:</strong> <?php echo htmlspecialchars($gaji['tgl_gaji']); ?></p>
                    <p><strong>Gaji Pokok:</strong> <?php echo formatRupiah($gaji['gaji_pokok']); ?></p>
                    <p><strong>Total Bonus:</strong> <?php echo formatRupiah($gaji['tot_bonus']); ?></p>
                    <p><strong>Total Potongan:</strong> <?php echo formatRupiah($gaji['tot_potongan']); ?></p>
                    <p><strong>Total Gaji:</strong> <?php echo formatRupiah($gaji['tot_gaji']); ?></p>
                <?php else: ?>
                    <p>Gaji tidak ditemukan.</p>
                <?php endif; ?>
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
