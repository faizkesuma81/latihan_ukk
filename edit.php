<?php
session_start();
include 'koneksi.php';

// 1. CEK LOGIN
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 2. AMBIL ID DARI URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan!'); window.location='login.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM alumni WHERE id_alumni = '$id'");
$d = mysqli_fetch_assoc($query);

if (!$d) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='login.php';</script>";
    exit;
}

// 3. LOGIKA UPDATE
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    $jurusan = $_POST['jurusan'];
    
    // Cek folder uploads
    if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }

    if (!empty($_FILES['foto']['name'])) {
        $foto_baru = time() . "_" . $_FILES['foto']['name'];
        if (move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto_baru)) {
            if (!empty($d['foto']) && file_exists("uploads/" . $d['foto'])) { unlink("uploads/" . $d['foto']); }
            $sql = "UPDATE alumni SET nama='$nama', angkatan='$angkatan', jurusan='$jurusan', foto='$foto_baru' WHERE id_alumni='$id'";
        }
    } else {
        $sql = "UPDATE alumni SET nama='$nama', angkatan='$angkatan', jurusan='$jurusan' WHERE id_alumni='$id'";
    }

    if (mysqli_query($koneksi, $sql)) {
        // --- BAGIAN PALING PENTING ---
        // Jika yang login ADMIN, balik ke dashboard_admin.php
        // Jika yang login USER, balik ke dashboard_user.php
        if ($_SESSION['role'] == 'admin') {
            echo "<script>alert('Data Berhasil Diubah oleh Admin'); window.location='dashboard_admin.php';</script>";
        } else {
            echo "<script>alert('Profil Berhasil Diperbarui'); window.location='dashboard_user.php';</script>";
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; padding: 50px; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 350px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #1e40af; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .btn-batal { display: block; text-align: center; margin-top: 15px; color: #667; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="text-align:center">Edit Data</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= $d['nama'] ?>" required>
            <label>Angkatan</label>
            <input type="number" name="angkatan" value="<?= $d['angkatan'] ?>" required>
            <label>Jurusan</label>
            <select name="jurusan">
                <option value="Rekayasa Perangkat Lunak" <?= $d['jurusan'] == 'Rekayasa Perangkat Lunak' ? 'selected' : '' ?>>RPL</option>
                <option value="Teknik Komputer Jaringan" <?= $d['jurusan'] == 'Teknik Komputer Jaringan' ? 'selected' : '' ?>>TKJ</option>
                <option value="Teknik Jaringan Akses Telekomunikasi" <?= $d['jurusan'] == 'Teknik Jaringan Akses Telekomunikasi' ? 'selected' : '' ?>>TJAT</option>
            </select>
            <label>Ganti Foto</label>
            <input type="file" name="foto">
            <button type="submit" name="update">Simpan Perubahan</button>
            
            <a href="<?= ($_SESSION['role'] == 'admin') ? 'dashboard_admin.php' : 'dashboard_user.php' ?>" class="btn-batal">Batal / Kembali</a>
        </form>
    </div>
</body>
</html>