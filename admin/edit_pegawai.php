<?php
include('../components/header.php');
include('../components/koneksi.php');

$status = "";
$alert_color = "";
$redirect = false;

if (isset($_GET['id'])) {
    $id_pegawai = $_GET['id'];

    // Ambil data pegawai berdasarkan ID
    $sql = "SELECT * FROM Pegawai WHERE id_pegawai = '$id_pegawai'";
    $result = $conn->query($sql);
    $pegawai = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_jabatan = $_POST['id_jabatan'];
        $nama_pegawai = $_POST['nama_pegawai'];
        $alamat = $_POST['alamat'];
        $tempat_lahir = $_POST['tempat_lahir'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $tgl_masuk_kerja = $_POST['tgl_masuk_kerja'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $no_hp = $_POST['no_hp'];

        $sql_update = "UPDATE Pegawai SET 
                        id_jabatan = '$id_jabatan', 
                        nama_pegawai = '$nama_pegawai', 
                        alamat = '$alamat', 
                        tempat_lahir = '$tempat_lahir', 
                        tanggal_lahir = '$tanggal_lahir', 
                        tgl_masuk_kerja = '$tgl_masuk_kerja', 
                        username = '$username', 
                        password = '$password', 
                        no_hp = '$no_hp' 
                    WHERE id_pegawai = '$id_pegawai'";

        if ($conn->query($sql_update) === TRUE) {
            $status = "Data pegawai berhasil diperbarui";
            $alert_color = "bg-green-100 border-green-400 text-green-700";
            $redirect = true;
        } else {
            $status = "Update gagal";
            $alert_color = "bg-red-100 border-red-400 text-red-700";
        }
    }

    $jobs = $conn->query("SELECT * FROM Jabatan");
} else {
    header("Location: data_pegawai.php");
}
?>

<div class="flex flex-col lg:flex-row">
    <aside class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </aside>
    <div class="container mx-auto lg:w-4/5 p-4 overflow-y-auto h-screen">
        <?php if (!empty($status)): ?>
        <div id="alert" class="hidden <?php echo $alert_color; ?> px-4 py-3 w-full rounded absolute top-0 left-0" role="alert">
            <strong class="font-bold"><?php echo $status; ?></strong>
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
                                window.location.href = "data_pegawai.php";
                            <?php endif; ?>
                        }, 300);
                    }, 3000); 
                });
            </script>
        <?php endif; ?>
        <h2 class="text-3xl font-bold mb-4">Edit Pegawai</h2>
        <form action="" method="POST" class="max-w-3xl py-4 bg-white rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-2">
                    <label for="nama_pegawai" class="block text-sm font-medium text-gray-700">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" id="nama_pegawai" value="<?php echo $pegawai['nama_pegawai']; ?>" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>  
                <div class="mb-2">
                    <label for="id_jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <select name="id_jabatan" id="id_jabatan" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <?php if ($jobs->num_rows > 0): ?>
                            <?php while ($job = $jobs->fetch_assoc()): ?>
                                <option value="<?php echo $job['id_jabatan']; ?>" <?php echo $pegawai['id_jabatan'] == $job['id_jabatan'] ? 'selected' : ''; ?>>
                                    <?php echo $job['nama_jabatan']; ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <input type="text" name="alamat" id="alamat" value="<?php echo $pegawai['alamat']; ?>" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>    
                <div class="mb-2">
                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="<?php echo $pegawai['tempat_lahir']; ?>" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-2">
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo $pegawai['tanggal_lahir']; ?>" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>  
                <div class="mb-2">
                    <label for="tgl_masuk_kerja" class="block text-sm font-medium text-gray-700">Tanggal Masuk Kerja</label>
                    <input type="date" name="tgl_masuk_kerja" id="tgl_masuk_kerja" value="<?php echo $pegawai['tgl_masuk_kerja']; ?>" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>  
                <div class="mb-2">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo $pegawai['username']; ?>" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" value="<?php echo $pegawai['password']; ?>" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-2">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700">No HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="<?php echo $pegawai['no_hp']; ?>" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
            </div>
        </form>
    </div>
</div>

<?php include('../components/footer.php'); ?>
