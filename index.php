<?php
include('components/header.php');
include('components/koneksi.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa admin
    $admin_sql = "SELECT * FROM admin WHERE username = '$username' and password = '$password'";
    $admin_result = $conn->query($admin_sql);

    // Query untuk memeriksa pegawai
    $pegawai_sql = "SELECT * FROM Pegawai WHERE username = '$username' and password = '$password'";
    $pegawai_result = $conn->query($pegawai_sql);

    if ($admin_result->num_rows > 0) {
        $_SESSION['login_user'] = $username;
        $_SESSION['role'] = 'admin';
        header("location: admin/dashboard.php");
    } elseif ($pegawai_result->num_rows > 0) {
        $_SESSION['login_user'] = $username;
        $_SESSION['role'] = 'pegawai';
        header("location: pegawai/dashboard.php");
    } else {
        $error = "Username atau Password salah";
    }
}
?>

<form action="" method="post">
    <div class="container">
        <h2>Login</h2>
        <label for="username"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required>
        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>
        <button type="submit">Login</button>
    </div>
</form>
<?php include('components/footer.php'); ?>