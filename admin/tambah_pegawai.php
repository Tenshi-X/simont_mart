<?php
include('../components/header.php');
include('../components/koneksi.php');
$status = "";
$alert_color = "";
$redirect = false;

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

    // Cek apakah jabatan adalah kepala toko atau bendahara
    if ($id_jabatan == 4 || $id_jabatan == 5) {
        // Cek apakah jabatan ini sudah dimiliki oleh pegawai lain
        $cek_jabatan = $conn->query("SELECT COUNT(*) as jumlah FROM Pegawai WHERE id_jabatan = $id_jabatan");
        $result = $cek_jabatan->fetch_assoc();

        if ($result['jumlah'] > 0) {
            // Jika sudah ada pegawai dengan jabatan tersebut, tampilkan pesan kesalahan
            $status = "Gagal: Jabatan ini sudah diisi oleh pegawai lain.";
            $alert_color = "bg-red-100 border-red-400 text-red-700";
        } else {
            // Jika belum, lanjutkan dengan penyimpanan data
            $sql = "INSERT INTO Pegawai (id_jabatan, nama_pegawai, alamat, tempat_lahir, tanggal_lahir, tgl_masuk_kerja, username, password, no_hp) 
                    VALUES ('$id_jabatan', '$nama_pegawai', '$alamat', '$tempat_lahir', '$tanggal_lahir', '$tgl_masuk_kerja', '$username', '$password', '$no_hp')";

            if ($conn->query($sql) === TRUE) {
                $status = "Pegawai baru berhasil ditambahkan";
                $alert_color = "bg-green-100 border-green-400 text-green-700";
                $redirect = true;
            } else {
                $status = "Penambahan gagal";
                $alert_color = "bg-red-100 border-red-400 text-red-700";
            }
        }
    } else {
        // Untuk jabatan selain kepala toko dan bendahara, langsung simpan data
        $sql = "INSERT INTO Pegawai (id_jabatan, nama_pegawai, alamat, tempat_lahir, tanggal_lahir, tgl_masuk_kerja, username, password, no_hp) 
                VALUES ('$id_jabatan', '$nama_pegawai', '$alamat', '$tempat_lahir', '$tanggal_lahir', '$tgl_masuk_kerja', '$username', '$password', '$no_hp')";

        if ($conn->query($sql) === TRUE) {
            $status = "Pegawai baru berhasil ditambahkan";
            $alert_color = "bg-green-100 border-green-400 text-green-700";
            $redirect = true;
        } else {
            $status = "Penambahan gagal";
            $alert_color = "bg-red-100 border-red-400 text-red-700";
        }
    }
}

$jobs = $conn->query("SELECT * FROM Jabatan");
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
        <h2 class="text-3xl font-bold mb-4">Tambah Pegawai</h2>
        <form action="" method="POST" class="max-w-3xl py-4 bg-white rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-2">
                    <label for="nama_pegawai" class="block text-sm font-medium text-gray-700">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" id="nama_pegawai" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>  
                <div class="mb-2">
                    <label for="id_jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <select name="id_jabatan" id="id_jabatan" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <?php while ($row = $jobs->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id_jabatan']; ?>"><?php echo $row['nama_jabatan']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-2">
                    <label for="tgl_masuk_kerja" class="block text-sm font-medium text-gray-700">Tanggal Masuk Kerja</label>
                    <input type="date" name="tgl_masuk_kerja" id="tgl_masuk_kerja" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-2">
                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-2">
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-6 col-span-2">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700">No HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="mt-1 block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-300">Simpan</button>
        </form>

    </div>
</div>

<style>
    .slide-down {
        animation: slideDown 0.2    s ease-out forwards;
    }
    .slide-up {
        animation: slideUp 0.5s ease-in forwards;
    }
    @keyframes slideDown {
        from { transform: translateY(-100%); }
        to { transform: translateY(0); }
    }
    @keyframes slideUp {
        from { transform: translateY(0); }
        to { transform: translateY(-100%); }
    }
</style>

<?php include('../components/footer.php'); ?>
