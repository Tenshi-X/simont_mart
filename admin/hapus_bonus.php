<?php
include('../components/koneksi.php');

$id_bonus = $_GET['id'];

$sql = "DELETE FROM Bonus WHERE id_bonus='$id_bonus'";
if ($conn->query($sql) === TRUE) {
    $status = "Bonus berhasil dihapus";
    $alert_color = "bg-green-100 border-green-400 text-green-700";
} else {
    $status = "Penghapusan gagal";
    $alert_color = "bg-red-100 border-red-400 text-red-700";
}

header("Location: data_bonus.php?status=success&message=" . urlencode($status));
?>
