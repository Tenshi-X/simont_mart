<?php
include('components/header.php');
include('components/koneksi.php');
session_start();

$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Pengecekan untuk pemilik
    if ($username === 'pemilik' && $password === 'pemilik') {
        $_SESSION['login_user'] = $username;
        $_SESSION['role'] = 'pemilik';
        header("location: pemilik/data_gaji.php");
        exit;
    }

    // Query untuk memeriksa admin
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $admin_result = $stmt->get_result();

    // Query untuk memeriksa pegawai
    $stmt = $conn->prepare("SELECT * FROM Pegawai WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $pegawai_result = $stmt->get_result();

    if ($admin_result->num_rows > 0) {
        $_SESSION['login_user'] = $username;
        $_SESSION['role'] = 'admin';
        header("location: admin/dashboard.php");
    } elseif ($pegawai_result->num_rows > 0) {
        $_SESSION['login_user'] = $username;
        $_SESSION['role'] = 'pegawai';
        header("location: pegawai/dashboard.php");
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<?php if (!empty($error)): ?>
<div id="alert" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 w-screen rounded absolute top-0 left-0" role="alert">
    <strong class="font-bold">Login Gagal!</strong>
    <span class="block sm:inline"><?php echo $error; ?></span>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById("alert");
        alert.classList.remove("hidden");
        alert.classList.add("slide-down");

        setTimeout(() => {
            alert.classList.remove("slide-down");
            alert.classList.add("slide-up");
        }, 3000); 
    });
</script>
<?php endif; ?>

<main class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="max-w-md w-full bg-white shadow-md rounded-lg p-8">
        <div class="text-center mb-6">
            <h1 class="text-3xl text-cyan-600 font-bold mb-2">Simont Mart</h1>
            <h2 class="text-2xl font-bold">Login</h2>
        </div>
        <form action="" method="post">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Username">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="********">
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Login
            </button>
        </form>
    </div>
</main>

<style>
    .slide-down {
        animation: slideDown 0.2s ease-out forwards;
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

<?php include('components/footer.php'); ?>
