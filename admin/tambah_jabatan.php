<?php
include('../components/header.php');
include('../components/koneksi.php');
$status = "";
$alert_color = "";
$redirect = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nm_jabatan = $_POST['nm_jabatan'];
    $gaji_pokok = $_POST['gaji_pokok'];

    $sql = "INSERT INTO Jabatan (nama_jabatan, gaji_pokok) VALUES ('$nm_jabatan', '$gaji_pokok')";
    if ($conn->query($sql) === TRUE) {
        $status = "Jabatan baru berhasil ditambahkan";
        $alert_color = "bg-green-100 border-green-400 text-green-700";
        $redirect = true;
    } else {
        $status = "Penambahan gagal";
        $alert_color = "bg-red-100 border-red-400 text-red-700";
    }
}

$jobs = $conn->query("SELECT * FROM Jabatan");
?>

<div class="flex flex-col lg:flex-row">
    <div class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </div>
    <div class="container mx-auto lg:w-4/5">
        <?php if (!empty($status)): ?>
            <div id="alert" class="hidden <?php echo $alert_color; ?> text-right px-4 py-3 relative w-full rounded top-0 right-0" role="alert">
                <strong class="text-black font-bold"><?php echo $status; ?></strong>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const alert = document.getElementById("alert");
                    alert.classList.remove("hidden");
                    alert.classList.add("slide-down");

                    setTimeout(() => {
                        alert.classList.remove("slide-down");
                        alert.classList.add("slide-up");

                        setTimeout(() => {
                            <?php if ($redirect): ?>
                                window.location.href = "data_jabatan.php";
                            <?php endif; ?>
                        }, 200);
                    }, 3000); 
                });
            </script>
        <?php endif; ?>
`       <h2 class="text-3xl font-bold mb-4 p-4">Kelola Jabatan</h2>
        <form action="" method="POST" class="px-4 w-2/5">
            <div class="mb-2">
                <label for="nm_jabatan" class="block text-sm font-medium text-gray-700">Nama Jabatan</label>
                <input type="text" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="nm_jabatan" required>
            </div>
            <div class="mb-4">
                <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
                <input type="number" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="gaji_pokok" required>
            </div>
            <button type="submit" class="inline-block bg-blue-500 text-white px-4 py-2 rounded mb-4">Submit</button>
        </form>
    </div>
    
</div>

<?php include('../components/footer.php'); ?>
