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

$current_page = basename($_SERVER['PHP_SELF']);

function formatRupiah($angka){
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

?>


<div class="flex flex-col lg:flex-row">
    <div class="lg:hidden flex items-center justify-between bg-gray-900 p-4">
        <a href="#" class="text-xl font-bold text-white">Owner Simont Mart</a>
        <button id="hamburger-btn" class="text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>
    <div id="sidebar" class="fixed lg:relative transform lg:transform-none -translate-x-full lg:translate-x-0 flex flex-col w-64 h-screen bg-gray-900 text-white transition-transform duration-300 ease-in-out">
        <div class="hidden lg:flex items-center justify-center h-16 bg-gray-800">
            <a href="#" class="text-xl font-bold text-white">Owner Simont Mart</a>
        </div>
        <nav class="flex-grow">
            <ul class="flex flex-col space-y-2 p-4">
                <li>
                    <a href="rincian_pegawai.php" class="block py-2 px-4 rounded <?php echo $current_page == 'dashboard.php' ? 'bg-gray-700' : ''; ?>">Daftar Gaji Pegawai</a>
                </li>
            </ul>
        </nav>
        <div class="flex items-center justify-center h-16 bg-gray-800">
            <a href="../logout.php" class="block py-2 px-4 rounded hover:bg-gray-700">Logout</a>
        </div>
    </div>

    <div class="container mx-auto lg:w-4/5 p-4">
        <h2 class="text-3xl font-bold mb-6">Daftar Gaji Pegawai</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="text-center">
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Nama Pegawai</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Jabatan</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Jumlah Hadir</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Tanggal Gaji</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Gaji Pokok</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Gaji Lembur</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Total Bonus</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Total Potongan</th>
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider">Total Gaji</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="bg-white text-center border-b">
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['nama_pegawai']); ?></td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm "><?php echo htmlspecialchars($row['nama_jabatan']); ?></td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm "><?php echo htmlspecialchars($row['jumlah_hadir']); ?></td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm "><?php echo htmlspecialchars($row['tgl_gaji']); ?></td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm "><?php echo formatRupiah($row['gaji_pokok']); ?></td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm "><?php echo formatRupiah($row['gaji_lembur']); ?></td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm "><?php echo formatRupiah($row['tot_bonus']); ?></td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm "><?php echo formatRupiah($row['tot_potongan']); ?></td>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo formatRupiah($row['tot_gaji']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">Tidak ada data pegawai atau gaji ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

<?php
include('../components/footer.php');
?>

