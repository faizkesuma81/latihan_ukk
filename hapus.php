<?php
include 'koneksi.php';
$id = $_GET['id'];
if (!empty($id)) {
    mysqli_query($koneksi, "DELETE FROM alumni WHERE id_alumni = '$id'");
}
header("Location: dashboard_admin.php");
?>