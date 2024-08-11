<?php
include('../components/header.php');
include('../components/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nm_jabatan = $_POST['nm_jabatan'];
    $gaji_pokok = $_POST['gaji_pokok'];

    $sql = "INSERT INTO Jabatan (nama_jabatan, gaji_pokok) VALUES ('$nm_jabatan', '$gaji_pokok')";
    if ($conn->query($sql) === TRUE) {
        echo "Jabatan baru berhasil ditambahkan";
        header("location: admin/data_jabatan.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$jobs = $conn->query("SELECT * FROM Jabatan");
?>

<div class="flex flex-col lg:flex-row">
    <div class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </div>
    <div class="container mx-auto lg:w-4/5 p-4">
`       <h2 class="text-3xl font-bold mb-4">Kelola Jabatan</h2>
        <form action="" method="POST">
            <div class="mb-2">
                <label for="nm_jabatan" class="block text-sm font-medium text-gray-700">Nama Jabatan</label>
                <input type="text" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="nm_jabatan" required>
            </div>
            <div class="mb-2">
                <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
                <input type="number" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" name="gaji_pokok" required>
            </div>
            <button type="submit" class="inline-block bg-blue-500 text-white px-4 py-2 rounded mb-4">Submit</button>
        </form>
    </div>
    
</div>

<?php include('../components/footer.php'); ?>
