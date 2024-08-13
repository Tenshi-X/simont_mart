<?php
include('../components/header.php');
include('../components/koneksi.php');

$status = "";
$alert_color = "";
$redirect = false;
$id_bonus = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nm_bonus = $_POST['nm_bonus'];
    $jumlah_bonus = $_POST['jumlah_bonus'];

    $sql = "UPDATE Bonus SET nama_bonus='$nm_bonus', jumlah_bonus='$jumlah_bonus' WHERE id_bonus='$id_bonus'";
    if ($conn->query($sql) === TRUE) {
        $status = "Bonus berhasil diperbarui";
        $alert_color = "bg-green-100 border-green-400 text-green-700";
        $redirect = true;
    } else {
        $status = "Pembaruan gagal";
        $alert_color = "bg-red-100 border-red-400 text-red-700";
    }
}

$bonus = $conn->query("SELECT * FROM Bonus WHERE id_bonus='$id_bonus'")->fetch_assoc();
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
                                window.location.href = "data_bonus.php";
                            <?php endif; ?>
                        }, 200);
                    }, 3000); 
                });
            </script>
        <?php endif; ?>
        <h2 class="text-3xl font-bold mb-4 p-4">Edit Bonus</h2>
        <form action="" method="POST" class="px-4 w-2/5">
            <div class="mb-2">
                <label for="nm_bonus" class="block text-sm font-medium text-gray-700">Nama Bonus</label>
                <input type="text" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="nm_bonus" value="<?php echo $bonus['nama_bonus']; ?>" required>
            </div>
            <div class="mb-4">
                <label for="jumlah_bonus" class="block text-sm font-medium text-gray-700">Jumlah Bonus</label>
                <input type="number" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="jumlah_bonus" value="<?php echo $bonus['jumlah_bonus']; ?>" required>
            </div>
            <button type="submit" class="inline-block bg-blue-500 text-white px-4 py-2 rounded mb-4">Submit</button>
        </form>
    </div>
</div>

<?php include('../components/footer.php'); ?>
