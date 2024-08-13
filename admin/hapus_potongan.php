<?php
include('../components/koneksi.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM Potongan WHERE id_potongan = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: data_potongan.php?status=success&message=" . urlencode("Potongan berhasil dihapus"));
    } else {
        header("Location: data_potongan.php?status=error&message=" . urlencode("Penghapusan gagal"));
    }
} else {
    header("Location: data_potongan.php");
    exit;
}
?>
