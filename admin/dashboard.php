<?php
include('../components/header.php');
include('../components/sidebar.php');
include('../components/koneksi.php');

// Fetch counts
$count_employee = $conn->query("SELECT COUNT(*) AS count FROM Pegawai")->fetch_assoc()['count'];
$count_jobs = $conn->query("SELECT COUNT(*) AS count FROM Jabatan")->fetch_assoc()['count'];
?>

<div class="container mt-5">
    <h2>Dashboard</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Pegawai</h5>
                    <p class="card-text"><?php echo $count_employee; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Jabatan</h5>
                    <p class="card-text"><?php echo $count_jobs; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../components/footer.php'); ?>
