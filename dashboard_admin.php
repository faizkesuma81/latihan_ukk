<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya Admin yang bisa masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Fitur Hapus Data
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    // Ambil nama foto untuk dihapus dari folder uploads
    $cari_foto = mysqli_query($koneksi, "SELECT foto FROM alumni WHERE id_alumni = '$id_hapus'");
    $f = mysqli_fetch_assoc($cari_foto);
    if (!empty($f['foto']) && file_exists("uploads/" . $f['foto'])) {
        unlink("uploads/" . $f['foto']);
    }

    $query_hapus = mysqli_query($koneksi, "DELETE FROM alumni WHERE id_alumni = '$id_hapus'");
    if ($query_hapus) {
        echo "<script>alert('Data Berhasil Dihapus'); window.location='dashboard_admin.php';</script>";
    }
}

// Fitur Pencarian
$keyword = "";
if (isset($_POST['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
    $query = mysqli_query($koneksi, "SELECT * FROM alumni WHERE nama LIKE '%$keyword%' OR jurusan LIKE '%$keyword%' ORDER BY id_alumni DESC");
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM alumni ORDER BY id_alumni DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Management Alumni</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            /* Background yang sama dengan User Dashboard */
            background: linear-gradient(rgba(241, 245, 249, 0.7), rgba(241, 245, 249, 0.8)), 
                        url('foto-gedung.jpg') no-repeat center center fixed; 
            background-size: cover;
        }

        .navbar { 
            background: #1e40af; 
            padding: 15px 5%; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        
        /* Card dibuat solid putih agar data mudah dibaca */
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 20px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); 
        }

        .header-table { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px; 
            gap: 20px;
            flex-wrap: wrap;
        }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f8fafc; padding: 15px; text-align: left; font-size: 13px; color: #64748b; border-bottom: 2px solid #edf2f7; text-transform: uppercase; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 14px; background: white; }
        
        .btn-edit { background: #f59e0b; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; }
        .btn-hapus { background: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; }
        .btn-logout { background: #f87171; color: white; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; transition: 0.3s; }
        .btn-logout:hover { background: #ef4444; }

        .btn-tambah { background: #059669; color: white; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.3s; }
        .btn-tambah:hover { background: #047857; }

        .btn-cari { padding: 8px 18px; background: #1e40af; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn-reset { padding: 8px 18px; background: #64748b; color: white; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 600; transition: 0.3s; }
        .btn-reset:hover { background: #475569; }
        
        .img-table { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #f1f5f9; }
        .search-box { padding: 8px 15px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; width: 200px; }
    </style>
</head>
<body>

<nav class="navbar">
    <div style="display:flex; align-items:center; gap:10px;">
        <img src="logo-telkom-schools.png" width="30">
        <h2 style="margin:0; font-size:20px;">Admin Panel Alumni</h2>
    </div>
    <div>
        <span style="margin-right:15px;">Halo, <strong>Admin</strong></span>
        <a href="logout.php" class="btn-logout">Keluar</a>
    </div>
</nav>

<div class="container">
    <div class="card">
        <div class="header-table">
            <div>
                <h3 style="margin:0; color: #1e293b;">Manajemen Data Alumni</h3>
                <a href="tambah.php" class="btn-tambah" style="display:inline-block; margin-top:10px;">+ Tambah Alumni Baru</a>
            </div>
            <form method="POST" style="display: flex; gap: 8px; align-items: center;">
                <input type="text" name="keyword" class="search-box" placeholder="Cari nama/jurusan..." value="<?= htmlspecialchars($keyword) ?>">
                <button type="submit" name="cari" class="btn-cari">Cari</button>
                <a href="dashboard_admin.php" class="btn-reset">Reset</a>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Jurusan</th>
                    <th>Angkatan</th>
                    <th>ID User</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) : 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="font-weight:600; color: #1e40af;"><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= $row['jurusan'] ?></td>
                    <td><span style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-weight: 600;"><?= $row['angkatan'] ?></span></td>
                    <td><small style="color:#94a3b8;">User ID: <?= $row['id_user'] ?></small></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id_alumni'] ?>" class="btn-edit">Edit</a>
                        <a href="dashboard_admin.php?hapus=<?= $row['id_alumni'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                } else {
                ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">Data tidak ditemukan.</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

   <footer style="position: fixed; bottom: 20px; left: 0; width: 100%; text-align: center; z-index: 999; pointer-events: none;">
    <p style="background: rgba(255,255,255,0.9); display: inline-block; padding: 10px 25px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); color: #475569; font-size: 13px; margin: 0; pointer-events: auto; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.3);">
        © 2026 Crafted by <strong>ahmad faiz</strong>
    </p>
</footer>
</div>

</body>
</html>