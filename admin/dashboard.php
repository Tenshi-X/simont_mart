<?php
include('../components/header.php');
include('../components/koneksi.php');

// Fetch counts
$count_employee = $conn->query("SELECT COUNT(*) AS count FROM Pegawai")->fetch_assoc()['count'];
$count_jobs = $conn->query("SELECT COUNT(*) AS count FROM Jabatan")->fetch_assoc()['count'];
?>

<div class="flex flex-col lg:flex-row">
    <div class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </div>

    <div class="container mx-auto lg:w-4/5 p-4">
        <h2 class="text-3xl font-bold mb-6">Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-500 text-white rounded-lg p-4">
                <h5 class="text-xl font-semibold">Jumlah Pegawai</h5>
                <p class="mt-2 text-lg"><?php echo $count_employee; ?></p>
            </div>
            <div class="bg-green-500 text-white rounded-lg p-4">
                <h5 class="text-xl font-semibold">Jumlah Jabatan</h5>
                <p class="mt-2 text-lg"><?php echo $count_jobs; ?></p>
            </div>
        </div>
    </div>
</div>

<?php include('../components/footer.php'); ?>
