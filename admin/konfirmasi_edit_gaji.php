<?php
include('../components/header.php');
include('../components/koneksi.php');

// Set timezone ke GMT+7
date_default_timezone_set('Asia/Jakarta');

// Tangkap data yang dikirim dari edit_gaji.php
$id_gaji = isset($_GET['id_gaji']) ? $_GET['id_gaji'] : '';
$id_pegawai = isset($_GET['id_pegawai']) ? $_GET['id_pegawai'] : '';
$jumlah_hadir = isset($_GET['jumlah_hadir']) ? $_GET['jumlah_hadir'] : '';
$tgl_gaji = isset($_GET['tgl_gaji']) ? $_GET['tgl_gaji'] . ' ' . date('H:i:s') : date('Y-m-d H:i:s');
$gaji_pokok = isset($_GET['gaji_pokok']) ? $_GET['gaji_pokok'] : '';
$gaji_lembur = isset($_GET['gaji_lembur']) ? $_GET['gaji_lembur'] : '';
$bonus_kinerja = isset($_GET['bonus_kinerja']) ? $_GET['bonus_kinerja'] : '';
$bonus_jabatan = isset($_GET['bonus_jabatan']) ? $_GET['bonus_jabatan'] : '';
$tot_bonus = isset($_GET['tot_bonus']) ? $_GET['tot_bonus'] : '';
$tot_potongan = isset($_GET['tot_potongan']) ? $_GET['tot_potongan'] : '';
$keterlambatan = isset($_GET['keterlambatan']) ? $_GET['keterlambatan'] : '';

// Ambil id_jabatan berdasarkan id_pegawai
$id_jabatan_query = $conn->query("SELECT id_jabatan FROM Pegawai WHERE id_pegawai = '$id_pegawai'");
$id_jabatan_result = $id_jabatan_query->fetch_assoc();
$id_jabatan = $id_jabatan_result['id_jabatan'];

// Hitung total potongan tambahan jika kehadiran kurang dari 26 hari
$potongan_kehadiran = 0;
$potongan_kerugian = $tot_potongan;
$potongan_per_hari = 100000;
$potongan_keterlambatan_per_jam = (($gaji_pokok / 26) / 9);

if ($tot_potongan != 0) {
    $presentase = 15;
} else {
    $presentase = 0;
}

if ($jumlah_hadir < 26) {
    $potongan_kehadiran = (26 - $jumlah_hadir) * $potongan_per_hari;
}

if ($potongan_kehadiran != 0) {
    $id_tidak_hadir = 7;
} else {
    $id_tidak_hadir = NULL;
}

if ($id_jabatan == 4) {
    $id_bonus_jabatan = 3;
    $id_keterlambatan = 3;
} else if ($id_jabatan == 5) {
    $id_bonus_jabatan = 4;
    $id_keterlambatan = 4;
} else if ($id_jabatan == 6) {
    $id_bonus_jabatan = 6;
    $id_keterlambatan = 5;
} else if ($id_jabatan == 7) {
    $id_bonus_jabatan = 7;
    $id_keterlambatan = 6;
}

if ($tot_potongan != 0) {
    $id_kerugian = 2;
} else {
    $id_kerugian = NULL;
}

if ($bonus_kinerja != 0) {
    $id_bonus_kinerja = 2;
} else {
    $id_bonus_kinerja = 5;
}

$potongan_keterlambatan = $potongan_keterlambatan_per_jam * $keterlambatan;

// Perhitungan total potongan
$tot_potongan_final = $potongan_kehadiran + $potongan_kerugian + $potongan_keterlambatan;

