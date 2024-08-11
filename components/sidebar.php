<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="lg:hidden flex items-center justify-between bg-gray-900 p-4">
    <a href="#" class="text-xl font-bold text-white">Admin Simont Mart</a>
    <button id="hamburger-btn" class="text-white focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>
</div>

<div id="sidebar" class="fixed lg:relative transform lg:transform-none -translate-x-full lg:translate-x-0 flex flex-col w-64 h-screen bg-gray-900 text-white transition-transform duration-300 ease-in-out">
    <div class="hidden lg:flex items-center justify-center h-16 bg-gray-800">
        <a href="#" class="text-xl font-bold text-white">Admin Simont Mart</a>
    </div>
    <nav class="flex-grow">
        <ul class="flex flex-col space-y-2 p-4">
            <li>
                <a href="dashboard.php" class="block py-2 px-4 rounded <?php echo $current_page == 'dashboard.php' ? 'bg-gray-700' : ''; ?>">Dashboard</a>
            </li>
            <li>
                <a href="data_pegawai.php" class="block py-2 px-4 rounded <?php echo $current_page == 'data_pegawai.php' || $current_page == 'tambah_pegawai.php' ? 'bg-gray-700' : ''; ?>">Data Pegawai</a>
            </li>
            <li>
                <a href="data_jabatan.php" class="block py-2 px-4 rounded <?php echo $current_page == 'data_jabatan.php' || $current_page == 'tambah_jabatan.php' ? 'bg-gray-700' : ''; ?>">Data Jabatan</a>
            </li>
            <li>
                <a href="tambah_gaji.php" class="block py-2 px-4 rounded <?php echo $current_page == 'tambah_gaji.php' || $current_page == 'kelola_gaji.php'? 'bg-gray-700' : ''; ?>">Penggajian</a>
            </li>
        </ul>
    </nav>
    <div class="flex items-center justify-center h-16 bg-gray-800">
        <a href="../logout.php" class="block py-2 px-4 rounded hover:bg-gray-700">Logout</a>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const hamburgerBtn = document.getElementById('hamburger-btn');

    hamburgerBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
</script>
