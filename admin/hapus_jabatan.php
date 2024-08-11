<?php
include('../components/koneksi.php');

if (isset($_GET['id'])) {
    $id_jabatan = $_GET['id'];

    // Query untuk menghapus jabatan
    $sql = "DELETE FROM Jabatan WHERE id_jabatan = '$id_jabatan'";

    if ($conn->query($sql) === TRUE) {
        header("Location: data_jabatan.php?status=success&message=Jabatan berhasil dihapus");
    } else {
        header("Location: data_jabatan.php?status=error&message=Gagal menghapus jabatan");
    }
} else {
    header("Location: data_jabatan.php");
}

$conn->close();
?>