// Perhitungan total gaji setelah potongan
$tot_gaji_final = $gaji_pokok - $tot_potongan_final + $gaji_lembur + $tot_bonus;
$status = "";
$alert_color = "";
$redirect = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pegawai = $_POST['id_pegawai'];
    $jumlah_hadir = $_POST['jumlah_hadir'];
    $tgl_gaji = $_POST['tgl_gaji'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $gaji_lembur = $_POST['gaji_lembur'];
    $tot_bonus = $_POST['tot_bonus'];
    $tot_potongan = $_POST['tot_potongan'];
    $tot_gaji = $_POST['tot_gaji'];

    $sql = "UPDATE Gaji SET 
                        id_pegawai = '$id_pegawai', 
                        jumlah_hadir = '$jumlah_hadir', 
                        tgl_gaji = '$tgl_gaji', 
                        gaji_pokok = '$gaji_pokok', 
                        gaji_lembur = '$gaji_lembur', 
                        tot_bonus = '$tot_bonus', 
                        tot_potongan = '$tot_potongan', 
                        tot_gaji = '$tot_gaji'
                    WHERE id_gaji = '$id_gaji'";
    if ($conn->query($sql) === TRUE) {
        $status = "Pencatatan berhasil";
        $alert_color = "bg-green-100 border-green-400 text-green-700";
        $redirect = true;
    } else {
        $status = "Pencatatan gagal";
        $alert_color = "bg-red-100 border-red-400 text-red-700";
    }

    // Update gaji_potongan
    $potongan_data = [
        'kerugian' => [$id_kerugian, $potongan_kerugian],
        'keterlambatan' => [$id_keterlambatan, $potongan_keterlambatan],
        'tidak_hadir' => [$id_tidak_hadir, $potongan_kehadiran]
    ];

    foreach ($potongan_data as $potongan) {
        if ($potongan[0] !== NULL) {
            $sql = "SELECT * FROM gaji_potongan WHERE id_gaji = '$id_gaji' AND id_potongan = '{$potongan[0]}'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $sql = "UPDATE gaji_potongan SET nilai_potongan = '{$potongan[1]}', tgl_gaji = '$tgl_gaji' 
                        WHERE id_gaji = '$id_gaji' AND id_potongan = '{$potongan[0]}'";
            } else {
                $sql = "INSERT INTO gaji_potongan (id_gaji, id_potongan, nilai_potongan, tgl_gaji) 
                        VALUES ('$id_gaji', '{$potongan[0]}', '{$potongan[1]}', '$tgl_gaji')";
            }
            $conn->query($sql);
        }
    }

    // Update gaji_bonus
    $bonus_data = [
        'kinerja' => [$id_bonus_kinerja, $bonus_kinerja],
        'jabatan' => [$id_bonus_jabatan, $bonus_jabatan]
    ];

    foreach ($bonus_data as $bonus) {
        if ($bonus[0] !== NULL) {
            $sql = "SELECT * FROM gaji_bonus WHERE id_gaji = '$id_gaji' AND id_bonus = '{$bonus[0]}'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $sql = "UPDATE gaji_bonus SET nilai_bonus = '{$bonus[1]}', tgl_gaji = '$tgl_gaji' 
                        WHERE id_gaji = '$id_gaji' AND id_bonus = '{$bonus[0]}'";
            } else {
                $sql = "INSERT INTO gaji_bonus (id_gaji, id_bonus, nilai_bonus, tgl_gaji) 
                        VALUES ('$id_gaji', '{$bonus[0]}', '{$bonus[1]}', '$tgl_gaji')";
            }
            $conn->query($sql);
        }
    }
}

$employees = $conn->query("SELECT * FROM Pegawai");
?>




