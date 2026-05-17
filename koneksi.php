<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_alumni"; // Ganti jika nama database kamu berbeda

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>