<div class="flex flex-col lg:flex-row">
    <aside class="lg:w-1/5">
        <?php include('../components/sidebar.php'); ?>
    </aside>

    <main class="flex-1 p-6">
    <?php if (!empty($status)): ?>
    <div id="alert" class="hidden <?php echo $alert_color; ?> px-4 py-3 w-4/5 rounded absolute text-left top-0 right-0" role="alert">
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
                        window.location.href = "data_gaji.php";
                    <?php endif; ?>
                }, 300);
            }, 3000); 
        });
    </script>
    <?php endif; ?>
        <h2 class="text-2xl font-bold mb-6">Konfirmasi Edit Pencatatan Penggajian</h2>
        <form action="" method="POST" class="max-w-3xl py-4 bg-white rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-1">
                    <label for="id_pegawai" class="block text-sm font-medium text-gray-700">Pegawai</label>
                    <select name="id_pegawai" id="id_pegawai" class=" block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <?php while ($row = $employees->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id_pegawai']; ?>" <?php if($row['id_pegawai'] == $id_pegawai) echo 'selected'; ?>>
                                <?php echo $row['nama_pegawai']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-1">
                    <label for="jumlah_hadir" class="block text-sm font-medium text-gray-700">Jumlah Hadir</label>
                    <input type="number" id="jumlah_hadir" name="jumlah_hadir" class=" block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo $jumlah_hadir; ?>" readonly required>
                </div>
                <div class="mb-1">
                    <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji Pokok</label>
                    <input type="text" id="gaji_pokok_display" class="block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo 'Rp ' . number_format($gaji_pokok, 0, ',', '.'); ?>" readonly required>
                    <input type="hidden" id="gaji_pokok" name="gaji_pokok" value="<?php echo $gaji_pokok; ?>">
                </div>
                <div class="mb-1">
                    <label for="gaji_lembur" class="block text-sm font-medium text-gray-700">Gaji Lembur</label>
                    <input type="text" id="gaji_lembur_display" class="block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo 'Rp ' . number_format($gaji_lembur, 0, ',', '.'); ?>" readonly required>
                    <input type="hidden" id="gaji_lembur" name="gaji_lembur" value="<?php echo $gaji_lembur; ?>">
                </div>
                <div class="mb-1">
                    <label for="tot_bonus" class="block text-sm font-medium text-gray-700">Total Bonus</label>
                    <input type="text" id="tot_bonus_display" class="block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo 'Rp ' . number_format($tot_bonus, 0, ',', '.'); ?>" readonly required>
                    <input type="hidden" id="tot_bonus" name="tot_bonus" value="<?php echo $tot_bonus; ?>">
                </div>
                <div>
                    <input type="hidden" id="tgl_gaji" name="tgl_gaji" value="<?php echo $tgl_gaji; ?>">
                </div>
                <div class="mb-1 col-span-2">
                    <label for="tot_potongan" class="block text-sm font-medium text-gray-700">Total Potongan (Rp)</label>
                    <input type="text" id="tot_potongan_display" class="block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo 'Rp ' . number_format($tot_potongan_final, 0, ',', '.'); ?>" readonly required>
                    <input type="hidden" id="tot_potongan" name="tot_potongan" value="<?php echo $tot_potongan_final; ?>">
                    <p class="mt-2 text-sm text-gray-600">
                        Potongan Kehadiran: Rp <?php echo number_format($potongan_kehadiran, 0, ',', '.'); ?> 
                        (<?php echo (26 - $jumlah_hadir); ?> hari tidak hadir x Rp <?php echo number_format($potongan_per_hari, 0, ',', '.'); ?> per hari)
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Potongan Kerugian Barang: Rp <?php echo number_format($potongan_kerugian, 0, ',', '.'); ?> 
                        (<?php echo $presentase ?>% dari kerugian barang)
                    </p>
                    <p class="mt-2 text-sm text-gray-600">
                        Potongan keterlambatan: Rp <?php echo number_format($potongan_keterlambatan, 0, ',', '.'); ?> 
                        (<?php echo $keterlambatan; ?> jam terlambat x Rp <?php echo number_format($potongan_keterlambatan_per_jam, 0, ',', '.'); ?> per jam)
                    </p>
                </div>


                <div class="mb-4 col-span-2">
                    <label for="tot_gaji_display" class="block text-sm font-medium text-gray-700">Total Gaji</label>
                    <input type="text" id="tot_gaji_display" class="block w-full px-2 py-2 border border-black rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="<?php echo 'Rp ' . number_format($tot_gaji_final, 0, ',', '.'); ?>" readonly required>
                    <input type="hidden" id="tot_gaji" name="tot_gaji" value="<?php echo $tot_gaji_final; ?>">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </form>
    </main>
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
